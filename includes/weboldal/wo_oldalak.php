<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujoldal")
			oldalak_hozzaad();
		elseif ($_REQUEST["opt"] == "ujmentes")
			oldalak_uj_mentes();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			oldalak_bekapcsol();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			oldalak_kikapcsol();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			oldalak_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			oldalak_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			oldalak_torles();
		else
			oldalak_lista();
	}
	else
	{
		oldalak_lista();
	}
}
else
{
    return false;
}

	function oldalak_bekapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$bekapcsol=$DB->query("update ".prefix."_oldalak_".$_SESSION["lang"]." set oldalaktiv='1' where oldalid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalak");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalak_lista();
		}
	}
	function oldalak_kikapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$kikapcsol=$DB->query("update ".prefix."_oldalak_".$_SESSION["lang"]." set oldalaktiv='0' where oldalid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalak");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalak_lista();
		}
	}
	function oldalak_torles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//kép törlése
			$beload=$DB->query("select ogimage from ".prefix."_oldalak_".$_SESSION["lang"]." where oldalid='".$_REQUEST["id"]."'");
			if($beload->rowCount()>0)
			{
				$b=$beload->fetch();
				unlink("../uploads/".$b["ogimage"]);
			}
			//adatbázis bejegyzés törlése
			$torles=$DB->query("delete from ".prefix."_oldalak_".$_SESSION["lang"]." where oldalid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalak");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalak_lista();
		}
	}
	function oldalak_uj_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["oldalcim"]) || empty($form_data["oldalcim"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			if (!isset($form_data["metatitle"]) || empty($form_data["metatitle"]))
				throw new Exception("A meta title kitöltése kötelező!");
			if (!isset($form_data["metadesc"]) || empty($form_data["metadesc"]))
				throw new Exception("A meta leirás kitöltése kötelező!");
			if (!isset($form_data["oldalaktiv"]) || empty($form_data["oldalaktiv"]))
				throw new Exception("A bekapcsolás jelző kitöltése kötelező!");
			
			//kép adatok ellenőrzése
			if(!isset($_FILES["ogimage"]) || $_FILES["ogimage"]["size"] == 0)
				throw new Exception("Hiányzó kiemelt kép, vagy ez a fájl nem is kép!");
			
			if($_FILES["ogimage"]["type"] != "image/jpeg" && $_FILES["ogimage"]["type"] != "image/png")
				throw new Exception("Nem megengedett képformátum, kérem válasszon jpg vagy png képet!");
			
			//adatok előkészítése
			global $mirol, $mire;
			$oldalfurl = str_replace($mirol, $mire, $form_data["oldalcim"]);
			$oldalfurl = to_linknew($oldalfurl);
			
			//kép feltöltése, ha sikeresek voltak az ellenőrzések
			$file_ext = substr($_FILES["ogimage"]['name'], strripos($_FILES["ogimage"]['name'], '.'));
			$ogimage =  $oldalfurl . uniqid("-") . $file_ext;
			
			if(!move_uploaded_file($_FILES["ogimage"]['tmp_name'], "../uploads/" . $ogimage))
				throw new Exception("Hiba történt a képfeltöltés során!");
			
			include("includes/SimpleImage.php");
			list($width, $height) = getimagesize("../uploads/" . $ogimage);
			if($width>="1920")
			{
				$uks=$oldalfurl . uniqid("-") . "_" . $file_ext;
				//Képméretező FÁNKSÖN
				$image = new SimpleImage();
				$image->load("../uploads/" . $ogimage);
				$image->resizeToWidth(1920);
				$image->save("../uploads/".$uks);
				//régi nagy kép törlése
				unlink("../uploads/" . $ogimage);
				$ogimage=$uks;
			}
			
			$DB = connect(true);
			$elment=$DB->query("insert into ".prefix."_oldalak_".$_SESSION["lang"]." (oldalcim,oldalcont,furl,tomodul,tolink,metatitle,metadesc,ogimage,oldalaktiv) values ('".$form_data["oldalcim"]."','".$form_data["oldalcont"]."','".$oldalfurl."','".$form_data["tomodul"]."','".$form_data["tolink"]."','".$form_data["metatitle"]."','".$form_data["metadesc"]."','".$ogimage."','".$form_data["oldalaktiv"]."')");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalak");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalak_lista();
		}
	}
	function oldalak_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["oldalcim"]) || empty($form_data["oldalcim"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			if (!isset($form_data["metatitle"]) || empty($form_data["metatitle"]))
				throw new Exception("A meta title kitöltése kötelező!");
			if (!isset($form_data["metadesc"]) || empty($form_data["metadesc"]))
				throw new Exception("A meta leirás kitöltése kötelező!");
			
			$DB = connect(true);
			
			//adatok előkészítése
			global $mirol, $mire;
			$oldalfurl = str_replace($mirol, $mire, $form_data["oldalcim"]);
			$oldalfurl = to_linknew($oldalfurl);
			
			//kép adatok ellenőrzése, ha feltölt ujat
			if(isset($_FILES["ogimage"]) AND $_FILES["ogimage"]["size"] != 0)
			{
				if($_FILES["ogimage"]["size"] == 0)
					throw new Exception("Hiányzó kiemelt kép, vagy ez a fájl nem is kép!");
				
				if($_FILES["ogimage"]["type"] != "image/jpeg" && $_FILES["ogimage"]["type"] != "image/png")
					throw new Exception("Nem megengedett képformátum, kérem válasszon jpg vagy png képet!");
				
				//kép feltöltése, ha sikeresek voltak az ellenőrzések
				$file_ext = substr($_FILES["ogimage"]['name'], strripos($_FILES["ogimage"]['name'], '.'));
				$ogimage =  $oldalfurl . uniqid("-") . $file_ext;
				
				if(!move_uploaded_file($_FILES["ogimage"]['tmp_name'], "../uploads/" . $ogimage))
					throw new Exception("Hiba történt a képfeltöltés során!");
				
				include("includes/SimpleImage.php");
				list($width, $height) = getimagesize("../uploads/" . $ogimage);
				if($width>="1920")
				{
					$uks=$oldalfurl . uniqid("-") . "_" . $file_ext;
					//Képméretező FÁNKSÖN
					$image = new SimpleImage();
					$image->load("../uploads/" . $ogimage);
					$image->resizeToWidth(1920);
					$image->save("../uploads/".$uks);
					//régi nagy kép törlése
					unlink("../uploads/" . $ogimage);
					$ogimage=$uks;
				}
				
				//régi kép törlése
				$beload=$DB->query("select ogimage from ".prefix."_oldalak_".$_SESSION["lang"]." where oldalid='".$form_data["oldalid"]."'");
				if($beload->rowCount()>0)
				{
					$b=$beload->fetch();
					unlink("../uploads/".$b["ogimage"]);
				}
				$ogimagechange="ogimage='".$ogimage."',";
			}
			else
			{
				$ogimagechange="";
			}

			$elment=$DB->query("update ".prefix."_oldalak_".$_SESSION["lang"]." set 
				oldalcim='".$form_data["oldalcim"]."',
				oldalcont='".$form_data["oldalcont"]."',
				furl='".$oldalfurl."',
				tomodul='".$form_data["tomodul"]."',
				tolink='".$form_data["tolink"]."',
				metatitle='".$form_data["metatitle"]."',
				metadesc='".$form_data["metadesc"]."',
				".$ogimagechange."
				oldalaktiv='".$form_data["oldalaktiv"]."' 
			where oldalid='".$form_data["oldalid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalak");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalak_lista();
		}
	}
	function oldalak_hozzaad()
	{
		$mirol=array("í","é","á","ű","ú","ő","ó","ü","ö","Í","É","Á","Ű","Ú","Ő","Ó","Ü","Ö","§","\"","_","+",":","%",",","?","=","*","(",")","<",">","[","]","{","}","&","#","@","<",">","$","'","!","/",";"," ");
		$mire=array("i","e","a","u","u","o","o","u","o","i","e","a","u","u","o","o","u","o","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-");
		echo '<script type="text/javascript" src="'.adminurl.'assets/kekcms/ckeditor/ckeditor.js"></script>
				<script src="'.adminurl.'assets/kekcms/ckeditor/adapters/jquery.js"></script>
				<script>
					CKEDITOR.env.isCompatible = true;
				</script>';
		$DB = NULL;
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új oldal hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=weboldal&thing=oldalak&opt=ujmentes" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label for="oldalcim">Új oldal címe</label>
								<input type="text" name="oldalcim" id="oldalcim" class="form-control" placeholder="Adja meg az új oldal nevét, címét" maxlength="250" required />
							</div>
							<div class="form-group">
								<label for="metatitle">Meta címsor (title)</label>
								<input type="text" name="metatitle" id="metatitle" class="form-control" placeholder="Adja meg az új oldal lapfül címét" maxlength="250" required />
							</div>
							<div class="form-group">
								<label for="metadesc">Meta leírás (description)</label>
								<textarea name="metadesc" id="metadesc" class="form-control" rows="5" placeholder="Pár sorban irja le mit fog tartalmazni az új oldal, mint egy összefoglalószerűen." required></textarea>
							</div>
							<div class="form-group">
								<label for="ogimage">Kiemelt kép</label>
								<input type="file" name="ogimage" id="ogimage" class="form-control" accept=".jpg, .jpeg, .png, .webp" />
							</div>
							<div class="form-group">
								<label for="tomodul">Rendelsz hozzá modult?</label>
								<select name="tomodul" id="tomodul" class="form-control">
									<option value="">Kérlek válassz, ha igen</option>';
									$DB = connect(true);
									$modulok=$DB->query("select * from ".prefix."_modul_".$_SESSION["lang"]." where aktiv='1' and integ='1'");
									while($m=$modulok->fetch())
									{
										echo '<optgroup label="'.$m["modulnev"].'">';
										$modulbe=$DB->query("select * from ".prefix."_".str_replace($mirol,$mire,strtolower($m["modulnev"]))."_".$_SESSION["lang"]." ");
										if($modulbe->rowCount()>0)
										{
											while($modul=$modulbe->fetch())
											{
												echo '<option value="'.$m["modulid"].'|'.$modul[str_replace($mirol,$mire,strtolower($m["modulnev"]))."id"].'">'.$modul[str_replace($mirol,$mire,strtolower($m["modulnev"]))."nev"].'</option>';
											}
										}
										else
										{
											echo '<option value="" disabled>Nincs rendelkezésre álló adat ennél a modulnál.</option>';
										}
									}
									$DB = NULL;
				echo '			</select>
							</div>
							<div class="form-group">
								<label for="oldalaktiv">Oldal be van kapcsolva?</label>
								<select name="oldalaktiv" id="oldalaktiv" class="form-control">
									<option value="1" selected>IGEN</option>
									<option value="0">NEM</option>
								</select>
							</div>
							<div class="form-group">
								<label for="tolink">Itt megadhatsz külső linket a tartalom készités helyett</label>
								<input type="text" name="tolink" id="tolink" class="form-control" placeholder="Adja meg a linket ahova ez az oldal mutasson, vagy alább ird meg a tartalmat te" />
							</div>
							<div class="form-group">
								<label for="oldalcont">Oldal tartalma</label>
								<textarea name="oldalcont" id="oldalcont" class="form-control" rows="15" placeholder="Szerkeszd meg a taratlmad, amit csak szeretnél..."></textarea>
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
								<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=oldalak"><span class="mdi mdi-arrow-left"></span> Vissza</a>
							</div>
						</form>
					</div>
				</div>';
		?>
			<script>
				CKEDITOR.replace( 'oldalcont', {
				language: 'hu',
				height: 800,
			<?php
				$useragent=$_SERVER['HTTP_USER_AGENT'];
				if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
				{
					echo 'toolbar : \'Basic\'';
				}
				else
				{
					echo 'toolbar : \'Full\'';
				}
			?>
				});
			</script>
		<?php
	}
	function oldalak_szerkeszt()
	{
		$mirol=array("í","é","á","ű","ú","ő","ó","ü","ö","Í","É","Á","Ű","Ú","Ő","Ó","Ü","Ö","§","\"","_","+",":","%",",","?","=","*","(",")","<",">","[","]","{","}","&","#","@","<",">","$","'","!","/",";"," ");
		$mire=array("i","e","a","u","u","o","o","u","o","i","e","a","u","u","o","o","u","o","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-");
		$DB = NULL;
		try
		{
			echo '<script src="'.adminurl.'assets/kekcms/ckeditor/adapters/jquery.js"></script>
					<script type="text/javascript" src="'.adminurl.'assets/kekcms/ckeditor/ckeditor.js"></script>
					<script>
						CKEDITOR.env.isCompatible = true;
					</script>';

			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_oldalak_".$_SESSION["lang"]." where oldalid='".$_REQUEST["id"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott oldal és tartalom az azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Oldal szerkesztése </h2>
						<div class="card">
							<div class="card-body">
								<form action="'.adminurl.'index.php?action=weboldal&thing=oldalak&opt=szerkesztmentes" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="oldalid" value="'.$_REQUEST["id"].'">
									<div class="form-group">
										<label for="oldalcim">Új oldal címe</label>
										<input type="text" name="oldalcim" id="oldalcim" value="'.$res["oldalcim"].'" class="form-control" placeholder="Adja meg az új oldal nevét, címét" maxlength="250" required />
									</div>
									<div class="form-group">
										<label for="metatitle">Meta címsor (title)</label>
										<input type="text" name="metatitle" id="metatitle" value="'.$res["metatitle"].'" class="form-control" placeholder="Adja meg az új oldal lapfül címét" maxlength="250" required />
									</div>
									<div class="form-group">
										<label for="metadesc">Meta leírás (description)</label>
										<textarea name="metadesc" id="metadesc" class="form-control" rows="5" placeholder="Pár sorban irja le mit fog tartalmazni az új oldal, mint egy összefoglalószerűen." required>'.$res["metadesc"].'</textarea>
									</div>
									<div class="form-group">
										<label for="ogimage">Kiemelt kép</label>
										<input type="file" name="ogimage" id="ogimage" class="form-control" accept=".jpg, .jpeg, .png, .webp" />
										'.($res["ogimage"]!='' ? '<br><img src="/uploads/'.$res["ogimage"].'" style="max-width:350px;">' : '').'
									</div>
									<div class="form-group">
										<label for="tomodul">Rendelsz hozzá modult?</label>
										<select name="tomodul" id="tomodul" class="form-control">
											<option value="">Kérlek válassz, ha igen</option>';
											$DB = connect(true);
											$modulok=$DB->query("select * from ".prefix."_modul_".$_SESSION["lang"]." where aktiv='1' and integ='1'");
											while($m=$modulok->fetch())
											{
												$meglevo=explode("|",$res["tomodul"]);
												echo '<optgroup label="'.$m["modulnev"].'">';
												$modulbe=$DB->query("select * from ".prefix."_".str_replace($mirol,$mire,strtolower($m["modulnev"]))."_".$_SESSION["lang"]." ");
												if($modulbe->rowCount()>0)
												{
													while($modul=$modulbe->fetch())
													{
														echo '<option value="'.$m["modulid"].'|'.$modul[str_replace($mirol,$mire,strtolower($m["modulnev"]))."id"].'" '.($meglevo[1]==$modul[str_replace($mirol,$mire,strtolower($m["modulnev"]))."id"] ? 'selected' : '').'>'.$modul[str_replace($mirol,$mire,strtolower($m["modulnev"]))."nev"].'</option>';
													}
												}
												else
												{
													echo '<option value="" disabled>Nincs rendelkezésre álló adat ennél a modulnál.</option>';
												}
											}
											$DB = NULL;
						echo '			</select>
									</div>
									<div class="form-group">
										<label for="oldalaktiv">Oldal be van kapcsolva?</label>
										<select name="oldalaktiv" id="oldalaktiv" class="form-control">
											<option value="1" '.($res["oldalaktiv"]=='1' ? 'selected' : '').'>IGEN</option>
											<option value="0" '.($res["oldalaktiv"]=='0' ? 'selected' : '').'>NEM</option>
										</select>
									</div>
									<div class="form-group">
										<label for="tolink">Itt megadhatsz külső linket a tartalom készités helyett</label>
										<input type="text" name="tolink" id="tolink" class="form-control" value="'.$res["tolink"].'" placeholder="Adja meg a linket ahova ez az oldal mutasson, vagy alább ird meg a tartalmat te" />
									</div>
									<div class="form-group">
										<label for="oldalcont">Oldal tartalma</label>
										<textarea name="oldalcont" id="oldalcont" class="form-control" rows="15" placeholder="Szerkeszd meg a taratlmad, amit csak szeretnél..." required>'.$res["oldalcont"].'</textarea>
									</div>
									<div>
										<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
										<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=oldalak"><span class="mdi mdi-arrow-left"></span> Vissza</a>
									</div>
								</form>
							</div>
						</div>';
				?>
					<script>
						CKEDITOR.replace( 'oldalcont', {
						language: 'hu',
						height: 800,
					<?php
						$useragent=$_SERVER['HTTP_USER_AGENT'];
						if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
						{
							echo 'toolbar : \'Basic\'';
						}
						else
						{
							echo 'toolbar : \'Full\'';
						}
					?>
						});
					</script>
				<?php
			}
		}
		catch(Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalak_lista();
		}
	}
	function oldalak_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_oldalak_".$_SESSION["lang"]."");
			$res = $sth->fetchAll();
			$DB = NULL;

			?>
			<div class="card">
				<div class="card-body">
					<h2>Oldalak és tartalmak listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=weboldal&thing=oldalak&opt=ujoldal" class="btn btn-primary btn-sm">+ Új hozzáadása</a>

					<div class="mt-3">
						<h2>Jelenlegi oldalak</h2>
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen létrehozott oldal sem!</p>';
						}
						else
						{
			?>
							<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Oldal címe</big></strong></th>
								  <th scope="col"><strong><big>Bekapcsolva?</big></strong></th>
								  <th scope="col"><strong><big>Műveletek</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
			<?php 
							$out = "";
							foreach($res as $row){ 
								$out .= '<tr>';
									$out .= '<td>' . $row["oldalcim"] . '</td>';
									$out .= '<td>' . ($row["oldalaktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalak&opt=kikapcsol&id=' . $row["oldalid"] . '" class="btn btn-outline-warning px-1 py-0"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalak&opt=bekapcsol&id=' . $row["oldalid"] . '" class="btn btn-outline-warning px-1 py-0"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</td>';
									$out .= '<td>';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalak&opt=szerkesztes&id=' . $row["oldalid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalak&opt=torles&id=' . $row["oldalid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
									$out .= '</td>';
								$out .= '</tr>';
							}
							$out .= '</tbody></table>';
							echo $out;
						}
			?>
					</div>
				</div>
			</div>
			<?php 
		}
		catch(Exception $e)
		{
			$DB = NULL;
			echo '<p class="text-danger">' . $e->getMessage() . '</p>';
		}
	}
?>

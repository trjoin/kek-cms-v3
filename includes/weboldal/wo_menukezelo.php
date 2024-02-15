<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujmenupont")
			menu_hozzaad();
		elseif ($_REQUEST["opt"] == "ujmentes")
			menu_uj_mentes();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			menu_bekapcsol();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			menu_kikapcsol();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			menu_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			menu_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			menu_torles();
		else
			menu_lista();
	}
	else
	{
		menu_lista();
	}
}
else
{
    return false;
}

	function menu_bekapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$bekapcsol=$DB->query("update ".prefix."_menupontok_".lang." set menuaktiv='1' where menuid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_err_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=menukezelo");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			menu_lista();
		}
	}
	function menu_kikapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$kikapcsol=$DB->query("update ".prefix."_menupontok_".lang." set menuaktiv='0' where menuid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_err_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=menukezelo");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			menu_lista();
		}
	}
	function menu_torles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$torles=$DB->query("delete from ".prefix."_menupontok_".lang." where menuid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_err_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=menukezelo");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			menu_lista();
		}
	}
	function menu_uj_mentes()
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
			$elment=$DB->query("insert into ".prefix."_menupontok_".lang." (oldalcim,oldalcont,furl,tomodul,metatitle,metadesc,ogimage,oldalaktiv) values ('".$form_data["oldalcim"]."','".$form_data["oldalcont"]."','".$oldalfurl."','".$form_data["tomodul"]."','".$form_data["metatitle"]."','".$form_data["metadesc"]."','".$ogimage."','".$form_data["oldalaktiv"]."')");
			$DB = NULL;
			$_SESSION["php_err_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=menukezelo");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
		}
	}
	function menu_szerkeszt_mentes()
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
				$beload=$DB->query("select ogimage from ".prefix."_menupontok_".lang." where menuid='".$form_data["menuid"]."'");
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

			$elment=$DB->query("update ".prefix."_menupontok_".lang." set 
				oldalcim='".$form_data["oldalcim"]."',
				oldalcont='".$form_data["oldalcont"]."',
				furl='".$oldalfurl."',
				tomodul='".$form_data["tomodul"]."',
				metatitle='".$form_data["metatitle"]."',
				metadesc='".$form_data["metadesc"]."',
				".$ogimagechange."
				oldalaktiv='".$form_data["oldalaktiv"]."' 
			where menuid='".$form_data["menuid"]."'");
				
			$DB = NULL;
			$_SESSION["php_err_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=menukezelo");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
		}
	}
	function menu_hozzaad()
	{
		$DB = NULL;
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új menüpont hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=weboldal&thing=menukezelo&opt=ujmentes" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label for="menupontcim">Menüpont címe, neve</label>
								<input type="number" name="menupontcim" id="menupontcim" class="form-control" required />
							</div>
							<div class="form-group">
								<label for="pozicio">Pozíció sorszám</label>
								<input type="number" name="pozicio" id="pozicio" class="form-control" required />
							</div>
							<div class="form-group">
								<label for="tartalomid">Tartalom hozzárendelése</label>
								<select name="tartalomid" id="tartalomid" class="form-control" required>
									<option value="">Kérlek válassz, vagy adj meg egyedi hivatkozást!</option>';
								$DB = connect(true);
								$beload=$DB->query("SELECT * FROM ".prefix."_oldalak_".lang." where oldalaktiv='1'");
								while($b=$beload->fetch())
								{
									echo '<option value="'.$b["oldalid"].'">'.$b["oldalcim"].'</option>';
								}
			echo '				</select>
							</div>
							<div class="form-group">
								<label for="menuaktiv">Menüpont be van kapcsolva?</label>
								<select name="menuaktiv" id="menuaktiv" class="form-control">
									<option value="1" selected>IGEN</option>
									<option value="0">NEM</option>
								</select>
							</div>
							<div class="form-group">
								<label for="megnyitas">Menüpont külön ablakban nyiljon meg?</label>
								<select name="megnyitas" id="megnyitas" class="form-control">
									<option value="1">IGEN</option>
									<option value="0" selected>NEM</option>
								</select>
							</div>
							<div class="form-group">
								<label for="almenupontok">Almenüpont hozzárendelése</label>
								<select name="almenupontok[]" id="almenupontok" class="form-control" multiple>';
								$DB = connect(true);
								$beload=$DB->query("SELECT * FROM ".prefix."_oldalak_".lang." where oldalaktiv='1'");
								while($b=$beload->fetch())
								{
									echo '<option value="'.$b["oldalid"].'">'.$b["oldalcim"].'</option>';
								}
			echo '				</select>
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
								<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=menukezelo"><span class="mdi mdi-arrow-left"></span> Vissza</a>
							</div>
						</form>
					</div>
				</div>';
	}
	function menu_szerkeszt()
	{
		$DB = NULL;
		try
		{
			echo '<script type="text/javascript" src="'.adminurl.'assets/kekcms/ckeditor/ckeditor.js"></script>
					<script src="'.adminurl.'assets/kekcms/ckeditor/adapters/jquery.js"></script>
					<script>
						CKEDITOR.env.isCompatible = true;
					</script>';

			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_menupontok_".lang." where menuid='".$_REQUEST["id"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott oldal és tartalom az azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Új oldal hozzáadása </h2>
						<div class="card">
							<div class="card-body">
								<form action="'.adminurl.'index.php?action=weboldal&thing=menukezelo&opt=szerkesztmentes" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="menuid" value="'.$_REQUEST["id"].'">
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
											$modulok=$DB->query("select * from ".prefix."_modul_".lang." where aktiv='1'");
											while($m=$modulok->fetch())
											{
												echo '<option value="'.$m["modulid"].'" '.($res["tomodul"]==$m["modulid"] ? 'selected' : '').'>'.$m["modulnev"].'</option>';
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
										<label for="oldalcont">Oldal tartalma</label>
										<textarea name="oldalcont" id="oldalcont" class="form-control" rows="15" placeholder="Szerkeszd meg a taratlmad, amit csak szeretnél..." required>'.$res["oldalcont"].'</textarea>
									</div>
									<div>
										<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
										<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=menukezelo"><span class="mdi mdi-arrow-left"></span> Vissza</a>
									</div>
								</form>
							</div>
						</div>';
			}
		}
		catch(Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			menu_lista();
		}
	}
	function menu_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			?>
			<div class="card">
				<div class="card-body">
					<h2>Menüpontok listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=weboldal&thing=menukezelo&opt=ujmenupont" class="btn btn-primary">+ Új hozzáadása</a>

					<div class="mt-3">
						<h2>Jelenlegi menüpontok</h2>
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen létrehozott menüpont sem!</p>';
						}
						else
						{
			?>
							<div class="row mx-0 fw-bolder mb-2">
								<div class="col-lg-4">Menüpont címe</div>
								<div class="col-lg-4">Bekapcsolva?</div>
								<div class="col-lg-4">Műveletek</div>
							</div>
			<?php
							$sth = $DB->query("SELECT * FROM ".prefix."_menupontok_".lang."");
							while($row=$sth->fetch())
							{
								//főmenüpont listázása
								echo '<div class="row mx-0">';
									echo '<div class="col-lg-4">' . $row["menupontcim"] . '</div>';
									echo '<div class="col-lg-4">' . ($row["menuaktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=kikapcsol&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=bekapcsol&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</div>';
									echo '<div class="col-lg-4">';
										echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=szerkesztes&id=' . $row["menuid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
										echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=torles&id=' . $row["menuid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
									echo '</div>';
								echo '</div>';
								//ha van almenüpontja akkor azt listázzuk alatta
								if($row["almenupontok"]!="")
								{
									$amp=explode(",",$row["almenupontok"]);
									foreach($amp as $k => $v)
									{
										if($v!="")
										{
											$almenu=$DB->query("SELECT * FROM ".prefix."_menupontok_".lang." where menuid='".$v."'");
											echo '<div class="row mx-0">';
												echo '<div class="col-lg-4"><span class="mdi mdi-arrow-right-bottom"></span> ' . $row["menupontcim"] . '</div>';
												echo '<div class="col-lg-4">' . ($row["menuaktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=kikapcsol&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=bekapcsol&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</div>';
												echo '<div class="col-lg-4">';
													echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=szerkesztes&id=' . $row["menuid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
													echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=torles&id=' . $row["menuid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
												echo '</div>';
											echo '</div>';
										}
									}
								}
								//majd haladunk tovább a következő főmenüponttal...
							}
						}
			?>
					</div>
				</div>
			</div>
			<?php 
			$DB = NULL;
		}
		catch(Exception $e)
		{
			$DB = NULL;
			echo '<p class="text-danger">' . $e->getMessage() . '</p>';
		}
	}
?>

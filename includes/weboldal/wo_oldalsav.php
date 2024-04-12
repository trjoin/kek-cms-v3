<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujelem")
			oldalsav_hozzaad();
		elseif ($_REQUEST["opt"] == "ujmentes")
			oldalsav_uj_mentes();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			oldalsav_bekapcsol();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			oldalsav_kikapcsol();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			oldalsav_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			oldalsav_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			oldalsav_torles();
		else
			oldalsav_lista();
	}
	else
	{
		oldalsav_lista();
	}
}
else
{
    return false;
}

	function oldalsav_bekapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$bekapcsol=$DB->query("update ".prefix."_oldalsav_".$_SESSION["lang"]." set aktiv='1' where elemid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalsav");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalsav_lista();
		}
	}
	function oldalsav_kikapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$kikapcsol=$DB->query("update ".prefix."_oldalsav_".$_SESSION["lang"]." set aktiv='0' where elemid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalsav");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalsav_lista();
		}
	}
	function oldalsav_torles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés törlése
			$torles=$DB->query("delete from ".prefix."_oldalsav_".$_SESSION["lang"]." where elemid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalsav");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalsav_lista();
		}
	}
	function oldalsav_uj_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["elemnev"]) || empty($form_data["elemnev"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			
			$DB = connect(true);
			$elment=$DB->query("insert into ".prefix."_oldalsav_".$_SESSION["lang"]." (elemnev,elemcont,pozicio,aktiv) values ('".$form_data["elemnev"]."','".$form_data["elemcont"]."','".$form_data["pozicio"]."','".$form_data["aktiv"]."')");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalsav");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalsav_lista();
		}
	}
	function oldalsav_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["elemnev"]) || empty($form_data["elemnev"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			$DB = connect(true);

			$elment=$DB->query("update ".prefix."_oldalsav_".$_SESSION["lang"]." set 
				elemnev='".$form_data["elemnev"]."',
				elemcont='".$form_data["elemcont"]."',
				pozicio='".$form_data["pozicio"]."',
				aktiv='".$form_data["aktiv"]."' 
			where elemid='".$form_data["elemid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=oldalsav");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			oldalsav_lista();
		}
	}
	function oldalsav_hozzaad()
	{
		echo '<script src="'.adminurl.'assets/kekcms/ckeditor/adapters/jquery.js"></script>
				<script type="text/javascript" src="'.adminurl.'assets/kekcms/ckeditor/ckeditor.js"></script>
				<script>
					CKEDITOR.env.isCompatible = true;
				</script>';
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új oldalsáv elem hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=weboldal&thing=oldalsav&opt=ujmentes" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label for="elemnev">Új oldalsáv elem címe</label>
								<input type="text" name="elemnev" id="elemnev" class="form-control" placeholder="Adja meg az új oldal nevét, címét" maxlength="250" required />
							</div>
							<div class="form-group">
								<label for="pozicio">Pozíció</label>
								<input type="number" name="pozicio" id="pozicio" class="form-control" required />
							</div>
							<div class="form-group">
								<label for="aktiv">Oldalsáv elem be van kapcsolva?</label>
								<select name="aktiv" id="aktiv" class="form-control">
									<option value="1" selected>IGEN</option>
									<option value="0">NEM</option>
								</select>
							</div>
							<div class="form-group">
								<label for="elemcont">Oldalsáv elem tartalma</label>
								<textarea name="elemcont" id="elemcont" class="form-control" rows="15" placeholder="Szerkeszd meg a taratlmad, amit csak szeretnél..." required></textarea>
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
								<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=oldalsav"><span class="mdi mdi-arrow-left"></span> Vissza</a>
							</div>
						</form>
					</div>
				</div>';
		?>
			<script>
				CKEDITOR.replace( 'elemcont', {
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
	function oldalsav_szerkeszt()
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
			$sth = $DB->query("SELECT * FROM ".prefix."_oldalsav_".$_SESSION["lang"]." where elemid='".$_REQUEST["id"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott oldalsáv elem az azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Oldalsáv elem szerkesztése </h2>
						<div class="card">
							<div class="card-body">
								<form action="'.adminurl.'index.php?action=weboldal&thing=oldalsav&opt=szerkesztmentes" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="elemid" value="'.$_REQUEST["id"].'">
									<div class="form-group">
										<label for="elemnev">Oldalsáv elem címe</label>
										<input type="text" name="elemnev" id="elemnev" class="form-control" value="'.$res["elemnev"].'" placeholder="Adja meg az új oldal nevét, címét" maxlength="250" required />
									</div>
									<div class="form-group">
										<label for="pozicio">Pozíció</label>
										<input type="number" name="pozicio" id="pozicio" class="form-control" value="'.$res["pozicio"].'" required />
									</div>
									<div class="form-group">
										<label for="aktiv">Oldalsáv elem be van kapcsolva?</label>
										<select name="aktiv" id="aktiv" class="form-control">
											<option value="1" '.($res["aktiv"]=='1' ? 'selected' : '').'>IGEN</option>
											<option value="0" '.($res["aktiv"]=='0' ? 'selected' : '').'>NEM</option>
										</select>
									</div>
									<div class="form-group">
										<label for="elemcont">Oldalsáv elem tartalma</label>
										<textarea name="elemcont" id="elemcont" class="form-control" rows="15" placeholder="Szerkeszd meg a taratlmad, amit csak szeretnél..." required>'.$res["elemcont"].'</textarea>
									</div>
									<div>
										<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
										<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=oldalsav"><span class="mdi mdi-arrow-left"></span> Vissza</a>
									</div>
								</form>
							</div>
						</div>';
				?>
					<script>
						CKEDITOR.replace( 'elemcont', {
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
			oldalsav_lista();
		}
	}
	function oldalsav_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_oldalsav_".$_SESSION["lang"]."");
			$res = $sth->fetchAll();
			$DB = NULL;

			?>
			<div class="card">
				<div class="card-body">
					<h2>Oldalsáv (widget) elemek listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=weboldal&thing=oldalsav&opt=ujelem" class="btn btn-primary btn-sm">+ Új hozzáadása</a>

					<div class="mt-3">
						<h2>Jelenlegi elemek</h2>
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen létrehozott oldalsáv elem sem!</p>';
						}
						else
						{
			?>
							<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Elem címe</big></strong></th>
								  <th scope="col"><strong><big>Bekapcsolva?</big></strong></th>
								  <th scope="col"><strong><big>Műveletek</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
			<?php 
							$out = "";
							foreach($res as $row){ 
								$out .= '<tr>';
									$out .= '<td>' . $row["pozicio"] . '. ' . $row["elemnev"] . '</td>';
									$out .= '<td>' . ($row["aktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalsav&opt=kikapcsol&id=' . $row["elemid"] . '" class="btn btn-outline-warning px-1 py-0"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalsav&opt=bekapcsol&id=' . $row["elemid"] . '" class="btn btn-outline-warning px-1 py-0"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</td>';
									$out .= '<td>';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalsav&opt=szerkesztes&id=' . $row["elemid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=oldalsav&opt=torles&id=' . $row["elemid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
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

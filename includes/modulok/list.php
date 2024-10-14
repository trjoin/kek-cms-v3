<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujmodul")
			modul_hozzaad();
		elseif ($_REQUEST["opt"] == "ujmodulmentes")
			modul_uj_mentes();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			modul_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			modul_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			modul_torles();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			modul_kikapcsol();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			modul_bekapcsol();
		else
			modul_lista();
	}
	else
	{
		modul_lista();
	}
}
else
{
    return false;
}

	function modul_torles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//betöltjük ellenőrzésre, mert ha egyedi modult töröl, akkor a fájlt is törölni kell!
			$beload=$DB->query("select * from ".prefix."_modul_".$_SESSION["lang"]." where modulid='".$_REQUEST["modulid"]."'");
			$be=$beload->fetch();
			if($be["modulcont"]!="")
			{
				if(file_exists("../frontinc/".$be["modulcont"].""))
				{
					unlink("../frontinc/".$be["modulcont"]."");
				}
			}
			//adatbázis bejegyzés törlése
			$torles=$DB->query("delete from ".prefix."_modul_".$_SESSION["lang"]." where modulid='".$_REQUEST["modulid"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=modules&thing=list");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function modul_kikapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés frissitése
			$kikapcsol=$DB->query("update ".prefix."_modul_".$_SESSION["lang"]." set aktiv='0' where modulid='".$_REQUEST["modulid"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=modules&thing=list");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function modul_bekapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés frissitése
			$bekapcsol=$DB->query("update ".prefix."_modul_".$_SESSION["lang"]." set aktiv='1' where modulid='".$_REQUEST["modulid"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=modules&thing=list");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function modul_uj_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["modulnev"]) || empty($form_data["modulnev"]))
				throw new Exception("A modul név kitöltése kötelező!");
			
			//modul elnevezése és a fájl létrehozása
			if($form_data["modulcont"]!="")
			{
				$modulnev=cserekari($form_data["modulnev"]).".trj";
				//modul tartalma elhelyezése fájlban
				if(file_exists("../frontinc/".$modulnev.""))
				{
					unlink("../frontinc/".$modulnev."");
				}
				touch("../frontinc/".$modulnev."");
				$fm=fopen("../frontinc/".$modulnev."","a");
				if($form_data["enkoded"]=="1")
				{
					$tartalom=base64_encode($form_data["modulcont"]);
				}
				else
				{
					$tartalom=$form_data["modulcont"];
				}
				fwrite($fm,$tartalom);
			}
			else
			{
				$modulnev="";
			}
			
			$DB = connect(true);
			$elment=$DB->query("insert into ".prefix."_modul_".$_SESSION["lang"]." (modulnev,modulcont,integ,aktiv,enkoded) values ('".$form_data["modulnev"]."','".$modulnev."','".$form_data["integ"]."','".$form_data["aktiv"]."','".$form_data["enkoded"]."')");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=modules&thing=list");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function modul_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["modulnev"]) || empty($form_data["modulnev"]))
				throw new Exception("A felhasználónév kitöltése kötelező!");
			
			$DB = connect(true);
			if($form_data["modulcont"]!="")
			{
				//leellenőrizzük, hogy cak a nevét módosította-e az egyedi modulnak, mer tha igen akkor a régi modulfájlt kitöröljük a picsába!
				$beload=$DB->query("select * from ".prefix."_modul_".$_SESSION["lang"]." where modulid='".$form_data["modulid"]."'");
				$be=$beload->fetch();
				if($be["modulnev"]!=$form_data["modulnev"])
				{
					$oldmodul=cserekari($be["modulnev"]).".trj";
					if(file_exists("../frontinc/".$oldmodul.""))
					{
						unlink("../frontinc/".$oldmodul."");
					}
				}
				//modul biztonságos elnevezése
				$modulnev=cserekari($form_data["modulnev"]).".trj";
				//modul tartalma elhelyezése fájlban
				if(file_exists("../frontinc/".$modulnev.""))
				{
					unlink("../frontinc/".$modulnev."");
				}
				touch("../frontinc/".$modulnev."");
				$fm=fopen("../frontinc/".$modulnev."","a");
				if($form_data["enkoded"]=="1")
				{
					$tartalom=base64_encode($form_data["modulcont"]);
				}
				else
				{
					$tartalom=$form_data["modulcont"];
				}
				fwrite($fm,$tartalom);
				
				//adatbázisba nem enkódolt tartalmat irunk, mivel nincs is modulcont!
				$enkoded="enkoded='".$form_data["enkoded"]."',";
			}
			else
			{
				$modulnev="";
				$enkoded="enkoded='0',";
			}
			
			$elment=$DB->query("update ".prefix."_modul_".$_SESSION["lang"]." set 
				modulnev='".$form_data["modulnev"]."',
				modulcont='".$modulnev."',
				integ='".$form_data["integ"]."',
				".$enkoded."
				aktiv='".$form_data["aktiv"]."' 
			where modulid='".$form_data["modulid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=modules&thing=list");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function modul_hozzaad()
	{
		echo '<h2 class="text-muted font-weight-bold mb-2"> Modul hozzáadása </h2>
					<div class="card">
						<div class="card-body">
							<form action="'.adminurl.'index.php?action=modules&thing=list&opt=ujmodulmentes" method="POST">
								<div class="form-group">
									<label for="modulnev">Modul neve</label>
									<input type="text" name="modulnev" id="modulnev" class="form-control" placeholder="Adja meg a modul nevét" required />
								</div>
								<div class="form-group">
									<label for="aktiv">Modul aktív?</label>
									<select name="aktiv" id="aktiv" class="form-control" required />
										<option value="0">NEM</option>
										<option value="1" selected>IGEN</option>
									</select>
								</div>
								<div class="form-group">
									<label for="integ">Modul integrálható?</label>
									<select name="integ" id="integ" class="form-control" required />
										<option value="0">NEM</option>
										<option value="1" selected>IGEN</option>
									</select>
								</div>';
							//ha ROOT van bejelentkezve, akkor adunk neki enkódolási lehetőséget!
							if($_SESSION["jogkor"]=="3")
							{
								echo '<div class="form-group">
										<label for="integ">Modul dekódolt?</label>
										<select name="enkoded" id="enkoded" class="form-control" />
											<option value="1">IGEN</option>
											<option value="0">NEM</option>
										</select>
									</div>';
							}
							else
							{
								echo '<input type="hidden" name="enkoded" id="enkoded" value="1">';
							}
							echo '<div class="form-group">
									<label for="modulcont">Modul tartalma</label>
									<textarea name="modulcont" id="modulcont" class="form-control" placeholder="Adja meg a modul programozott tartalmát" rows="20" /></textarea>
								</div>
								<div>
									<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
									<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=modules&thing=list"><span class="mdi mdi-arrow-left"></span> Vissza</a>
								</div>
							</form>
						</div>
					</div>';
	}
	function modul_szerkeszt()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_modul_".$_SESSION["lang"]." where modulid='".$_REQUEST["modulid"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott modul azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Modul szerkesztése </h2>
					<div class="card">
						<div class="card-body">
							<form action="'.adminurl.'index.php?action=modules&thing=list&opt=szerkesztmentes" method="POST">
								<input type="hidden" name="modulid" value="'.$res["modulid"].'">
								<div class="form-group">
									<label for="modulnev">Modul neve</label>
									<input type="text" name="modulnev" id="modulnev" class="form-control" placeholder="Adja meg a modul nevét" value="'.$res["modulnev"].'" required />
								</div>
								<div class="form-group">
									<label for="aktiv">Modul aktív?</label>
									<select name="aktiv" id="aktiv" class="form-control" required />
										<option value="0" '.($res["aktiv"]=="0" ? 'selected' : '').'>NEM</option>
										<option value="1" '.($res["aktiv"]=="1" ? 'selected' : '').'>IGEN</option>
									</select>
								</div>
								<div class="form-group">
									<label for="integ">Modul integrálható?</label>
									<select name="integ" id="integ" class="form-control" required />
										<option value="0" '.($res["integ"]=="0" ? 'selected' : '').'>NEM</option>
										<option value="1" '.($res["integ"]=="1" ? 'selected' : '').'>IGEN</option>
									</select>
								</div>';
								//ha ROOT van bejelentkezve, akkor adunk neki enkódolási lehetőséget!
								if($_SESSION["jogkor"]=="3")
								{
									echo '<div class="form-group">
											<label for="integ">Modul dekódolt?</label>
											<select name="enkoded" id="enkoded" class="form-control" />
												<option value="0" '.($res["enkoded"]=="0" ? 'selected' : '').'>NEM</option>
												<option value="1" '.($res["enkoded"]=="1" ? 'selected' : '').'>IGEN</option>
											</select>
										</div>';
								}
								else
								{
									echo '<input type="hidden" name="enkoded" id="enkoded" value="1">';
								}
								
								if($res["modulcont"]!="")
								{
									if(file_exists("../frontinc/".$res["modulcont"].""))
									{
										$modulcontent = fopen("../frontinc/".$res["modulcont"]."", "r") or die("Unable to open file!");
										$ezkell=fread($modulcontent,filesize("../frontinc/".$res["modulcont"].""));
										fclose($modulcontent);
										$modultartalom=$ezkell;
										if($res["enkoded"]=="1")
										{
											$modultartalom=base64_decode($modultartalom);
										}
									}
									else
									{
										$modultartalom="";
									}
									echo '<div class="form-group">
											<label for="modulcont">Modul tartalma</label>
											<textarea name="modulcont" id="modulcont" class="form-control" placeholder="Adja meg a modul programozott tartalmát" rows="20" />'.$modultartalom.'</textarea>
										</div>';
									if($res["enkoded"]=="1")
									{
										echo $modultartalom;
									}
									else
									{
										include("../frontinc/".$res["modulcont"]."");
									}
								}
								else
								{
									echo '<input type="hidden" name="modulcont" id="modulcont" value="">';
								}
							echo '<div>
									<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
									<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=modules&thing=list"><span class="mdi mdi-arrow-left"></span> Vissza</a>
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
			modul_lista();
		}
	}
	function modul_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_modul_".$_SESSION["lang"]."");
			$res = $sth->fetchAll();
			$DB = NULL;

			?>
			<div class="card">
				<div class="card-body">
					<h2>Modulok listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=modules&thing=list&opt=ujmodul" class="btn btn-primary btn-sm">+ Új hozzáadása</a>

					<div class="mt-3">
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen modul sem!</p>';
						}
						else
						{
			?>
							<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Modul neve</big></strong></th>
								  <th scope="col"><strong><big>Aktív?</big></strong></th>
								  <th scope="col"><strong><big>Integrálható?</big></strong></th>
								  <th scope="col"><strong><big>Egyedi?</big></strong></th>
								  <th scope="col"><strong><big>Műveletek</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
			<?php 
							$out = "";
							foreach($res as $row){ 
								$out .= '<tr>';
									$out .= '<td>' . $row["modulnev"] . '</td>';
									$out .= '<td>' . ($row["aktiv"]!="0" ? "IGEN" : "NEM") . '</td>';
									$out .= '<td>' . ($row["integ"]!="0" ? "IGEN" : "NEM") . '</td>';
									$out .= '<td>' . ($row["modulcont"]!="" ? "IGEN" : "NEM") . '</td>';
									$out .= '<td>';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=modules&thing=list&opt=szerkesztes&modulid=' . $row["modulid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
										if($row["aktiv"]!="0")
										{
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=modules&thing=list&opt=kikapcsol&modulid=' . $row["modulid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> Kikapcsolás</a> ';
										}
										else
										{
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=modules&thing=list&opt=bekapcsol&modulid=' . $row["modulid"] . '" class="btn btn-outline-success px-2 py-1"><span class="mdi mdi-power-cycle"></span> Bekapcsolás</a> ';
										}
										if($row["modulcont"]!="")
										{
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=modules&thing=list&opt=torles&modulid=' . $row["modulid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a> ';
										}
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

<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujstat")
			marketing_hozzaad();
		elseif ($_REQUEST["opt"] == "ujstatmentes")
			marketing_uj_mentes();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			marketing_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			marketing_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			marketing_torles();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			marketing_kikapcsol();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			marketing_bekapcsol();
		else
			marketing_lista();
	}
	else
	{
		marketing_lista();
	}
}
else
{
    return false;
}

	function marketing_torles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés törlése
			$torles=$DB->query("delete from ".prefix."_marketing_".$_SESSION["lang"]." where marketid='".$_REQUEST["marketid"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=stat&thing=marketing");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function marketing_kikapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés frissitése
			$kikapcsol=$DB->query("update ".prefix."_marketing_".$_SESSION["lang"]." set aktiv='0' where marketid='".$_REQUEST["marketid"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=stat&thing=marketing");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function marketing_bekapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés frissitése
			$bekapcsol=$DB->query("update ".prefix."_marketing_".$_SESSION["lang"]." set aktiv='1' where marketid='".$_REQUEST["marketid"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=stat&thing=marketing");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function marketing_uj_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["marketnev"]) || empty($form_data["marketnev"]))
				throw new Exception("A marketing script név kitöltése kötelező!");
			
			$DB = connect(true);
			$elment=$DB->query("insert into ".prefix."_marketing_".$_SESSION["lang"]." (marketnev,headba,bodyba,footerbe,aktiv) values ('".$form_data["marketnev"]."','".$form_data["headba"]."','".$form_data["bodyba"]."','".$form_data["footerbe"]."','".$form_data["aktiv"]."')");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=stat&thing=marketing");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function marketing_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["marketnev"]) || empty($form_data["marketnev"]))
				throw new Exception("A marketing script név kitöltése kötelező!");
			
			$DB = connect(true);
			$elment=$DB->query("update ".prefix."_marketing_".$_SESSION["lang"]." set 
				marketnev='".$form_data["marketnev"]."',
				headba='".$form_data["headba"]."',
				bodyba='".$form_data["bodyba"]."',
				footerbe='".$form_data["footerbe"]."',
				aktiv='".$form_data["aktiv"]."' 
			where marketid='".$form_data["marketid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=stat&thing=marketing");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			modul_lista();
		}
	}
	function marketing_hozzaad()
	{
		echo '<h2 class="text-muted font-weight-bold mb-2"> Marketing script hozzáadása </h2>
					<div class="card">
						<div class="card-body">
							<form action="'.adminurl.'index.php?action=stat&thing=marketing&opt=ujstatmentes" method="POST">
								<div class="form-group">
									<label for="marketnev">Script neve</label>
									<input type="text" name="marketnev" id="marketnev" class="form-control" placeholder="Adja meg a script nevét, pl Analytics 4" required />
								</div>
								<div class="form-group">
									<label for="aktiv">Script aktív?</label>
									<select name="aktiv" id="aktiv" class="form-control" required />
										<option value="0">NEM</option>
										<option value="1" selected>IGEN</option>
									</select>
								</div>
								<div class="form-group">
									<label for="headba">Script HEAD tartalma</label>
									<textarea name="headba" id="headba" class="form-control" placeholder="Adja meg a script HEAD részbe illesztendő tartalmát" rows="20" /></textarea>
								</div>
								<div class="form-group">
									<label for="bodyba">Script BODY tartalma</label>
									<textarea name="bodyba" id="bodyba" class="form-control" placeholder="Adja meg a script BODY részbe illesztendőt tartalmát" rows="20" /></textarea>
								</div>
								<div class="form-group">
									<label for="footerbe">Script FOOTER tartalma</label>
									<textarea name="footerbe" id="footerbe" class="form-control" placeholder="Adja meg a script FOOTER részbe illesztendő tartalmát" rows="20" /></textarea>
								</div>
								<div>
									<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
									<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=stat&thing=marketing"><span class="mdi mdi-arrow-left"></span> Vissza</a>
								</div>
							</form>
						</div>
					</div>';
	}
	function marketing_szerkeszt()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_marketing_".$_SESSION["lang"]." where marketid='".$_REQUEST["marketid"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Marketing script szerkesztése </h2>
					<div class="card">
						<div class="card-body">
							<form action="'.adminurl.'index.php?action=stat&thing=marketing&opt=szerkesztmentes" method="POST">
								<input type="hidden" name="marketid" id="marketid" value="'.$res["marketid"].'">
								<div class="form-group">
									<label for="marketnev">Script neve</label>
									<input type="text" name="marketnev" id="marketnev" class="form-control" placeholder="Adja meg a script nevét, pl Analytics 4" value="'.$res["marketnev"].'" required />
								</div>
								<div class="form-group">
									<label for="aktiv">Script aktív?</label>
									<select name="aktiv" id="aktiv" class="form-control" required />
										<option value="0" '.($res["aktiv"]=='0' ? 'selected' : '').'>NEM</option>
										<option value="1" '.($res["aktiv"]=='1' ? 'selected' : '').'>IGEN</option>
									</select>
								</div>
								<div class="form-group">
									<label for="headba">Script HEAD tartalma</label>
									<textarea name="headba" id="headba" class="form-control" placeholder="Adja meg a script HEAD részbe illesztendő tartalmát" rows="20" />'.$res["headba"].'</textarea>
								</div>
								<div class="form-group">
									<label for="bodyba">Script BODY tartalma</label>
									<textarea name="bodyba" id="bodyba" class="form-control" placeholder="Adja meg a script BODY részbe illesztendőt tartalmát" rows="20" />'.$res["bodyba"].'</textarea>
								</div>
								<div class="form-group">
									<label for="footerbe">Script FOOTER tartalma</label>
									<textarea name="footerbe" id="footerbe" class="form-control" placeholder="Adja meg a script FOOTER részbe illesztendő tartalmát" rows="20" />'.$res["footerbe"].'</textarea>
								</div>
								<div>
									<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
									<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=stat&thing=marketing"><span class="mdi mdi-arrow-left"></span> Vissza</a>
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
	function marketing_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_marketing_".$_SESSION["lang"]."");
			$res = $sth->fetchAll();
			$DB = NULL;

			?>
			<div class="card">
				<div class="card-body">
					<h2>Marketing scriptek listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=stat&thing=marketing&opt=ujstat" class="btn btn-primary btn-sm">+ Új hozzáadása</a>

					<div class="mt-3">
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen script sem!</p>';
						}
						else
						{
			?>
							<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Script neve</big></strong></th>
								  <th scope="col"><strong><big>Aktív?</big></strong></th>
								  <th scope="col"><strong><big>Műveletek</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
			<?php 
							$out = "";
							foreach($res as $row){ 
								$out .= '<tr>';
									$out .= '<td>' . $row["marketnev"] . '</td>';
									$out .= '<td>' . ($row["aktiv"]!="0" ? "IGEN" : "NEM") . '</td>';
									$out .= '<td>';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=stat&thing=marketing&opt=szerkesztes&marketid=' . $row["marketid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
										if($row["aktiv"]!="0")
										{
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=stat&thing=marketing&opt=kikapcsol&marketid=' . $row["marketid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> Kikapcsolás</a> ';
										}
										else
										{
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=stat&thing=marketing&opt=bekapcsol&marketid=' . $row["marketid"] . '" class="btn btn-outline-success px-2 py-1"><span class="mdi mdi-power-cycle"></span> Bekapcsolás</a> ';
										}
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=stat&thing=marketing&opt=torles&marketid=' . $row["marketid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a> ';
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

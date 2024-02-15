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

			if (!isset($form_data["tartalomid"]) || empty($form_data["tartalomid"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			if (!isset($form_data["menupontcim"]) || empty($form_data["menupontcim"]))
				throw new Exception("A meta title kitöltése kötelező!");
			if (!isset($form_data["pozicio"]) || empty($form_data["pozicio"]))
				throw new Exception("A meta leirás kitöltése kötelező!");
			
			$DB = connect(true);
			
			$elment=$DB->query("insert into ".prefix."_menupontok_".lang." (tartalomid,menupontcim,pozicio,megnyitas,menuaktiv) values ('".$form_data["tartalomid"]."','".$form_data["menupontcim"]."','".$form_data["pozicio"]."','".$form_data["megnyitas"]."','".$form_data["menuaktiv"]."')");
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
	function menu_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["tartalomid"]) || empty($form_data["tartalomid"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			if (!isset($form_data["menupontcim"]) || empty($form_data["menupontcim"]))
				throw new Exception("A meta title kitöltése kötelező!");
			if (!isset($form_data["pozicio"]) || empty($form_data["pozicio"]))
				throw new Exception("A meta leirás kitöltése kötelező!");
			
			$DB = connect(true);
			
			$almenupontok="";
			foreach($form_data["almenupontok"] as $k => $v)
			{
				if($v!="")
				{
					$almenupontok.=$v.",";
				}
			}

			$elment=$DB->query("update ".prefix."_menupontok_".lang." set 
				tartalomid='".$form_data["tartalomid"]."',
				menupontcim='".$form_data["menupontcim"]."',
				pozicio='".$form_data["pozicio"]."',
				almenupontok='".$almenupontok."',
				megnyitas='".$form_data["metadesc"]."',
				menuaktiv='".$form_data["oldalaktiv"]."' 
			where menuid='".$form_data["modid"]."'");
				
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
		$DB = connect(true);
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új menüpont hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=weboldal&thing=menukezelo&opt=ujmentes" method="POST">
							<div class="form-group">
								<label for="menupontcim">Menüpont címe, neve</label>
								<input type="text" name="menupontcim" id="menupontcim" class="form-control" required />
							</div>
							<div class="form-group">
								<label for="pozicio">Pozíció sorszám</label>
								<input type="number" name="pozicio" id="pozicio" class="form-control" required />
							</div>
							<div class="form-group">
								<label for="tartalomid">Tartalom hozzárendelése</label>
								<select name="tartalomid" id="tartalomid" class="form-control" required>
									<option value="">Kérlek válassz, vagy adj meg egyedi hivatkozást!</option>';
									$beload=$DB->query("SELECT * FROM ".prefix."_oldalak_".lang." where oldalaktiv='1'");
									if($beload->rowCount()>0)
									{
										while($b=$beload->fetch())
										{
											 echo '<option value="'.$b["oldalid"].'">'.$b["oldalcim"].'</option>';
										}
									}
									else
									{
										echo '<option value="" selected disabled>NINCS LÉTREHOZVA EGYETLEN OLDAL SEM</option>';
									}
					echo '		</select>
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
			$DB = connect(true);
			
			$menup=$DB->query("SELECT * FROM ".prefix."_menupontok_".lang." where menuid='".$_REQUEST["id"]."'");
			if($menup->rowCount()>0)
			{
				$m=$menup->fetch();
				echo '<h2 class="text-muted font-weight-bold mb-2"> Menüpont szerkesztése </h2>
						<div class="card">
							<div class="card-body">
								<form action="'.adminurl.'index.php?action=weboldal&thing=menukezelo&opt=szerkesztmentes" method="POST">
									<input type="hidden" name="modid" value="'.$_REQUEST["id"].'">
									<div class="form-group">
										<label for="menupontcim">Menüpont címe, neve</label>
										<input type="text" name="menupontcim" id="menupontcim" value="'.$m["menupontcim"].'" class="form-control" required />
									</div>
									<div class="form-group">
										<label for="pozicio">Pozíció sorszám</label>
										<input type="number" name="pozicio" id="pozicio" class="form-control" value="'.$m["pozicio"].'" required />
									</div>
									<div class="form-group">
										<label for="tartalomid">Tartalom hozzárendelése</label>
										<select name="tartalomid" id="tartalomid" class="form-control" required>';
											$beload=$DB->query("SELECT * FROM ".prefix."_oldalak_".lang." where oldalaktiv='1'");
											while($b=$beload->fetch())
											{
												echo '<option value="'.$b["oldalid"].'" '.($m["tartalomid"]==$b["oldalid"] ? 'selected' : '').'>'.$b["oldalcim"].'</option>';
											}
								echo '	</select>
									</div>
									<div class="form-group">
										<label for="menuaktiv">Menüpont be van kapcsolva?</label>
										<select name="menuaktiv" id="menuaktiv" class="form-control">
											<option value="1" '.($m["menuaktiv"]=='1' ? 'selected' : '').'>IGEN</option>
											<option value="0" '.($m["menuaktiv"]=='0' ? 'selected' : '').'>NEM</option>
										</select>
									</div>
									<div class="form-group">
										<label for="megnyitas">Menüpont külön ablakban nyiljon meg?</label>
										<select name="megnyitas" id="megnyitas" class="form-control">
											<option value="1" '.($m["megnyitas"]=='1' ? 'selected' : '').'>IGEN</option>
											<option value="0" '.($m["megnyitas"]=='1' ? 'selected' : '').'>NEM</option>
										</select>
									</div>
									<div class="form-group">
										<label for="almenupontok">Almenüpont(ok) hozzárendelése</label>
										<select name="almenupontok[]" id="almenupontok" class="form-control" multiple>
											<option value="">Válassz akár több almenüpontot is, vagy kattints ide, ha nem kell almenüpont!</option>';
											if($m["almenupontok"]!="")
											{
												$amp=explode(",",$m["almenupontok"]);
											}
											else
											{
												$amp=array();
											}
											
											$almenu=$DB->query("SELECT * FROM ".prefix."_menupontok_".lang." where menuid!='".$_REQUEST["id"]."'");
											while($a=$almenu->fetch())
											{
												 echo '<option value="'.$a["menuid"].'" '.(in_array($a["menuid"], $amp) ? 'selected' : '').'>'.$a["menupontcim"].'</option>';
											}
								echo '	</select>
									</div>
									<div>
										<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
										<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=menukezelo"><span class="mdi mdi-arrow-left"></span> Vissza</a>
									</div>
								</form>
							</div>
						</div>';
			}
			else
			{
				$DB = NULL;
				$_SESSION["php_err_notification"] = "NIncs ilyen azonositoval menüpont";
				menu_lista();
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
							<div class="row mx-0 fw-bolder mb-2">
								<div class="col-lg-4">Menüpont címe</div>
								<div class="col-lg-4">Bekapcsolva?</div>
								<div class="col-lg-4">Műveletek</div>
							</div>
			<?php
							$sth = $DB->query("SELECT * FROM ".prefix."_menupontok_".lang."");
							if($sth->rowCount()>0)
							{
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
											if($v!="" AND $v!=0)
											{
												$almenu=$DB->query("SELECT * FROM ".prefix."_menupontok_".lang." where menuid='".$v."'");
												$al=$almenu->fetch();
												echo '<div class="row mx-0">';
													echo '<div class="col-lg-4"><span class="mdi mdi-arrow-bottom-right"></span> ' . $al["menupontcim"] . '</div>';
													echo '<div class="col-lg-4">' . ($al["menuaktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=kikapcsol&id=' . $al["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=bekapcsol&id=' . $al["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</div>';
													echo '<div class="col-lg-4">';
														echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=szerkesztes&id=' . $al["menuid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
														echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=torles&id=' . $al["menuid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
													echo '</div>';
												echo '</div>';
											}
										}
									}
									//majd haladunk tovább a következő főmenüponttal...
								}
							}
							else
							{
								echo '<p class="text-danger">Jelenleg nem található egyetlen létrehozott menüpont sem!</p>';
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

<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujmenupont")
			menu_hozzaad();
		elseif ($_REQUEST["opt"] == "ujalmenupont")
			menu_alhozzaad();
		elseif ($_REQUEST["opt"] == "ujmentes")
			menu_uj_mentes();
		elseif ($_REQUEST["opt"] == "ujalmentes")
			menu_uj_almentes();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			menu_bekapcsol();
		elseif ($_REQUEST["opt"] == "albekapcsol")
			menu_albekapcsol();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			menu_kikapcsol();
		elseif ($_REQUEST["opt"] == "alkikapcsol")
			menu_alkikapcsol();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			menu_szerkeszt();
		elseif ($_REQUEST["opt"] == "alszerkesztes")
			menu_alszerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			menu_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "alszerkesztmentes")
			menu_alszerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			menu_torles();
		elseif ($_REQUEST["opt"] == "altorles")
			menu_altorles();
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
	function menu_albekapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$bekapcsol=$DB->query("update ".prefix."_almenupontok_".lang." set menuaktiv='1' where menuid='".$_REQUEST["id"]."'");
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
	function menu_alkikapcsol()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$kikapcsol=$DB->query("update ".prefix."_almenupontok_".lang." set menuaktiv='0' where menuid='".$_REQUEST["id"]."'");
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
	function menu_altorles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			
				$fobe=$DB->query("select * from ".prefix."_menupontok_".lang." where almenupontok like '%".$_REQUEST["id"].",%'");
				$f=$fobe->fetch();
				$alok=explode(",",$f["almenupontok"]);
				if (($key = array_search($_REQUEST["id"], $alok)) !== false) {
					unset($alok[$key]);
				}
				$alok=implode(",",$alok);
				$fotorol=$DB->query("update ".prefix."_menupontok_".lang." set almenupontok='".$alok."' where menuid='".$f["menuid"]."'");

			$torles=$DB->query("delete from ".prefix."_almenupontok_".lang." where menuid='".$_REQUEST["id"]."'");
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
	function menu_uj_almentes()
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
			$elment=$DB->query("insert into ".prefix."_almenupontok_".lang." (tartalomid,menupontcim,pozicio,szulo,megnyitas,menuaktiv) values ('".$form_data["tartalomid"]."','".$form_data["menupontcim"]."','".$form_data["pozicio"]."','".$form_data["szulo"]."','".$form_data["megnyitas"]."','".$form_data["menuaktiv"]."')");
			$stmt = $DB->query("SELECT LAST_INSERT_ID()");
			$lastId = $stmt->fetchColumn();
			$fomenusave=$DB->query("update ".prefix."_menupontok_".lang." set almenupontok=CONCAT(almenupontok,',".$lastId."') where menuid='".$form_data["szulo"]."'");
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

			$elment=$DB->query("update ".prefix."_menupontok_".lang." set 
				tartalomid='".$form_data["tartalomid"]."',
				menupontcim='".$form_data["menupontcim"]."',
				pozicio='".$form_data["pozicio"]."',
				megnyitas='".$form_data["metadesc"]."',
				menuaktiv='".$form_data["menuaktiv"]."' 
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
	function menu_alszerkeszt_mentes()
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

			$elment=$DB->query("update ".prefix."_almenupontok_".lang." set 
				tartalomid='".$form_data["tartalomid"]."',
				menupontcim='".$form_data["menupontcim"]."',
				pozicio='".$form_data["pozicio"]."',
				megnyitas='".$form_data["metadesc"]."',
				menuaktiv='".$form_data["menuaktiv"]."' 
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
	function menu_alhozzaad()
	{
		$DB = NULL;
		$DB = connect(true);
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új almenüpont hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=weboldal&thing=menukezelo&opt=ujalmentes" method="POST">
							<input type="hidden" name="szulo" value="'.$_REQUEST["id"].'">
							<div class="form-group">
								<label for="menupontcim">Almenüpont címe, neve</label>
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
								<label for="menuaktiv">Almenüpont be van kapcsolva?</label>
								<select name="menuaktiv" id="menuaktiv" class="form-control">
									<option value="1" selected>IGEN</option>
									<option value="0">NEM</option>
								</select>
							</div>
							<div class="form-group">
								<label for="megnyitas">Almenüpont külön ablakban nyiljon meg?</label>
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
											<option value="0" '.($m["megnyitas"]=='0' ? 'selected' : '').'>NEM</option>
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
	function menu_alszerkeszt()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			
			$menup=$DB->query("SELECT * FROM ".prefix."_almenupontok_".lang." where menuid='".$_REQUEST["id"]."'");
			if($menup->rowCount()>0)
			{
				$m=$menup->fetch();
				echo '<h2 class="text-muted font-weight-bold mb-2"> Menüpont szerkesztése </h2>
						<div class="card">
							<div class="card-body">
								<form action="'.adminurl.'index.php?action=weboldal&thing=menukezelo&opt=alszerkesztmentes" method="POST">
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
											<option value="0" '.($m["megnyitas"]=='0' ? 'selected' : '').'>NEM</option>
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
							$stha = $DB->query("SELECT * FROM ".prefix."_menupontok_".lang." order by pozicio asc");
							if($stha->rowCount()>0)
							{
								while($row=$stha->fetch())
								{
									echo '<div class="row mx-0 py-1">';
										echo '<div class="col-lg-4">' . $row["menupontcim"] . '</div>';
										echo '<div class="col-lg-4">' . ($row["menuaktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=kikapcsol&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=bekapcsol&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</div>';
										echo '<div class="col-lg-4 col-xxl-3 d-flex justify-content-between">';
											echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=szerkesztes&id=' . $row["menuid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
											echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=ujalmenupont&id=' . $row["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-plus"></span> Új almenüpont</a> ';
											echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=torles&id=' . $row["menuid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
										echo '</div>';
									echo '</div>';
									
									if($row["almenupontok"]!="")
									{
										$res = $DB->query("SELECT * FROM ".prefix."_almenupontok_".lang." WHERE menuid IN (".ltrim($row["almenupontok"], ",").") ORDER BY pozicio");
										$almenuk = $res->fetchAll();
										
										foreach($almenuk as $almenu)
										{
											echo '<div class="row mx-0">';
											echo '<div class="col-lg-4"><span class="mdi mdi-arrow-bottom-right"></span> ' . $almenu["menupontcim"] . '</div>';
											echo '<div class="col-lg-4">' . ($almenu["menuaktiv"]=='1' ? 'IGEN <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=alkikapcsol&id=' . $almenu["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> kikapcsol</a>' : 'NEM <a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=albekapcsol&id=' . $almenu["menuid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> bekapcsol</a>') . '</div>';
											echo '<div class="col-lg-4 col-xxl-3 d-flex justify-content-between">';
													echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=alszerkesztes&id=' . $almenu["menuid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
													echo '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=menukezelo&opt=altorles&id=' . $almenu["menuid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
											echo '</div>';
											echo '</div>';
										}
									}
								}
							}
							else
							{
								echo '<p class="text-danger">Nincs még létrehozott menüpont!</p>';
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

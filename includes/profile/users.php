<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujuser")
			user_hozzaad();
		elseif ($_REQUEST["opt"] == "ujmentes")
			user_uj_mentes();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			user_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			user_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			user_torles();
		else
			user_lista();
	}
	else
	{
		user_lista();
	}
}
else
{
    return false;
}

	function user_torles()
	{
		$DB = NULL;
		try
		{
			if($_REQUEST["uid"]!=$_SESSION["userkod"] AND $_SESSION["jogkor"]>"1")
			{
				$DB = connect(true);
				//adatbázis bejegyzés törlése
				$torles=$DB->query("delete from ".prefix."_users_hun where uid='".$_REQUEST["uid"]."'");
				$DB = NULL;
				$_SESSION["php_notification"]="Sikeres művelet";
				echo '<script>window.location.replace("'.adminurl.'index.php?action=profile&thing=users");</script>';
				die();
			}
			else
			{
				$_SESSION["php_err_notification"]="Sikertelen művelet, csalni próbáltál";
				echo '<script>window.location.replace("'.adminurl.'index.php?action=profile&thing=users");</script>';
				die();
			}
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			user_lista();
		}
	}
	function user_uj_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["usernev"]) || empty($form_data["usernev"]))
				throw new Exception("A felhasználónév kitöltése kötelező!");
			if (!isset($form_data["useremail"]) || empty($form_data["useremail"]))
				throw new Exception("Az email cím kitöltése kötelező!");
			if (!isset($form_data["userpwd"]) || empty($form_data["userpwd"]))
				throw new Exception("A jelszó kitöltése kötelező!");
			if (!isset($form_data["jogkor"]) || empty($form_data["jogkor"]))
				throw new Exception("A jogkör kitöltése kötelező!");
			
			$DB = connect(true);
			$elment=$DB->query("insert into ".prefix."_users_hun (usernev,teljesnev,useremail,userpwd,beosztas,jogkor,usercont) values ('".$form_data["usernev"]."','".$form_data["teljesnev"]."','".$form_data["useremail"]."','".hash('sha256', $form_data["userpwd"])."','".$form_data["beosztas"]."','".$form_data["jogkor"]."','".$form_data["usercont"]."')");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=profile&thing=users");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			user_lista();
		}
	}
	function user_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["usernev"]) || empty($form_data["usernev"]))
				throw new Exception("A felhasználónév kitöltése kötelező!");
			if (!isset($form_data["useremail"]) || empty($form_data["useremail"]))
				throw new Exception("Az email cím kitöltése kötelező!");
			
			if(isset($form_data["userpwd"]) AND $form_data["userpwd"]!="")
			{
				$userpwchange="userpwd='".hash('sha256', $form_data["userpwd"])."',";
			}
			else
			{
				$userpwchange="";
			}
			
			$DB = connect(true);

			$elment=$DB->query("update ".prefix."_users_hun set 
				usernev='".$form_data["usernev"]."',
				teljesnev='".$form_data["teljesnev"]."',
				useremail='".$form_data["useremail"]."',
				".$userpwchange."
				beosztas='".$form_data["beosztas"]."',
				jogkor='".$form_data["jogkor"]."',
				usercont='".$form_data["usercont"]."'
			where uid='".$form_data["uid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=profile&thing=users");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			user_lista();
		}
	}
	function user_hozzaad()
	{
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új felhasználó hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=profile&thing=users&opt=ujmentes" method="POST">
							<div class="form-group">
								<label for="teljesnev">Új felhasználó teljes neve</label>
								<input type="text" name="teljesnev" id="teljesnev" class="form-control" placeholder="Adja meg az új felhasználó teljes nevét" required />
							</div>
							<div class="form-group">
								<label for="usernev">Új felhasználó belépési neve (username)</label>
								<input type="text" name="usernev" id="usernev" class="form-control" placeholder="Adja meg az új felhasználó belépési nevét" required />
							</div>
							<div class="form-group">
								<label for="userpwd">Új felhasználó jelszava</label>
								<input type="password" name="userpwd" id="userpwd" class="form-control" placeholder="Adja meg az új felhasználó jelszavát" required />
							</div>
							<div class="form-group">
								<label for="useremail">Új felhasználó e-mail címe</label>
								<input type="email" name="useremail" id="useremail" class="form-control" placeholder="Adja meg az új felhasználó e-mail címét" required />
							</div>
							<div class="form-group">
								<label for="beosztas">Új felhasználó beosztása</label>
								<input type="text" name="beosztas" id="beosztas" class="form-control" placeholder="Adja meg az új felhasználó beosztását" />
							</div>
							<div class="form-group">
								<label for="jogkor">Új felhasználó jogköre</label>
								<select name="jogkor" id="jogkor" class="form-control" required />
									<option value="" selected disabled>Kérem válasszon!</option>
									<option value="1">Felhasználó</option>
									<option value="2">Adminisztrátor</option>
								</select>
							</div>
							<div class="form-group">
								<label for="usercont">Új felhasználó bemutatása</label>
								<input type="text" name="usercont" id="usercont" class="form-control" placeholder="Adja meg az új felhasználó bemutatását néhány szóban" />
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
								<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=profile&thing=users"><span class="mdi mdi-arrow-left"></span> Vissza</a>
							</div>
						</form>
					</div>
				</div>';
	}
	function user_szerkeszt()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_users_hun where uid='".$_REQUEST["uid"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott oldalsáv elem az azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Felhasználó szerkesztése </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=profile&thing=users&opt=szerkesztmentes" method="POST">
							<input type="hidden" name="uid" value="'.$res["uid"].'">
							<div class="form-group">
								<label for="teljesnev">Felhasználó teljes neve</label>
								<input type="text" name="teljesnev" id="teljesnev" class="form-control" placeholder="Adja meg a felhasználó teljes nevét" value="'.$res["teljesnev"].'" required />
							</div>
							<div class="form-group">
								<label for="usernev">Felhasználó belépési neve (username)</label>
								<input type="text" name="usernev" id="usernev" class="form-control" placeholder="Adja meg a felhasználó belépési nevét" value="'.$res["usernev"].'" required />
							</div>
							<div class="form-group">
								<label for="userpwd">Felhasználó jelszava</label>
								<input type="password" name="userpwd" id="userpwd" class="form-control" placeholder="Ha meg akarod változtatni a jelszót, akkor töltsd ki csak!" />
							</div>
							<div class="form-group">
								<label for="useremail">Felhasználó e-mail címe</label>
								<input type="email" name="useremail" id="useremail" class="form-control" placeholder="Adja meg a felhasználó e-mail címét" value="'.$res["useremail"].'" required />
							</div>
							<div class="form-group">
								<label for="beosztas">Felhasználó beosztása</label>
								<input type="text" name="beosztas" id="beosztas" class="form-control" placeholder="Adja meg a felhasználó beosztását" value="'.$res["beosztas"].'" />
							</div>
							<div class="form-group">
								<label for="jogkor">Felhasználó jogköre</label>
								<select name="jogkor" id="jogkor" class="form-control" />
									<option value="1" '.($res["jogkor"]=="1" ? 'selected' : '').'>Felhasználó</option>
									<option value="2" '.($res["jogkor"]=="2" ? 'selected' : '').'>Adminisztrátor</option>
								</select>
							</div>
							<div class="form-group">
								<label for="usercont">Felhasználó bemutatása</label>
								<input type="text" name="usercont" id="usercont" class="form-control" placeholder="Adja meg a felhasználó bemutatását néhány szóban" value="'.$res["usercont"].'" />
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
								<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=profile&thing=users"><span class="mdi mdi-arrow-left"></span> Vissza</a>
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
			user_lista();
		}
	}
	function user_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_users_hun where uid!='".$_SESSION["userkod"]."'");
			$res = $sth->fetchAll();
			$DB = NULL;
			
			/*** JOGKÖRÖK ***/
			$jogkorok=array("nem létezik","felhasználó","admin","tulajdonos");

			?>
			<div class="card">
				<div class="card-body">
					<h2>Felhasználók listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=profile&thing=users&opt=ujuser" class="btn btn-primary btn-sm">+ Új hozzáadása</a>

					<div class="mt-3">
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen létrehozott felhasználó sem!</p>';
						}
						else
						{
			?>
							<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Név</big><br><small>(felhasználónév)</small></strong></th>
								  <th scope="col"><strong><big>E-mail cím</big></strong></th>
								  <th scope="col"><strong><big>Beosztás</big><br><small>(jogkör)</small></strong></th>
								  <th scope="col"><strong><big>Műveletek</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
			<?php 
							$out = "";
							foreach($res as $row){ 
								$out .= '<tr>';
									$out .= '<td>' . $row["teljesnev"] . '<br><small>('.$row["usernev"].')</small></td>';
									$out .= '<td>' . $row["useremail"] . '</td>';
									$out .= '<td>' . $row["beosztas"] . '<br><small>('.$jogkorok[$row["jogkor"]].')</small></td>';
									$out .= '<td>';
										if($_SESSION["jogkor"]=="3")
										{
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=profile&thing=users&opt=szerkesztes&uid=' . $row["uid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
											$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=profile&thing=users&opt=torles&uid=' . $row["uid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
										}
										elseif($row["jogkor"]!="3")
										{
											if($_SESSION["jogkor"]>"1")
											{
												$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=profile&thing=users&opt=szerkesztes&uid=' . $row["uid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
												$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=profile&thing=users&opt=torles&uid=' . $row["uid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
											}
											else
											{
												$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=profile&thing=users&opt=szerkesztes&uid=' . $row["uid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
											}
										}
										else
										{
											$out.="";
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

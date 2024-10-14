<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "szerkesztmentes")
			own_szerkeszt_mentes();
		else
			own_adatlap();
	}
	else
	{
		own_adatlap();
	}
}
else
{
    return false;
}

	function own_szerkeszt_mentes()
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
				usercont='".$form_data["usercont"]."'
			where uid='".$form_data["uid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=profile&thing=own");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			own_adatlap();
		}
	}
	function own_adatlap()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_users_hun where uid='".$_SESSION["userkod"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Felhasználó szerkesztése </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=profile&thing=own&opt=szerkesztmentes" method="POST">
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
								<label for="usercont">Felhasználó bemutatása</label>
								<input type="text" name="usercont" id="usercont" class="form-control" placeholder="Adja meg a felhasználó bemutatását néhány szóban" value="'.$res["usercont"].'" />
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
							</div>
						</form>
					</div>
				</div>';
			}
		}
		catch(Exception $e)
		{
			$DB = NULL;
			echo '<p class="text-danger">' . $e->getMessage() . '</p>';
		}
	}
?>

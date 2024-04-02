<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujelem")
			socialmedia_hozzaad();
		elseif ($_REQUEST["opt"] == "ujmentes")
			socialmedia_uj_mentes();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			socialmedia_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			socialmedia_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			socialmedia_torles();
		else
			socialmedia_lista();
	}
	else
	{
		socialmedia_lista();
	}
}
else
{
    return false;
}

	function socialmedia_torles()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			//adatbázis bejegyzés törlése
			$torles=$DB->query("delete from ".prefix."_socialmedia_".$_SESSION["lang"]." where socialid='".$_REQUEST["id"]."'");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=kozossegimedia");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			socialmedia_lista();
		}
	}
	function socialmedia_uj_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["socialnev"]) || empty($form_data["socialnev"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			if (!isset($form_data["sociallink"]) || empty($form_data["sociallink"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			
			$DB = connect(true);
			$elment=$DB->query("insert into ".prefix."_socialmedia_".$_SESSION["lang"]." (socialnev,sociallink) values ('".$form_data["socialnev"]."','".$form_data["sociallink"]."')");
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=kozossegimedia");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			socialmedia_lista();
		}
	}
	function socialmedia_szerkeszt_mentes()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);

			if (!isset($form_data["socialnev"]) || empty($form_data["socialnev"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			if (!isset($form_data["sociallink"]) || empty($form_data["sociallink"]))
				throw new Exception("A megnevezés kitöltése kötelező!");
			
			$DB = connect(true);

			$elment=$DB->query("update ".prefix."_socialmedia_".$_SESSION["lang"]." set 
				socialnev='".$form_data["socialnev"]."',
				sociallink='".$form_data["sociallink"]."'
			where socialid='".$form_data["socialid"]."'");
				
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=kozossegimedia");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			socialmedia_lista();
		}
	}
	function socialmedia_hozzaad()
	{
		echo '<h2 class="text-muted font-weight-bold mb-2"> Új közösségi médiaelem hozzáadása </h2>
				<div class="card">
					<div class="card-body">
						<form action="'.adminurl.'index.php?action=weboldal&thing=kozossegimedia&opt=ujmentes" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label for="socialnev">Új közösségi médialink neve</label>
								<input type="text" name="socialnev" id="socialnev" class="form-control" placeholder="Adja meg az új közösségi médialink nevét" required />
							</div>
							<div class="form-group">
								<label for="sociallink">Új közösségi médialink hivatkozása (URL)</label>
								<input type="text" name="sociallink" id="sociallink" class="form-control" placeholder="Adja meg az új közösségi médialink hivatkozását" required />
							</div>
							<div>
								<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
								<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=kozossegimedia"><span class="mdi mdi-arrow-left"></span> Vissza</a>
							</div>
						</form>
					</div>
				</div>';
	}
	function socialmedia_szerkeszt()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_socialmedia_".$_SESSION["lang"]." where socialid='".$_REQUEST["id"]."'");
			$res = $sth->fetch();
			if(empty($res))
			{
				throw new Exception("Nem található adatok a megadott oldalsáv elem az azonosító alapján!");
				$DB = NULL;
			}
			else
			{
				echo '<h2 class="text-muted font-weight-bold mb-2"> Közösségi médiaelem szerkesztése </h2>
						<div class="card">
							<div class="card-body">
								<form action="'.adminurl.'index.php?action=weboldal&thing=kozossegimedia&opt=szerkesztmentes" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="socialid" value="'.$_REQUEST["id"].'">
									<div class="form-group">
										<label for="socialnev">Új közösségi médialink neve</label>
										<input type="text" name="socialnev" id="socialnev" class="form-control" value="'.$res["socialnev"].'" required />
									</div>
									<div class="form-group">
										<label for="sociallink">Új közösségi médialink hivatkozása (URL)</label>
										<input type="text" name="sociallink" id="sociallink" class="form-control" value="'.$res["sociallink"].'" required />
									</div>
									<div>
										<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
										<a class="btn btn-secondary ms-2" href="'.adminurl.'index.php?action=weboldal&thing=kozossegimedia"><span class="mdi mdi-arrow-left"></span> Vissza</a>
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
			socialmedia_lista();
		}
	}
	function socialmedia_lista()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$sth = $DB->query("SELECT * FROM ".prefix."_socialmedia_".$_SESSION["lang"]."");
			$res = $sth->fetchAll();
			$DB = NULL;

			?>
			<div class="card">
				<div class="card-body">
					<h2>Közösségi médiaelemek listája</h2>
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=weboldal&thing=kozossegimedia&opt=ujelem" class="btn btn-primary">+ Új hozzáadása</a>

					<div class="mt-3">
						<h2>Jelenlegi linkek</h2>
			<?php
						if(empty($res))
						{
							echo '<p class="text-danger">Jelenleg nem található egyetlen létrehozott közösségi médiaelem sem!</p>';
						}
						else
						{
			?>
							<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Elem neve</big></strong></th>
								  <th scope="col"><strong><big>Link</big></strong></th>
								  <th scope="col"><strong><big>Műveletek</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
			<?php 
							$out = "";
							foreach($res as $row){ 
								$out .= '<tr>';
									$out .= '<td>' . $row["socialnev"] . '</td>';
									$out .= '<td><a href="'.$row["sociallink"].'" target="_blank" class="btn btn-default btn-sm px-1 py-0">' . $row["sociallink"] . ' &raquo;</a></td>';
									$out .= '<td>';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=kozossegimedia&opt=szerkesztes&id=' . $row["socialid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a> ';
										$out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=weboldal&thing=kozossegimedia&opt=torles&id=' . $row["socialid"] . '" class="btn btn-outline-danger px-2 py-1" onClick="return confirm(\'Biztosan törlöd?\')"><span class="mdi mdi-trash-can"></span> Törlés</a>';
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

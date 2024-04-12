<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "defsave")
			beallitasok_modosit();
		else
			beallitasok_lista();
	}
	else
	{
		beallitasok_lista();
	}
}
else
{
    return false;
}

	function beallitasok_modosit()
	{
		$DB = NULL;
		try
		{
			//adatok tisztítása és ellenőrzése
			$form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
			$DB = connect(true);

			//alapadatok sql-be mentése
			foreach($_POST as $k=>$v)
			{
				if(!isset($_POST["debugmod"])){$elment=$DB->query("update ".prefix."_parameters_hun set webparamcont='0' where webparamname='debugmod'");}
				if(!isset($_POST["breakoff"])){$elment=$DB->query("update ".prefix."_parameters_hun set webparamcont='0' where webparamname='breakoff'");}
				$elment=$DB->query("update ".prefix."_parameters_hun set webparamcont='".$v."' where webparamname='".$k."'");
			}
			//képek feltöltése
			if(isset($_FILES['favicon']) AND $_FILES['favicon']['name']!="")
			{
				$ext = strtolower(substr(strrchr($_FILES["favicon"]["name"], "."), 1));
				if($ext == "png" || $ext == "ico")
				{
					$fajlnev="favicon.".$ext;
					
					if(move_uploaded_file($_FILES['favicon']['tmp_name'],"../".$fajlnev))
					{
						list($width, $height) = getimagesize("../".$fajlnev);
						if($width<="1")
						{
							unlink("../".$fajlnev);
							$_SESSION["php_err_notification"]="Fájl feltöltési hiba, ez nem favicon-nak való kép!";
							die();
						}
					}
					else
					{
						$_SESSION["php_err_notification"]="Fájl feltöltési hiba, szerver hiba történt!";
						die();
					}
					
				}
				else
				{
					$_SESSION["php_err_notification"]="Sikertelen művelet. :( A kép formátuma nem volt megfelelő, a feltölthető formátum: ICO, PNG.";
					die();
				}
			}
			
			if(isset($_FILES['ceglogo']) AND $_FILES['ceglogo']['name']!="")
			{
				$SafeFile = $_FILES['ceglogo']['name'];
				$SafeFile = strtolower($SafeFile);
				$SafeFile = cserekari($SafeFile);
				$fajlnev=$SafeFile;
				
				$ext = strtolower(substr(strrchr($_FILES["ceglogo"]["name"], "."), 1));
				if($ext == "png" || $ext == "jpg")
				{
					if(move_uploaded_file($_FILES['ceglogo']['tmp_name'],"../".$fajlnev))
					{
						list($width, $height) = getimagesize("../".$fajlnev);
						if($width<="1")
						{
							unlink("../".$fajlnev);
							$_SESSION["php_err_notification"]="Fájl feltöltési hiba, ez nem kép!";
							die();
						}
					}
					else
					{
						$_SESSION["php_err_notification"]="Sikertelen művelet. :( A képet nem sikerült feltölteni.";
						die();
					}
					
				}
				else
				{
					$_SESSION["php_err_notification"]="Sikertelen művelet. :( A kép formátuma nem volt megfelelő, a feltölthető formátum: JPG, PNG.";
					die();
				}
			}

			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=beallitasok");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			beallitasok_lista();
		}
	}
	function beallitasok_lista()
	{
		$DB = connect(true);

		$webparamname=array();
		$webparamcont=array();
		$webosszetevok=$DB->query("select * from ".prefix."_parameters_hun");
		while($webadatok=$webosszetevok->fetch())
		{
			array_push($webparamname, $webadatok["webparamname"]);
			array_push($webparamcont, $webadatok["webparamcont"]);
		}
		$DB = NULL;
		
		$parameterekdef=array("title"=>"Címsor (META TITLE)|text",
								"metadescription"=>"META leírás (META DESCRIPTION)|text",
								"sitename"=>"Fejléc szöveg (SITENAME)|text",
								"siteslogen"=>"Szlogen|text",
								"copyright"=>"Copyright infó láblécben|text",
								"sablon"=>"Beállított sablon|nulla",
								"favicon"=>"Weboldal ikon|file",
								"ceglogo"=>"Logó|file",
								"kapcstel"=>"Kapcsolat felvételi mobil telefonszám|text",
								"kapcsemail"=>"Kapcsolat felvételi e-mail cím|text",
								"gdpr"=>"GDPR kód|text",
								"breakoff"=>"Karbantartás mód|checkbox",
								"debugmod"=>"Hibafigyelő mód|checkbox",
								"gmapskey"=>"Google térképhez cím|text",
								"nyelvek"=>"Telepített nyelvek|nulla",
								"bkpdate"=>"Utolsó adatbázis biztonsági mentés|nulla");
		
		echo '<div class="card">
				<div class="card-body">
					<h2>Weboldal alapbeállítások</h2>
					<div class="mt-3">
						<table class="table">
							  <thead>
								<tr style="border-bottom: 2px solid #4aa3c5;">
								  <th scope="col"><strong><big>Paraméter neve</big></strong></th>
								  <th scope="col"><strong><big>Paraméter értéke</big></strong></th>
								</tr>
							  </thead>
							  <tbody>
								<form action="'.adminurl.'index.php?action=weboldal&thing=beallitasok&opt=defsave" method="POST" enctype="multipart/form-data">';

							$out = "";
							foreach($webparamname as $key => $value)
							{
								$neve = explode("|",$parameterekdef[$value]);
								$out .= '<tr>';
									$out .= '<td style="padding:1rem;">'.$neve[0].':</td>';
									if($neve[1]!="nulla")
									{
										$out .= '<td>'.($neve[1]=="file" ? '<img src="/'.$webparamcont[$key].'" class="img-responsive normal-image"><br>' : '').'<input type="'.$neve[1].'" name="'.$webparamname[$key].'" value="'.($neve[1]=='checkbox' ? '1' : $webparamcont[$key]).'" '.($neve[1]!="checkbox" ? 'class="form-control"' : 'style="margin-left: 22px;" '.($webparamcont[$key]==1 ? 'checked' : '').'').'></td>';
									}
									else
									{
										$out .= '<td><span class="form-control" style="border:none;background:transparent;">'.$webparamcont[$key].'</span></td>';
									}
								$out .= '</tr>';
							}
							$out .= '<tr>
										<td colspan="2">
											<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
										</td>
									</tr>
										</form>';
							$out .= '</tbody></table>';
							echo $out;
		echo '		</div>
				</div>
			</div>';
	}
?>

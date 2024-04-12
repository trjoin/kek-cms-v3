<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "start")
		{
			backupper_start();
		}
		elseif ($_REQUEST["opt"] == "dbrestore")
		{
			backupper_restore();
		}
		elseif ($_REQUEST["opt"] == "dbrepair")
		{
			backupper_dbrepair();
		}
		else
		{
			backupper_lista();
		}
	}
	else
	{
		backupper_lista();
	}
}
else
{
    return false;
}

	function backupper_dbrepair()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$nyelvek=$DB->query("select * from ".prefix."_parameters_hun where webparamname='nyelvek'");
			$nyelv=$nyelvek->fetch();
			$osszes=explode(",",$nyelv["webparamcont"]);
			
			$backuptablak=array();
			
			//nyelvesitett alap SQL táblák injektálása
			foreach($osszes as $k=>$v)
			{
				if($v!="")
				{
					array_push($backuptablak, 
						prefix."_menupontok_".$v."",
						prefix."_almenupontok_".$v."",
						prefix."_oldalak_".$v."",
						prefix."_oldalsav_".$v."",
						prefix."_socialmedia_".$v.""
					);
				}
			}
			
			//nem nyelvesitett alap SQL táblák injektálása
			array_push($backuptablak, 
				prefix."_parameters_hun",
				prefix."_modul_hun",
				prefix."_users_hun",
				prefix."_usermod_hun",
				prefix."_userlog_hun",
				prefix."_userjogkor_hun",
				prefix."_visitorstat_hun",
				prefix."_newslist_hun"
			);
			
			//moduláris SQL táblák injektálása nyelvesitetten
			$modulok=$DB->query("select * from ".prefix."_modul_hun");
			while($m=$modulok->fetch())
			{
				foreach($osszes as $k=>$v)
				{
					if($v!="")
					{
						array_push($backuptablak, prefix."_".cserekari(strtolower($m["modulnev"]))."_".$v."");
					}
				}
			}
			
			//shop SQL táblák injektálása
			array_push($backuptablak, 
				prefix."_shop_akcio_hun",
				prefix."_shop_alkategoria_hun",
				prefix."_shop_csoport_hun",
				prefix."_shop_fizmod_hun",
				prefix."_shop_fokategoria_hun",
				prefix."_shop_gyarto_hun",
				prefix."_shop_megrendelesek_hun",
				prefix."_shop_params_hun",
				prefix."_shop_szallmod_hun",
				prefix."_shop_tattach_hun",
				prefix."_shop_termekallapot_hun",
				prefix."_shop_termekarsav_hun",
				prefix."_shop_termekar_hun",
				prefix."_shop_termekkepek_hun",
				prefix."_shop_termekkeszlet_hun",
				prefix."_shop_termek_hun",
				prefix."_shop_topcio_hun",
				prefix."_shop_topckat_hun",
				prefix."_shop_vasarlok_hun"
			);
			
			//BACKUP ellenőrzése, számitása és elvégzése
			foreach($backuptablak as $k => $v)
			{
				//van-e hiányzó tábla, vagy sérült, és annak javitási kisérlete
				$volte=$DB->query("SELECT 1 FROM information_schema.tables WHERE table_schema = '".DB_NAME."' AND table_name = '".$v."' LIMIT 1");
				if($volte->rowCount()<=0)
				{
					$DB->query("CREATE TABLE ".$v." LIKE ".$v."_bkp ");
					$DB->query("INSERT INTO ".$v." SELECT * FROM ".$v."_bkp ");
					$DB->query("REPAIR TABLE ".$v." ");
					$DB->query("OPTIMIZE TABLE ".$v." ");
				}
			}
			
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=backupper");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			backupper_lista();
		}
	}
	
	function backupper_restore()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$nyelvek=$DB->query("select * from ".prefix."_parameters_hun where webparamname='nyelvek'");
			$nyelv=$nyelvek->fetch();
			$osszes=explode(",",$nyelv["webparamcont"]);
			
			$restoretablak=array();
			
			//nyelvesitett alap SQL táblák injektálása
			foreach($osszes as $k=>$v)
			{
				if($v!="")
				{
					array_push($restoretablak, 
						prefix."_menupontok_".$v."",
						prefix."_almenupontok_".$v."",
						prefix."_oldalak_".$v."",
						prefix."_oldalsav_".$v."",
						prefix."_socialmedia_".$v.""
					);
				}
			}
			
			//nem nyelvesitett alap SQL táblák injektálása
			array_push($restoretablak, 
				prefix."_parameters_hun",
				prefix."_modul_hun",
				prefix."_users_hun",
				prefix."_usermod_hun",
				prefix."_userlog_hun",
				prefix."_userjogkor_hun",
				prefix."_visitorstat_hun",
				prefix."_newslist_hun"
			);
			
			//moduláris SQL táblák injektálása nyelvesitetten
			$modulok=$DB->query("select * from ".prefix."_modul_hun");
			while($m=$modulok->fetch())
			{
				foreach($osszes as $k=>$v)
				{
					if($v!="")
					{
						array_push($restoretablak, prefix."_".cserekari(strtolower($m["modulnev"]))."_".$v."");
					}
				}
			}
			
			//shop SQL táblák injektálása
			array_push($restoretablak, 
				prefix."_shop_akcio_hun",
				prefix."_shop_alkategoria_hun",
				prefix."_shop_csoport_hun",
				prefix."_shop_fizmod_hun",
				prefix."_shop_fokategoria_hun",
				prefix."_shop_gyarto_hun",
				prefix."_shop_megrendelesek_hun",
				prefix."_shop_params_hun",
				prefix."_shop_szallmod_hun",
				prefix."_shop_tattach_hun",
				prefix."_shop_termekallapot_hun",
				prefix."_shop_termekarsav_hun",
				prefix."_shop_termekar_hun",
				prefix."_shop_termekkepek_hun",
				prefix."_shop_termekkeszlet_hun",
				prefix."_shop_termek_hun",
				prefix."_shop_topcio_hun",
				prefix."_shop_topckat_hun",
				prefix."_shop_vasarlok_hun"
			);
			
			//BACKUP ellenőrzése, számitása és elvégzése
			foreach($restoretablak as $k => $v)
			{
				//backup RESTORE
				$DB->query("DROP TABLE ".$v." ");
				$backupre=$DB->query("CREATE TABLE ".$v." LIKE ".$v."_bkp ");
				$backupin=$DB->query("INSERT INTO ".$v." SELECT * FROM ".$v."_bkp ");
			}
			
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=backupper");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			backupper_lista();
		}
	}
	
	function backupper_start()
	{
		$DB = NULL;
		try
		{
			$DB = connect(true);
			$nyelvek=$DB->query("select * from ".prefix."_parameters_hun where webparamname='nyelvek'");
			$nyelv=$nyelvek->fetch();
			$osszes=explode(",",$nyelv["webparamcont"]);
			
			$backuptablak=array();
			
			//nyelvesitett alap SQL táblák injektálása
			foreach($osszes as $k=>$v)
			{
				if($v!="")
				{
					array_push($backuptablak, 
						prefix."_menupontok_".$v."",
						prefix."_almenupontok_".$v."",
						prefix."_oldalak_".$v."",
						prefix."_oldalsav_".$v."",
						prefix."_socialmedia_".$v.""
					);
				}
			}
			
			//nem nyelvesitett alap SQL táblák injektálása
			array_push($backuptablak, 
				prefix."_parameters_hun",
				prefix."_modul_hun",
				prefix."_users_hun",
				prefix."_usermod_hun",
				prefix."_userlog_hun",
				prefix."_userjogkor_hun",
				prefix."_visitorstat_hun",
				prefix."_newslist_hun"
			);
			
			//moduláris SQL táblák injektálása nyelvesitetten
			$modulok=$DB->query("select * from ".prefix."_modul_hun");
			while($m=$modulok->fetch())
			{
				foreach($osszes as $k=>$v)
				{
					if($v!="")
					{
						array_push($backuptablak, prefix."_".cserekari(strtolower($m["modulnev"]))."_".$v."");
					}
				}
			}
			
			//shop SQL táblák injektálása
			array_push($backuptablak, 
				prefix."_shop_akcio_hun",
				prefix."_shop_alkategoria_hun",
				prefix."_shop_csoport_hun",
				prefix."_shop_fizmod_hun",
				prefix."_shop_fokategoria_hun",
				prefix."_shop_gyarto_hun",
				prefix."_shop_megrendelesek_hun",
				prefix."_shop_params_hun",
				prefix."_shop_szallmod_hun",
				prefix."_shop_tattach_hun",
				prefix."_shop_termekallapot_hun",
				prefix."_shop_termekarsav_hun",
				prefix."_shop_termekar_hun",
				prefix."_shop_termekkepek_hun",
				prefix."_shop_termekkeszlet_hun",
				prefix."_shop_termek_hun",
				prefix."_shop_topcio_hun",
				prefix."_shop_topckat_hun",
				prefix."_shop_vasarlok_hun"
			);
			
			//BACKUP ellenőrzése, számitása és elvégzése
			foreach($backuptablak as $k => $v)
			{
				//volt-e már backup? ellenőrzés és ha igen akkor eldobás
				$volte=$DB->query("SELECT 1 FROM information_schema.tables WHERE table_schema = '".DB_NAME."' AND table_name = '".$v."_bkp' LIMIT 1");
				if($volte->rowCount()>0)
				{
					$DB->query("DROP TABLE ".$v."_bkp");
				}
				//backup copy kreálása
				$backupre=$DB->query("CREATE TABLE ".$v."_bkp LIKE ".$v." ");
				$backupin=$DB->query("INSERT INTO ".$v."_bkp SELECT * FROM ".$v." ");
			}
			//végül a backupdate bejegyzése
			$bkpdateinsert=$DB->query("INSERT INTO ".prefix."_parameters_hun_bkp (webparamname,webparamcont) values('bkpdate',now())");
			
			$DB = NULL;
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=backupper");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			backupper_lista();
		}
	}
	function backupper_lista()
	{
		$DB = connect(true);
		
		echo '<div class="card">
				<div class="card-body">
					<h2>Adatbázis biztonsági mentés</h2>
					<div class="mt-3">';
						$volte=$DB->query("SELECT 1 FROM information_schema.tables WHERE table_schema = '".DB_NAME."' AND table_name = '".prefix."_parameters_hun_bkp' LIMIT 1");
						if($volte->rowCount()>0)
						{
							$mikor=$DB->query("select * from ".prefix."_parameters_hun_bkp where webparamname='bkpdate'");
							$mk=$mikor->fetch();
							echo '<p>Készült már biztonsági mentés '.str_replace("-",".",$mk["webparamcont"]).'.-kor. Visszaállítod abból az adatokat?</p>';
							echo '<p><a href="'.adminurl.'index.php?action=weboldal&thing=backupper&opt=dbrestore" class="btn btn-warning btn-sm" onclick="return confirm(\'Biztosan visszaállítasz egy régebbi adatbázis állapotot?\')">VISSZAÁLLÍTÁS</a></p><hr>';
							echo '<p>Hibás működést vagy hiányosságot észleltél? Próbáld az adatbázis táblák megjavításával kiküszöbölni a dolgokat.</p>';
							echo '<p><a href="'.adminurl.'index.php?action=weboldal&thing=backupper&opt=dbrepair" class="btn btn-secondary btn-sm" onclick="alert(\'Ez a művelet egy létező adatbázis mentésből megprobálja kijavitani a sérült vagy eltünt táblákat.\')">JAVÍTÁS</a></p><hr>';
						}
						else
						{
							//biztonsági mentős paraméter tábla elkészitése, ha még nincs
							$backupre=$DB->query("CREATE TABLE ".prefix."_parameters_hun_bkp LIKE ".prefix."_parameters_hun ");
							$backupin=$DB->query("INSERT INTO ".prefix."_parameters_hun_bkp SELECT * FROM ".prefix."_parameters_hun ");
							$bkpdateinsert=$DB->query("INSERT INTO ".prefix."_parameters_hun_bkp (webparamname,webparamcont) values('bkpdate',now())");
						}
						echo '<p>Itt elvégezheted manuálisan az adatbázisod biztonsági mentését arra az esetre, ha megsérülne vagy elrontanál valamit, esetleg bármi egyéb káros tényező alakulna ki.</p>';
						echo '<form action="'.adminurl.'index.php?action=weboldal&thing=backupper&opt=start" method="POST">';
						echo '<input type="hidden" name="dbackup" value="1">';
						echo '<input type="submit" value="ÚJ BIZTONSÁGI MENTÉS" class="btn btn-sm btn-primary">';
						echo '</form>';
		echo '		</div>
				</div>
			</div>';
		
		$DB = NULL;
	}
?>

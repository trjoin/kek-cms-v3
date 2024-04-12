<?php
	/*** default load/start ***/
    include_once("config.inc.php");
    include_once("connect.php");
    include_once("functions.php");
	session_start();
	/*** használati nyelv letárolás ***/
	if(!isset($_REQUEST["lang"]) AND !isset($_SESSION["lang"]))
	{
		$_SESSION["lang"] = lang;
		$_SESSION["lang_code"] = langcode;
	}
	else
	{
		if(!isset($_REQUEST["lang"]))
		{
			$_SESSION["lang"] = $_SESSION["lang"];
			$_SESSION["lang_code"] = $_SESSION["lang_code"];
		}
		else
		{
			$_SESSION["lang"] = $_REQUEST["lang"];
			if($_REQUEST["lang"]=="hun"){ $_SESSION["lang_code"] = "HU"; }
			if($_REQUEST["lang"]=="ger"){ $_SESSION["lang_code"] = "DE"; }
			if($_REQUEST["lang"]=="eng"){ $_SESSION["lang_code"] = "GB"; }
		}
	}
	//első kapcsolat DB-hez
	$DB = connect(true);
	/*** ALAPADATOK BETÖLTÉSE ***/
	$defaultwebdata=array();
	$webosszetevok=$DB->query("select * from ".prefix."_parameters_hun");
	while($webadatok=$webosszetevok->fetch())
	{
		$defaultwebdata[$webadatok["webparamname"]] = $webadatok["webparamcont"];
	}
    /*** DEBUGGER FIGYELÉSE ***/
	if($defaultwebdata["debugmod"]=="1")
	{
		ini_set("display_startup_errors", 1);
		ini_set("display_errors", 1);
		error_reporting(-1);
		$_SESSION["debugmode"] = "<span style='color:#900;background:#fff;padding: 5px 10px;'><strong>FIGYELEM!</strong> A HIBAKERESŐ MÓD AKTÍV!</span>";
	}
	else
	{
		$_SESSION["debugmode"]="";
	}
	/*** KIJELENTKEZÉS LEKEZELÉSE ***/
	if(isset($_REQUEST["out"]))
	{
		session_unset();
		header("Location: /wp-admin/index.php");
	}
	//munkamenet ellenőrzés és aktuális akció szerinti modul betöltés
    if(isset($_SESSION["munkamenet"]) AND $_SESSION["munkamenet"]!="")
	{
        if(isset($_REQUEST["action"]))
		{
			if(file_exists("includes/".$_REQUEST["action"].".php"))
			{
				$content = "includes/".$_REQUEST["action"].".php";
			}
			else
			{
				$content = "includes/404.php";
			}
        }
		else
		{
			$content = "includes/dashboard.php";
		}
        
        include_once("header.php");
        include_once($content);
        include_once("footer.php");
    }
    else
	{
        include_once("includes/login.php");
    }
?>


          
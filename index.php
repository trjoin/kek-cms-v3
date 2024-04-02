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
	}
	else
	{
		if(!isset($_REQUEST["lang"]))
		{
			$_SESSION["lang"] = $_SESSION["lang"];
		}
		else
		{
			$_SESSION["lang"] = $_REQUEST["lang"];
		}
	}
	//első kapcsolat DB-hez
	$pdo = connect(true);
	/*** ALAPADATOK BETÖLTÉSE ***/
	$defaultwebdata=array();
	$webosszetevok=$pdo->query("select * from ".prefix."_parameters_hun");
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
	}
	
	if(isset($_REQUEST["out"]))
	{
		session_unset();
		header("Location: /wp-admin/index.php");
	}
	
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


          
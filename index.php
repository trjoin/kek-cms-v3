<?php

    //a fejlesztés idejére
    ini_set("display_startup_errors", 1);
    ini_set("display_errors", 1);
    error_reporting(-1);
    
    //default load/start
    include_once("config.inc.php");
    include_once("connect.php");
    include_once("functions.php");
    session_start();
	
	if(isset($_REQUEST["out"]))
	{
		session_unset();
		header("Location: /wp-admin/index.php");
	}
    
	//only for debugging, start session
    //$_SESSION["munkamenet"] = "van";
	
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


          
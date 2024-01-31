<?php
    session_start();
    include_once("config.inc.php");
    include_once("connect.php");
    include_once("functions.php");
    
    if("be vagy lépve"){
        
        $content = "includes/dashboard.php";
        
        if($_REQUEST["action"] == "akarmi"){
            $content = "";
        }
        else if($_REQUEST["action"] == "akarmi2"){
            $content = "";
        }
        
        include_once("header.php");
        include_once($content);
        include_once("footer.php");
    }
    else{
        //login
    }
    
?>


          
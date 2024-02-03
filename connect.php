<?php
function connect()
{
    try{
        $pdo = new PDO('mysql:host='.HOST_NAME.';dbname='.DB_NAME.'', ''.USER_NAME.'', ''.PASSWD.''); 
        $pdo->query("SET NAMES 'utf8'"); 
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        
        return $pdo;
    }
    catch(Exception $e){
        return false;
    }
}
?>
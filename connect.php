<?php
function connect($exception = false)
{
    try{
        $pdo = new PDO('mysql:host='.HOST_NAME.';dbname='.DB_NAME.'', ''.USER_NAME.'', ''.PASSWD.''); 
        $pdo->query("SET NAMES 'utf8'"); 
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        if(!$exception)
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        else
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    }
    catch(Exception $e){
        if(!$exception)
            return false;
        else{
            $msg = "Adatbázis kapcsolódási hiba történt!";
            
            if(!PROD)
                $msg .= " " . $e->getMessage();
            
            throw new Exception($msg);
        }
    }
}
?>
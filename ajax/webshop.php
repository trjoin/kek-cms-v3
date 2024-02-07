<?php

include '../config.inc.php';
include '../function.php';
include '../connect.php';

session_start();

if(!isset($_SESSION["munkamenet"]) || $_SESSION["munkamenet"] == ""){
    echo json_encode(array("status" => "error", "msg" => "Nem található érvényes munkamenet, kérem jelentkezzen be újra!"));
    return false;
}

if(!isset($_POST["muvelet"]) || empty($_POST["muvelet"])){
    echo json_encode(array("status" => "error", "msg" => "Érvénytelen hívás!"));
    return false;
}

//routing
$muvelet = filter_input(INPUT_POST, "muvelet", FILTER_UNSAFE_RAW);

if($muvelet == "fokategoria_torles"){
    shop_fokategoria_torles();
}
else{
    echo json_encode(array("status" => "error", "msg" => "Nem található feladat azonosító!"));
}
    


function shop_fokategoria_torles(){
    $DB = NULL;
    try{
        if(!isset($_POST["id"]) || empty($_POST["id"]))
            throw new Exception("Hiányzó kategória azonosító!");
        
        //azonosító ellenőrzése
        $id = (int)$_POST["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő kategória azonosító!");
        
        
        $DB = connect(true);
        
        //hozzárendelt termékek ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_termek_hun WHERE tfkategoria = :id");
        $sth->bindValue(":id", $id);
        $sth->execute();
        $termekek_count = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($termekek_count["count(*)"] > 0)
            throw new Exception("A főkategória nem törölhető, mert vannak még hozzárendelt termékek (összsen: " . $termekek_count["count(*)"] . ")!");
        
        
        //főkategória adainak lekérdezése
        $sth = $DB->prepare("SELECT * FROM trs_shop_fokategoria_hun WHERE fkatid = :fkatid LIMIT 1");
        $sth->bindValue(":fkatid", $id);
        $sth->execute();
        $db_data = $sth->fetch(PDO::FETCH_ASSOC);
        
        if(empty($db_data))
            throw new Exception("Nem található a törölni kívánt főkategória!");
        
        //képfájl törlése
        if(file_exists("../../uploads/" . $db_data["fthumbnail"])){
            if(!unlink("../../uploads/" . $db_data["fthumbnail"]))
                throw new Exception("Sikertelen főkategória kép törlés!");
        }
        
        //adatbázis törlése
        $DB->query("DELETE FROM trs_shop_fokategoria_hun WHERE fkatid=" . $id . " LIMIT 1");
        
        $DB = NULL;
        
        echo json_encode(array("status" => "success", "msg" => "A főkategória sikeresen törölve"));
    }
    catch (Exception $e) {
        $DB = NULL;
        echo json_encode(array("status" => "error", "msg" => "Hiba történt! " . $e->getMessage()));
    }
    
    
}
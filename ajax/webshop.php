<?php

include '../config.inc.php';
include '../functions.php';
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

if($muvelet == "fokategoria_torles")
    shop_fokategoria_torles();
elseif($muvelet == "csoport_torles")
    shop_csoport_torles();
elseif($muvelet == "arsav_torles")
    shop_arsav_torles();
else
    echo json_encode(array("status" => "error", "msg" => "Nem található feladat azonosító!"));

    


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
            throw new Exception("A főkategória nem törölhető, mert vannak még hozzárendelt termékek (összesen: " . $termekek_count["count(*)"] . " db)!");
        
        
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



function shop_csoport_torles(){
    $DB = NULL;
    try{
        if(!isset($_POST["id"]) || empty($_POST["id"]))
            throw new Exception("Hiányzó csoport azonosító!");
        
        //azonosító ellenőrzése
        $id = (int)$_POST["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő csoport azonosító!");
        
        
        $DB = connect(true);
        
        //hozzárendelt termékek ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_fokategoria_hun WHERE csoportid = :id");
        $sth->bindValue(":id", $id);
        $sth->execute();
        $termekek_count = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($termekek_count["count(*)"] > 0)
            throw new Exception("A csoport nem törölhető, mert vannak még hozzárendelt főkategóriák (összesen: " . $termekek_count["count(*)"] . " db)!");
        
        
        //főkategória adainak lekérdezése
        $sth = $DB->prepare("SELECT * FROM trs_shop_csoport_hun WHERE csopid = :csopid LIMIT 1");
        $sth->bindValue(":csopid", $id);
        $sth->execute();
        $db_data = $sth->fetch(PDO::FETCH_ASSOC);
        
        if(empty($db_data))
            throw new Exception("Nem található a törölni kívánt csoport!");
        
        //adatbázis törlése
        $DB->query("DELETE FROM trs_shop_csoport_hun WHERE csopid=" . $id . " LIMIT 1");
        
        $DB = NULL;
        
        echo json_encode(array("status" => "success", "msg" => "A csoport sikeresen törölve"));
    }
    catch (Exception $e) {
        $DB = NULL;
        echo json_encode(array("status" => "error", "msg" => "Hiba történt! " . $e->getMessage()));
    }
}



function shop_arsav_torles(){
    $DB = NULL;
    try{
        if(!isset($_POST["id"]) || empty($_POST["id"]))
            throw new Exception("Hiányzó ársáv azonosító!");
        
        //azonosító ellenőrzése
        $id = (int)$_POST["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő ársáv azonosító!");
        
        
        $DB = connect(true);
        
        //hozzárendelt termékek ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_termekar_hun WHERE arsavkategoria = :id");
        $sth->bindValue(":id", $id);
        $sth->execute();
        $termekar_count = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($termekar_count["count(*)"] > 0)
            throw new Exception("Az ársáv nem törölhető, mert vannak még hozzárendelt termék árak (összesen: " . $termekar_count["count(*)"] . " db)!");
        
        
        //adatok lekérdezése
        $sth = $DB->prepare("SELECT * FROM trs_shop_termekarsav_hun WHERE arsavid = :arsavid LIMIT 1");
        $sth->bindValue(":arsavid", $id);
        $sth->execute();
        $db_data = $sth->fetch(PDO::FETCH_ASSOC);
        
        if(empty($db_data))
            throw new Exception("Nem található a törölni kívánt ársáv!");
        
        //adatbázis törlése
        $DB->query("DELETE FROM trs_shop_termekarsav_hun WHERE arsavid=" . $id . " LIMIT 1");
        
        $DB = NULL;
        
        echo json_encode(array("status" => "success", "msg" => "Az ársáv sikeresen törölve"));
    }
    catch (Exception $e) {
        $DB = NULL;
        echo json_encode(array("status" => "error", "msg" => "Hiba történt! " . $e->getMessage()));
    }
}
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

$muvelet = filter_input(INPUT_POST, "muvelet", FILTER_UNSAFE_RAW);

if($muvelet == "fokategoria_torles"){
    shop_fokategoria_torles();
}
else{
    echo json_encode(array("status" => "error", "msg" => "Nem található feladat azonosító!"));
}
    


function shop_fokategoria_torles(){
    echo json_encode(array("status" => "success", "msg" => "backend válasz"));
    //azonosító ellenőrzése
    
    //hozzárendelt termékek ellenőrzése
    //képfájl törlése
    //adatbázis törlése
    
}
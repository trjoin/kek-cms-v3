<?php

if (isset($_REQUEST["opt"])) {

    if ($_REQUEST["opt"] == "ujcsoport")
        shop_uj_csoport_nezet();
    elseif ($_REQUEST["opt"] == "ujcsoportmentes")
        shop_uj_csoport_mentes();
    elseif ($_REQUEST["opt"] == "szerkesztes")
        shop_csoport_szerkeszt_nezet();
    elseif ($_REQUEST["opt"] == "szerkesztesmentes")
        shop_csoport_szerkeszt_mentes();
    else
        webshop_csoport_index();
}
else {
    //default működés hívása
    webshop_csoport_index();
}

function webshop_csoport_index() {
    //adatok lekérdezése
    $db = connect();
    $res = $db->query("SELECT * FROM trs_shop_csoport_hun");
    $db = NULL;
    //nézet megjelenítése
    ?>

    
    <div class="card">
        <div class="card-body">
            <h2>Termék csoportok listája</h2>
            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=webshop&thing=csoportok&opt=ujcsoport" class="btn btn-primary">+ Új hozzáadása</a>

            <div class="mt-3">
                <h2>Jelenlegi termék csoportok</h2>
                <?php
                if(empty($res)){
                    echo '<p class="text-danger">Jelenleg nem található egyetlen termék csoport sem!</p>';
                }
                else{ 
                ?>
                <div class="row mx-0 fw-bolder mb-2">
                    <div class="col-lg-4">Azonosító</div>
                    <div class="col-lg-4">Megnevezés</div>
                    <div class="col-lg-4">Műveletek</div>
                </div>
                <?php 
                $out = "";
                
                foreach($res as $row){ 
                    $out .= '<div class="row mx-0">';
                    $out .= '<div class="col-lg-4">' . $row["csopid"] . '</div>';
                    $out .= '<div class="col-lg-4">' . $row["csoportnev"] . '</div>';
                    $out .= '<div class="col-lg-4">';
                    $out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=webshop&thing=csoportok&opt=szerkesztes&id=' . $row["csopid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a>';
                    $out .= '<button type="button" class="btn btn-outline-danger px-2 py-1 ms-2 shop_csoport_torles" data-id="' . $row["csopid"] . '"><span class="mdi mdi-trash-can"></span> Törlés</button>';
                    $out .= '</div>';
                    $out .= '</div>';
                }
                echo $out;
                ?>
            </div>
        </div>
    </div>
    <?php 
       }
}

function shop_uj_csoport_nezet() {
    ?>
    <h2 class="text-muted font-weight-bold mb-2"> Új csoport hozzáadása </h2>
    <div class="card">
        <div class="card-body">

            <form action="/wp-admin/index.php?action=webshop&thing=csoportok&opt=ujcsoportmentes" method="post">
                <div class="form-group">
                    <label for="csoportnev">Megnevezés</label>
                    <input type="text" name="csoportnev" id="csoportnev" class="form-control" value="<?php echo (isset($_SESSION["new_webshop_group"]["csoportnev"]) ? $_SESSION["new_webshop_group"]["csoportnev"] : ""); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="csoportleiras">Leírás</label>
                    <textarea name="csoportleiras" id="csoportleiras" class="form-control" rows="5" required><?php echo (isset($_SESSION["new_webshop_group"]["csoportleiras"]) ? $_SESSION["new_webshop_group"]["csoportleiras"] : ""); ?></textarea>
                </div>
                <div>
                    <button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
                    <a class="btn btn-secondary ms-2" href="/wp-admin/index.php?action=webshop&thing=csoportok"><span class="mdi mdi-arrow-left"></span> Vissza</a>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function shop_uj_csoport_mentes() {
    $DB = NULL;
    try {
        //adatok tisztítása és ellenőrzése
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["new_webshop_group"] = $form_data;

        if (!isset($form_data["csoportnev"]) || empty($form_data["csoportnev"]))
            throw new Exception("A megnevezés kitöltése kötelező!");
        if (!isset($form_data["csoportleiras"]) || empty($form_data["csoportleiras"]))
            throw new Exception("A leírás kitöltése kötelező!");

        //adatok előkészítése
        global $mirol, $mire;
        $csoportfurl = str_replace($mirol, $mire, $form_data["csoportnev"]);
        $csoportfurl = to_linknew($csoportfurl);
        
        //kép feltöltése
        
        //adatok bejegyzése
        $DB = connect(true);
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_csoport_hun WHERE csoportnev = :csoportnev");
        $sth->bindValue(":csoportnev", $form_data["csoportnev"]);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű csoport, kérem válasszon másikat!");
        }
            

        $sql = "INSERT INTO trs_shop_csoport_hun (csoportnev, csoportleiras, csoportfurl) VALUES (:csoportnev, :csoportleiras, :csoportfurl)";
        $sth = $DB->prepare($sql);

        $sth->bindValue(":csoportnev", $form_data["csoportnev"]);
        $sth->bindValue(":csoportleiras", $form_data["csoportleiras"]);
        $sth->bindValue(":csoportfurl", $csoportfurl);

        $res = $sth->execute();

        $DB = NULL;

        //átirányítás
        unset($_SESSION["new_webshop_group"]);
        $_SESSION["php_notification"] = 'Termék csoport sikeresen hozzáadva!';

        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=csoportok");</script>';
        die(); //lefutott a program további része, ami gondot okozott
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_uj_csoport_nezet();
    }
}

function shop_csoport_szerkeszt_nezet(){
    $DB = NULL;
    try{
        if(!isset($_GET["id"]) || empty($_GET["id"]))
            throw new Exception("Hiányzó csoport azonosító!");
        
        $id = (int)$_GET["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő csoport azonosító!");
        
        $DB = connect(true);
        
        $sth = $DB->prepare("SELECT * FROM trs_shop_csoport_hun WHERE csopid = :csopid LIMIT 1");
        $sth->bindValue(":csopid", $id);
        $sth->execute();
        
        $res = $sth->fetchAll();
        
        $DB = NULL;
        
        if(empty($res))
            throw new Exception("Nem található adatok a megadott csoport azonosító alapján!");
        
        //form megjelenítése
        shop_csoport_szerkesz_form($res);
        
    }
    catch(Exception $e){
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        webshop_csoport_index();
    }
    
}

function shop_csoport_szerkesz_form($res = false){ ?>
    <h2 class="text-muted font-weight-bold mb-2"> Csoport szerkesztése </h2>
    <div class="card">
        <div class="card-body">
            <form action="/wp-admin/index.php?action=webshop&thing=csoportok&opt=szerkesztesmentes" method="post">
                <div class="form-group">
                    <label for="csoportnev">Megnevezés</label>
                    <input type="text" name="csoportnev" id="csoportnev" class="form-control" value="<?php echo (isset($_SESSION["edit_webshop_group"]["csoportnev"]) ? $_SESSION["edit_webshop_group"]["csoportnev"] : $res[0]["csoportnev"]); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="csoportleiras">Leírás</label>
                    <textarea name="csoportleiras" id="csoportleiras" class="form-control" rows="5" required><?php echo (isset($_SESSION["edit_webshop_group"]["csoportleiras"]) ? $_SESSION["edit_webshop_group"]["csoportleiras"] : $res[0]["csoportleiras"]); ?></textarea>
                </div>
                <div>
                    <input type="hidden" name="csopid" value="<?php echo (isset($_SESSION["edit_webshop_group"]["csopid"]) ? $_SESSION["edit_webshop_group"]["csopid"] : $res[0]["csopid"]); ?>" />
                    <button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
                    <a class="btn btn-secondary ms-2" href="/wp-admin/index.php?action=webshop&thing=csoportok"><span class="mdi mdi-arrow-left"></span> Vissza</a>
                </div>
            </form>
        </div>
    </div>
<?php
}

function shop_csoport_szerkeszt_mentes(){
    $DB = NULL;
    try {
        
        //adatok ellenőrzése és tisztítása
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["edit_webshop_group"] = $form_data;
        
        if(!isset($form_data["csopid"]) || empty($form_data["csopid"]))
            throw new Exception("Hiányzó kategória azonosító!");
        
        if(!isset($form_data["csoportnev"]) || empty($form_data["csoportnev"]))
            throw new Exception("Hiányzó csoport megnevezés!");
        
        if(!isset($form_data["csoportleiras"]) || empty($form_data["csoportleiras"]))
            throw new Exception("Hiányzó csoport leírás!");
        
        $id = (int)$form_data["csopid"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő csoport azonosító!");
        
        //adatok előkészítése
        global $mirol, $mire;
        $csoportfurl = str_replace($mirol, $mire, $form_data["csoportnev"]);
        $csoportfurl = to_linknew($csoportfurl);
        
        
        $DB = connect(true);
        
        //ellenőrzés
        $sth = $DB->prepare("SELECT * FROM trs_shop_csoport_hun WHERE csopid = :csopid LIMIT 1");
        $sth->bindValue(":csopid", $id);
        $sth->execute();
        $db_data = $sth->fetchAll();
        
        if(empty($db_data))
            throw new Exception("Nem található a módosítani kívánt csoport!");
        
        //a módosítitt megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_csoport_hun WHERE csoportnev = :csoportnev AND csopid <> :csopid");
        $sth->bindValue(":csoportnev", $form_data["csoportnev"]);
        $sth->bindValue(":csopid", $id);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű csoport, kérem válasszon másikat!");
        }
        
        //adatbázis adatok frissítése
        $sth = $DB->prepare("UPDATE trs_shop_csoport_hun SET csoportnev=:csoportnev, csoportleiras=:csoportleiras, csoportfurl=:csoportfurl  WHERE csopid = :csopid LIMIT 1");
        $sth->bindValue(":csoportnev", $form_data["csoportnev"]);
        $sth->bindValue(":csoportleiras", $form_data["csoportleiras"]);
        $sth->bindValue(":csoportfurl", $csoportfurl);
        $sth->bindValue(":csopid", $id);
        $sth->execute();
        
        $DB = NULL;
        unset($_SESSION["edit_webshop_group"]);
        
        //átirányítás a lista oldalra
        $_SESSION["php_notification"] = 'Termék csport sikeresen módosítva!';
        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=csoportok");</script>';
        die();
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_csoport_szerkesz_form();
    }   
}
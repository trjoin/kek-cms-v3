<?php

if (isset($_REQUEST["opt"])) {

    if ($_REQUEST["opt"] == "ujarsav")
        shop_uj_arsav_nezet();
    elseif ($_REQUEST["opt"] == "ujarsavmentes")
        shop_uj_arsav_mentes();
    elseif ($_REQUEST["opt"] == "szerkesztes")
        shop_arsav_szerkeszt_nezet();
    elseif ($_REQUEST["opt"] == "szerkesztesmentes")
        shop_arsav_szerkeszt_mentes();
    else
        webshop_arsav_index();
}
else {
    //default működés hívása
    webshop_arsav_index();
}

function webshop_arsav_index() {
    //adatok lekérdezése
    $db = connect();
    $res = $db->query("SELECT * FROM trs_shop_termekarsav_hun");
    $db = NULL;
    //nézet megjelenítése
    ?>

    
    <div class="card">
        <div class="card-body">
            <h2>Termék ársávok listája</h2>
            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=webshop&thing=arsavok&opt=ujarsav" class="btn btn-primary">+ Új termék ársáv</a>

            <div class="mt-3">
                <h2>Jelenlegi termék ársávok</h2>
                <?php
                if(empty($res)){
                    echo '<p class="text-danger">Jelenleg nem található egyetlen termék ársáv sem!</p>';
                }
                else{ 
                ?>
                <div class="row mx-0 fw-bolder mb-2">
                    <div class="col-lg-3">Azonosító</div>
                    <div class="col-lg-3">Megnevezés</div>
                    <div class="col-lg-3">Aktív</div>
                    <div class="col-lg-3">Műveletek</div>
                </div>
                <?php 
                $out = "";
                
                foreach($res as $row){ 
                    $out .= '<div class="row mx-0">';
                    $out .= '<div class="col-lg-3">' . $row["arsavid"] . '</div>';
                    $out .= '<div class="col-lg-3">' . $row["arsavnev"] . '</div>';
                    $out .= '<div class="col-lg-3">' . $row["arsavaktiv"] . '</div>';
                    $out .= '<div class="col-lg-3">';
                    $out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=webshop&thing=arsavok&opt=szerkesztes&id=' . $row["arsavid"] . '" class="btn btn-outline-primary px-2 py-1">Szerkesztés</a>';
                    $out .= '<button type="button" class="btn btn-outline-danger px-2 py-1 ms-2 shop_arsav_torles" data-id="' . $row["arsavid"] . '">Törlés</button>';
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

function shop_uj_arsav_nezet() {
    ?>
    <h2 class="text-muted font-weight-bold mb-2"> Új ársáv hozzáadása </h2>
    <div class="card">
        <div class="card-body">

            <form action="/wp-admin/index.php?action=webshop&thing=arsavok&opt=ujarsavmentes" method="post">
                <div class="form-group">
                    <label for="arsavnev">Megnevezés</label>
                    <input type="text" name="arsavnev" id="arsavnev" class="form-control" value="<?php echo (isset($_SESSION["new_webshop_arsav"]["arsavnev"]) ? $_SESSION["new_webshop_arsav"]["arsavnev"] : ""); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="arsavaktiv">Elérhetőség</label>
                    <select name="arsavaktiv" id="arsavaktiv" class="form-select">
                        <?php 
                            $selected = "1";
                            if(isset($_SESSION["new_webshop_arsav"]["arsavaktiv"]))
                                $selected = $_SESSION["new_webshop_arsav"]["arsavaktiv"];
                        ?>
                        <option value="1" <?php echo ($selected == "1" ? "selected" : ""); ?>>Aktív</option>
                        <option value="0" <?php echo ($selected == "0" ? "selected" : ""); ?>>Inaktív</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function shop_uj_arsav_mentes() {
    $DB = NULL;
    try {
        //adatok tisztítása és ellenőrzése
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["new_webshop_arsav"] = $form_data;

        if (!isset($form_data["arsavnev"]) || empty($form_data["arsavnev"]))
            throw new Exception("A megnevezés kitöltése kötelező!");
        if (!isset($form_data["arsavaktiv"]))
            throw new Exception("Az ársáv állapot kitöltése kötelező!");

        
        $DB = connect(true);
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_termekarsav_hun WHERE arsavnev = :arsavnev");
        $sth->bindValue(":arsavnev", $form_data["arsavnev"]);
        $sth->execute();
        
        $megnevezes_count = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($megnevezes_count["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű ársáv, kérem válasszon másikat!");
        }
            
        //adatok bejegyzése
        $sql = "INSERT INTO trs_shop_termekarsav_hun (arsavnev, arsavaktiv) VALUES (:arsavnev, :arsavaktiv)";
        $sth = $DB->prepare($sql);

        $sth->bindValue(":arsavnev", $form_data["arsavnev"]);
        $sth->bindValue(":arsavaktiv", $form_data["arsavaktiv"]);

        $res = $sth->execute();

        $DB = NULL;

        //átirányítás
        unset($_SESSION["new_webshop_arsav"]);
        $_SESSION["php_notification"] = 'Termék ársáv sikeresen hozzáadva!';

        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=arsavok");</script>';
        die(); //lefutott a program további része, ami gondot okozott
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_uj_arsav_nezet();
    }
}

function shop_arsav_szerkeszt_nezet(){
    $DB = NULL;
    try{
        if(!isset($_GET["id"]) || empty($_GET["id"]))
            throw new Exception("Hiányzó ársáv azonosító!");
        
        $id = (int)$_GET["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő ársáv azonosító!");
        
        $DB = connect(true);
        
        $sth = $DB->prepare("SELECT * FROM trs_shop_termekarsav_hun WHERE arsavid = :arsavid LIMIT 1");
        $sth->bindValue(":arsavid", $id);
        $sth->execute();
        
        $res = $sth->fetch(PDO::FETCH_ASSOC);
        
        $DB = NULL;
        
        if(empty($res))
            throw new Exception("Nem található adatok a megadott ársáv azonosító alapján!");
        
        //form megjelenítése
        shop_arsav_szerkesz_form($res);
        
    }
    catch(Exception $e){
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        webshop_arsav_index();
    }
    
}

function shop_arsav_szerkesz_form($res = false){ ?>
    <h2 class="text-muted font-weight-bold mb-2"> Ásráv szerkesztése </h2>
    <div class="card">
        <div class="card-body">
            <form action="/wp-admin/index.php?action=webshop&thing=arsavok&opt=szerkesztesmentes" method="post">
                <div class="form-group">
                    <label for="arsavnev">Megnevezés</label>
                    <input type="text" name="arsavnev" id="arsavnev" class="form-control" value="<?php echo (isset($_SESSION["edit_webshop_arsav"]["arsavnev"]) ? $_SESSION["edit_webshop_arsav"]["arsavnev"] : $res["arsavnev"]); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="arsavaktiv">Elérhetőség</label>
                    <select name="arsavaktiv" id="arsavaktiv" class="form-select">
                        <?php 
                            $selected = "1";
                            if(isset($_SESSION["new_webshop_arsav"]["arsavaktiv"]))
                                $selected = $_SESSION["new_webshop_arsav"]["arsavaktiv"];
                            elseif(isset($res["arsavaktiv"]))
                                $selected = $res["arsavaktiv"];
                        ?>
                        <option value="1" <?php echo ($selected == "1" ? "selected" : ""); ?>>Aktív</option>
                        <option value="0" <?php echo ($selected == "0" ? "selected" : ""); ?>>Inaktív</option>
                    </select>
                </div>
                <div>
                    <input type="hidden" name="arsavid" value="<?php echo (isset($_SESSION["edit_webshop_arsav"]["arsavid"]) ? $_SESSION["edit_webshop_arsav"]["arsavid"] : $res["arsavid"]); ?>" />
                    <button class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
<?php
}

function shop_arsav_szerkeszt_mentes(){
    $DB = NULL;
    try {
        
        //adatok ellenőrzése és tisztítása
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["edit_webshop_arsav"] = $form_data;
        
        if(!isset($form_data["arsavid"]) || empty($form_data["arsavid"]))
            throw new Exception("Hiányzó ársáv azonosító!");
        
        if(!isset($form_data["arsavnev"]) || empty($form_data["arsavnev"]))
            throw new Exception("Hiányzó ársáv megnevezés!");
        
        if(!isset($form_data["arsavaktiv"]))
            throw new Exception("Hiányzó ársáv elérhetőség!");
        
        $id = (int)$form_data["arsavid"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő ársáv azonosító!");
        
        
        $DB = connect(true);
        
        //ellenőrzés
        $sth = $DB->prepare("SELECT * FROM trs_shop_termekarsav_hun WHERE arsavid = :arsavid LIMIT 1");
        $sth->bindValue(":arsavid", $id);
        $sth->execute();
        $db_data = $sth->fetchAll();
        
        if(empty($db_data))
            throw new Exception("Nem található a módosítani kívánt ársáv!");
        
        //a módosítitt megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_termekarsav_hun WHERE arsavnev = :arsavnev AND arsavid <> :arsavid");
        $sth->bindValue(":arsavnev", $form_data["arsavnev"]);
        $sth->bindValue(":arsavid", $id);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű ársáv, kérem válasszon másikat!");
        }
        
        //adatbázis adatok frissítése
        $sth = $DB->prepare("UPDATE trs_shop_termekarsav_hun SET arsavnev=:arsavnev, arsavaktiv=:arsavaktiv WHERE arsavid = :arsavid LIMIT 1");
        $sth->bindValue(":arsavnev", $form_data["arsavnev"]);
        $sth->bindValue(":arsavaktiv", $form_data["arsavaktiv"]);
        $sth->bindValue(":arsavid", $id);
        $sth->execute();
        
        $DB = NULL;
        unset($_SESSION["edit_webshop_arsav"]);
        
        //átirányítás a lista oldalra
        $_SESSION["php_notification"] = 'Termék ársáv sikeresen módosítva!';
        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=arsavok");</script>';
        die();
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_arsav_szerkesz_form();
    }   
}
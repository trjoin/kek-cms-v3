<?php


if (isset($_REQUEST["opt"])) {

    //új blog
    //blog lista
    //blog szerkesztés
    //blog törlése
    
    //blog kategóriák
    //új blog kategória
    //blog kategória szerkesztése
    //blog kategória törlése
    
    if ($_REQUEST["opt"] == "ujblogkategoria")
        shop_uj_blog_kategoria_nezet();
    elseif ($_REQUEST["opt"] == "ujgyartomentes")
        shop_uj_gyarto_mentes();
    elseif ($_REQUEST["opt"] == "szerkesztes")
        shop_gyarto_szerkeszt_nezet();
    elseif ($_REQUEST["opt"] == "szerkesztesmentes")
        shop_gyarto_szerkeszt_mentes();
    else
        webshop_blog_kategoria_index();
}
else {
    //default működés hívása
    webshop_blog_kategoria_index();
}

function webshop_blog_kategoria_index() {
    $DB = NULL;
    try{
    //adatok lekérdezése
    $DB = connect(true);
    $sth = $DB->query("SELECT * FROM " . prefix."_blogcat_".lang);
    $res = $sth->fetchAll();
    $DB = NULL;
    
    //nézet megjelenítése
    ?>
    <div class="card">
        <div class="card-body">
            <h2>Termék gyártók listája</h2>
            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=webshop&thing=gyartok&opt=ujgyarto" class="btn btn-primary">+ Új hozzáadása</a>

            <div class="mt-3">
                <h2>Jelenlegi gyártók</h2>
                <?php
                if(empty($res)){
                    echo '<p class="text-danger">Jelenleg nem található egyetlen gyártó sem!</p>';
                }
                else{ 
                ?>
                <div class="row mx-0 fw-bolder mb-2">
                    <div class="col-lg-4">Logó</div>
                    <div class="col-lg-4">Megnevezés</div>
                    <div class="col-lg-4">Műveletek</div>
                </div>
                <?php 
                $out = "";
                foreach($res as $row){ 
                    $out .= '<div class="row mx-0">';
                    $out .= '<div class="col-lg-4"><img src="/uploads/' . $row["gyartologo"] . '" class="img-fluid col-lg-6" /></div>';
                    $out .= '<div class="col-lg-4">' . $row["gyartonev"] . '</div>';
                    $out .= '<div class="col-lg-4">';
                    $out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=webshop&thing=gyartok&opt=szerkesztes&id=' . $row["gyartoid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a>';
                    $out .= '<button type="button" class="btn btn-outline-danger px-2 py-1 ms-2" data-id="' . $row["gyartoid"] . '"><span class="mdi mdi-trash-can"></span> Törlés</button>';
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
    catch(Exception $e){
        $DB = NULL;
        echo '<p class="text-danger">' . $e->getMessage() . '</p>';
    }
}

function shop_uj_gyarto_nezet() {
    ?>
    <h2 class="text-muted font-weight-bold mb-2"> Új gyártó hozzáadása </h2>
    <div class="card">
        <div class="card-body">

            <form action="/wp-admin/index.php?action=webshop&thing=gyartok&opt=ujgyartomentes" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="gyartonev">Megnevezés</label>
                    <input type="text" name="gyartonev" id="gyartonev" class="form-control" value="<?php echo (isset($_SESSION["new_product_producer"]["gyartonev"]) ? $_SESSION["new_product_producer"]["gyartonev"] : ""); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="gyartoleiras">Leírás</label>
                    <textarea name="gyartoleiras" id="gyartoleiras" class="form-control" rows="5" required><?php echo (isset($_SESSION["new_product_producer"]["gyartoleiras"]) ? $_SESSION["new_product_producer"]["gyartoleiras"] : ""); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="gyartologo">Gyártó kép</label>
                    <input type="file" name="gyartologo" id="gyartologo" class="form-control" accept=".jpg, .jpeg, .png" required />
                </div>
                <div>
                    <button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
                    <a class="btn btn-secondary ms-2" href="/wp-admin/index.php?action=webshop&thing=gyartok"><span class="mdi mdi-arrow-left"></span> Vissza</a>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function shop_uj_gyarto_mentes() {
    $DB = NULL;
    try {
        //adatok tisztítása és ellenőrzése
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["new_product_producer"] = $form_data;

        if (!isset($form_data["gyartonev"]) || empty($form_data["gyartonev"]))
            throw new Exception("A megnevezés kitöltése kötelező!");
        if (!isset($form_data["gyartoleiras"]) || empty($form_data["gyartoleiras"]))
            throw new Exception("A leírás kitöltése kötelező!");
        
        //kép adatok ellenőrzése
        if(!isset($_FILES["gyartologo"]) || $_FILES["gyartologo"]["size"] == 0)
            throw new Exception("Hiányzó gyártó logó kép!");
        
        if($_FILES["gyartologo"]["type"] != "image/jpeg" && $_FILES["gyartologo"]["type"] != "image/png")
            throw new Exception("Nem megengedett képformátum, kérem válasszon jpg vagy png képet!");
        
        //adatok előkészítése
        global $mirol, $mire;
        $gyartofurl = str_replace($mirol, $mire, $form_data["gyartonev"]);
        $gyartofurl = to_linknew($gyartofurl);
        
        
        //adatok bejegyzése
        $DB = connect(true);
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_gyarto_hun WHERE gyartonev = :gyartonev");
        $sth->bindValue(":gyartonev", $form_data["gyartonev"]);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű gyártó, kérem válasszon másikat!");
        }
        
        //kép feltöltése, ha sikeresek voltak az ellenőrzések
        $file_ext = substr($_FILES["gyartologo"]['name'], strripos($_FILES["gyartologo"]['name'], '.'));
        $gyartologo =  $gyartofurl . uniqid("-") . $file_ext;
        
        if(!move_uploaded_file($_FILES["gyartologo"]['tmp_name'], "../uploads/" . $gyartologo))
            throw new Exception("Hiba történt a képfeltöltés során!");

        $sql = "INSERT INTO trs_shop_gyarto_hun (gyartonev, gyartoleiras, gyartofurl, gyartologo) VALUES (:gyartonev, :gyartoleiras, :gyartofurl, :gyartologo)";
        $sth = $DB->prepare($sql);

        $sth->bindValue(":gyartonev", $form_data["gyartonev"]);
        $sth->bindValue(":gyartoleiras", $form_data["gyartoleiras"]);
        $sth->bindValue(":gyartofurl", $gyartofurl);
        $sth->bindValue(":gyartologo", $gyartologo);

        $res = $sth->execute();

        $DB = NULL;

        //átirányítás
        unset($_SESSION["new_product_producer"]);
        $_SESSION["php_notification"] = 'Termék gyártó sikeresen hozzáadva!';

        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=gyartok");</script>';
        die(); //lefutott a program további része, ami gondot okozott
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_uj_gyarto_nezet();
    }
}

function shop_gyarto_szerkeszt_nezet(){
    $DB = NULL;
    try{
        if(!isset($_GET["id"]) || empty($_GET["id"]))
            throw new Exception("Hiányzó gyártó azonosító!");
        
        $id = (int)$_GET["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő gyártó azonosító!");
        
        $DB = connect(true);
        
        $sth = $DB->prepare("SELECT * FROM trs_shop_gyarto_hun WHERE gyartoid = :gyartoid LIMIT 1");
        $sth->bindValue(":gyartoid", $id);
        $sth->execute();
        
        $res = $sth->fetchAll();
        
        if(empty($res))
            throw new Exception("Nem található adatok a megadott gyártó azonosító alapján!");
        
        $DB = NULL;
        
        //form megjelenítése
        shop_gyarto_szerkesz_form($res[0]);
        
    }
    catch(Exception $e){
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        webshop_gyarto_index();
    }
    
}

function shop_gyarto_szerkesz_form($db_data = false){ ?>
    <h2 class="text-muted font-weight-bold mb-2"> Gyártó szerkesztése </h2>
    <div class="card">
        <div class="card-body">
            <form action="/wp-admin/index.php?action=webshop&thing=gyartok&opt=szerkesztesmentes" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="gyartonev">Megnevezés</label>
                    <input type="text" name="gyartonev" id="gyartonev" class="form-control" value="<?php echo (isset($_SESSION["edit_product_producer"]["gyartonev"]) ? $_SESSION["edit_product_producer"]["gyartonev"] : $db_data["gyartonev"]); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="gyartoleiras">Leírás</label>
                    <textarea name="gyartoleiras" id="gyartoleiras" class="form-control" rows="5" required><?php echo (isset($_SESSION["edit_product_producer"]["gyartoleiras"]) ? $_SESSION["edit_product_producer"]["gyartoleiras"] : $db_data["gyartoleiras"]); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="gyartologo">Gyártó kép cseréje</label>
                    <input type="file" name="gyartologo" id="gyartologo" class="form-control" accept=".jpg, .jpeg, .png" />
                </div>
                <div>
                    <p>Jelenlegi gyártó logó</p>
                    <img src="/uploads/<?php echo (isset($_SESSION["edit_product_producer"]["current_image"]) ? $_SESSION["edit_product_producer"]["current_image"] : $db_data["gyartologo"]); ?>" class="col-lg-4 img-fluid" />
                </div>
                <div class="mt-2">
                    <input type="hidden" name="current_image" value="/<?php echo (isset($_SESSION["edit_product_producer"]["current_image"]) ? $_SESSION["edit_product_producer"]["current_image"] : $db_data["gyartologo"]); ?>" />
                    <input type="hidden" name="gyartoid" value="<?php echo (isset($_SESSION["edit_product_producer"]["gyartoid"]) ? $_SESSION["edit_product_producer"]["gyartoid"] : $db_data["gyartoid"]); ?>" />
                    <button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
                    <a class="btn btn-secondary ms-2" href="/wp-admin/index.php?action=webshop&thing=gyartok"><span class="mdi mdi-arrow-left"></span> Vissza</a>
                </div>
            </form>
        </div>
    </div>
<?php
}

function shop_gyarto_szerkeszt_mentes(){
    $DB = NULL;
    try {
        
        //adatok ellenőrzése és tisztítása
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["edit_product_producer"] = $form_data;
        
        if(!isset($form_data["gyartoid"]) || empty($form_data["gyartoid"]))
            throw new Exception("Hiányzó gyártó azonosító!");
        
        if(!isset($form_data["gyartonev"]) || empty($form_data["gyartonev"]))
            throw new Exception("Hiányzó gyártó megnevezés!");
        
        if(!isset($form_data["gyartoleiras"]) || empty($form_data["gyartoleiras"]))
            throw new Exception("Hiányzó gyártó leírás!");
        
        
        //adatok előkészítése
        $id = (int)$form_data["gyartoid"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő gyártó azonosító!");

        global $mirol, $mire;
        $gyartofurl = str_replace($mirol, $mire, $form_data["gyartonev"]);
        $gyartofurl = to_linknew($gyartofurl);
        
        
        //adatbázis műveletek
        $DB = connect(true);
        
        //ellenőrzés
        $sth = $DB->prepare("SELECT * FROM trs_shop_gyarto_hun WHERE gyartoid = :gyartoid LIMIT 1");
        $sth->bindValue(":gyartoid", $id);
        $sth->execute();
        $db_data = $sth->fetchAll();
        
        if(empty($db_data))
            throw new Exception("Nem található a módosítani kívánt gyártó!");
        
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_gyarto_hun WHERE gyartonev = :gyartonev AND gyartoid <> :gyartoid");
        $sth->bindValue(":gyartonev", $form_data["gyartonev"]);
        $sth->bindValue(":gyartoid", $id);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű gyártó, kérem válasszon másikat!");
        }
        
        //adatbázis adatok frissítése
        $sth = $DB->prepare("UPDATE trs_shop_gyarto_hun SET gyartonev=:gyartonev, gyartoleiras=:gyartoleiras, gyartofurl=:gyartofurl WHERE gyartoid = :gyartoid LIMIT 1");
        $sth->bindValue(":gyartonev", $form_data["gyartonev"]);
        $sth->bindValue(":gyartoleiras", $form_data["gyartoleiras"]);
        $sth->bindValue(":gyartofurl", $gyartofurl);
        
        $sth->bindValue(":gyartoid", $id);
        $sth->execute();
        
        //kép feltöltése, opcionális adat
        if(isset($_FILES["gyartologo"]) && $_FILES["gyartologo"]["size"] > 0){
            
            if($_FILES["gyartologo"]["type"] != "image/jpeg" && $_FILES["gyartologo"]["type"] != "image/png")
                throw new Exception("Nem megengedett képformátum, kérem válasszon jpg vagy png képet!");
            
            $gyartologo = "";
            $file_ext = substr($_FILES["gyartologo"]['name'], strripos($_FILES["gyartologo"]['name'], '.'));
            $gyartologo =  $gyartofurl . uniqid("-") . $file_ext;

            if(!move_uploaded_file($_FILES["gyartologo"]['tmp_name'], "../uploads/" . $gyartologo))
                throw new Exception("Hiba történt a képfeltöltés során!");

            //korábbi kép törlése
            $success = unlink("../uploads/" . $db_data[0]["gyartologo"]);

            if(!$success)
                throw new Exception("A korábbi gyártó kép (" . $db_data[0]["gyartologo"] . ") törlése sikertelen!");
            
            $sth = $DB->prepare("UPDATE trs_shop_gyarto_hun SET gyartologo=:gyartologo WHERE gyartoid = :gyartoid LIMIT 1");
            $sth->bindValue(":gyartologo", $gyartologo);
            $sth->bindValue(":gyartoid", $id);
            $sth->execute();
        }
        
        $DB = NULL;
        unset($_SESSION["edit_product_producer"]);
        
        //átirányítás a lista oldalra
        $_SESSION["php_notification"] = 'Termék gyártó sikeresen módosítva!';
        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=gyartok");</script>';
        die();
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_gyarto_szerkesz_form();
    }   
}

<?php
if (isset($_REQUEST["opt"])) {

    if ($_REQUEST["opt"] == "ujkategoria")
        shop_uj_kategoria_nezet();
    elseif ($_REQUEST["opt"] == "ujkategoriamentes")
        shop_uj_kategoria_mentes();
    elseif ($_REQUEST["opt"] == "szerkesztes")
        shop_kategoria_szerkeszt_nezet();
    elseif ($_REQUEST["opt"] == "szerkesztesmentes")
        shop_kategoria_szerkeszt_mentes();
    else
        webshop_kategoria_index();
}
else {
    //default működés hívása
    webshop_kategoria_index();
}

function webshop_kategoria_index() {
    $DB = NULL;
    try{
    //adatok lekérdezése
    $DB = connect(true);
    $sth = $DB->query("SELECT * FROM trs_shop_fokategoria_hun LEFT JOIN trs_shop_csoport_hun ON (trs_shop_fokategoria_hun.csoportid = trs_shop_csoport_hun.csopid)");
    $res = $sth->fetchAll();
    $DB = NULL;
    
    //nézet megjelenítése
    ?>
    <div class="card">
        <div class="card-body">
            <h2>Termék kategóriák listája</h2>
            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=webshop&thing=kategoriak&opt=ujkategoria" class="btn btn-primary">+ Új kategória</a>

            <div class="mt-3">
                <h2>Jelenlegi főkategóriák</h2>
                <?php
                if(empty($res)){
                    echo '<p class="text-danger">Jelenleg nem található egyetlen főkategória sem!</p>';
                }
                else{ 
                ?>
                <div class="row mx-0 fw-bolder mb-2">
                    <div class="col-lg-3">Kép</div>
                    <div class="col-lg-3">Megnevezés</div>
                    <div class="col-lg-3">Csoport</div>
                    <div class="col-lg-3">Műveletek</div>
                </div>
                <?php 
                $out = "";
                foreach($res as $row){ 
                    $out .= '<div class="row mx-0">';
                    $out .= '<div class="col-lg-3">a</div>';
                    $out .= '<div class="col-lg-3">' . $row["fkatnev"] . '</div>';
                    $out .= '<div class="col-lg-3">' . (!empty($row["csoportnev"]) ? $row["csoportnev"] : "-") . '</div>';
                    $out .= '<div class="col-lg-3">';
                    $out .= '<a href="' . $_SERVER["PHP_SELF"] . '?action=webshop&thing=kategoriak&opt=szerkesztes&id=' . $row["fkatid"] . '" class="btn btn-outline-primary px-2 py-1">Szerkesztés</a>';
                    $out .= '<button type="button" class="btn btn-outline-danger px-2 py-1 ms-2" data-id="' . $row["fkatid"] . '">Törlés</button>';
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

function shop_uj_kategoria_nezet() {
    $DB = NULL;
    try{
    //adatok lekérdezése
    $DB = connect(true);
    $sth = $DB->query("SELECT csopid, csoportnev FROM trs_shop_csoport_hun");
    $csoportok = $sth->fetchAll();
    $DB = NULL;
    
    if(empty($csoportok))
       throw new Exception("Adatbázis hiba történt a csoportok lekérdezése során, kérem próbálja újra!"); 
    
    //nézet megjelenítése
    ?>
    
    <h2 class="text-muted font-weight-bold mb-2"> Új kategória hozzáadása </h2>
    <div class="card">
        <div class="card-body">

            <form action="/wp-admin/index.php?action=webshop&thing=kategoriak&opt=ujkategoriamentes" method="post" enctype="multipart/formdata">
                <div class="form-group">
                    <label for="fkatnev">Megnevezés</label>
                    <input type="text" name="fkatnev" id="fkatnev" class="form-control" value="<?php echo (isset($_SESSION["new_product_category"]["fkatnev"]) ? $_SESSION["new_product_category"]["fkatnev"] : ""); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="fkatleiras">Leírás</label>
                    <textarea name="fkatleiras" id="fkatleiras" class="form-control" rows="5" required><?php echo (isset($_SESSION["new_product_category"]["fkatleiras"]) ? $_SESSION["new_product_category"]["fkatleiras"] : ""); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="csoportid">Csoport</label>
                    <select name="csoportid" id="csoportid"  class="form-control">
                        <?php
                        
                        $out = "";
                        
                        foreach($csoportok as $csoport){
                            $out .= '<option value="' . $csoport["csopid"] . '"';
                            
                            if(isset($_SESSION["new_product_category"]["csoportid"]) && $_SESSION["new_product_category"]["csoportid"] == $csoport["csopid"])
                                $out .= " selected";
                                
                            $out .= '>' . $csoport["csoportnev"] . '</option>';
                        }
                        
                        echo $out;
                        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fthumbnail">Kategória kép</label>
                    <input type="file" name="fthumbnail" id="fthumbnail" class="form-control" value="" />
                </div>
                <div>
                    
                    <button class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    }
    catch(Exception $e){
        $DB = NULL;
        echo '<p class="text-danger">' . $e->getMessage() . '</p>';
    }
}

function shop_uj_kategoria_mentes() {
    $DB = NULL;
    try {
        //adatok tisztítása és ellenőrzése
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["new_product_category"] = $form_data;

        if (!isset($form_data["fkatnev"]) || empty($form_data["fkatnev"]))
            throw new Exception("A megnevezés kitöltése kötelező!");
        if (!isset($form_data["fkatleiras"]) || empty($form_data["fkatleiras"]))
            throw new Exception("A leírás kitöltése kötelező!");
        if (!isset($form_data["csoportid"]) || empty($form_data["csoportid"]))
            throw new Exception("A csoport kiválasztása kötelező!");

        $form_data["csoportid"] = (int) $form_data["csoportid"];
        
        if(!preg_match("/^[0-9]+$/", $form_data["csoportid"]))
            throw new Exception("Nem megfelelő csoport azonosító!");
        
        //adatok előkészítése
        global $mirol, $mire;
        $fkatfurl = str_replace($mirol, $mire, $form_data["fkatnev"]);
        $fkatfurl = to_linknew($fkatfurl);
        
        //kép feltöltése
        
        //adatok bejegyzése
        $DB = connect(true);
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_fokategoria_hun WHERE fkatnev = :fkatnev");
        $sth->bindValue(":fkatnev", $form_data["fkatnev"]);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű főkategória, kérem válasszon másikat!");
        }
            

        $sql = "INSERT INTO trs_shop_fokategoria_hun (fkatnev, fkatleiras, fkatfurl, fthumbnail, csoportid) VALUES (:fkatnev, :fkatleiras, :fkatfurl, :fthumbnail, :csoportid)";
        $sth = $DB->prepare($sql);

        $sth->bindValue(":fkatnev", $form_data["fkatnev"]);
        $sth->bindValue(":fkatleiras", $form_data["fkatleiras"]);
        $sth->bindValue(":fkatfurl", $fkatfurl);
        $sth->bindValue(":fthumbnail", '1');
        $sth->bindValue(":csoportid", $form_data["csoportid"]);

        $res = $sth->execute();

        $DB = NULL;

        //átirányítás
        unset($_SESSION["new_product_category"]);
        $_SESSION["php_notification"] = 'Termék kategória sikeresen hozzáadva!';

        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=kategoriak");</script>';
        die(); //lefutott a program további része, ami gondot okozott
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_uj_kategoria_nezet();
    }
}

function shop_kategoria_szerkeszt_nezet(){
    $DB = NULL;
    try{
        if(!isset($_GET["id"]) || empty($_GET["id"]))
            throw new Exception("Hiányzó főkategória azonosító!");
        
        $id = (int)$_GET["id"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő főkategória azonosító!");
        
        $DB = connect(true);
        
        $sth = $DB->prepare("SELECT * FROM trs_shop_fokategoria_hun WHERE fkatid = :fkatid LIMIT 1");
        $sth->bindValue(":fkatid", $id);
        $sth->execute();
        
        $res = $sth->fetchAll();
        
        if(empty($res))
            throw new Exception("Nem található adatok a megadott főkategória azonosító alapján!");
        
        $sth = $DB->query("SELECT csopid, csoportnev FROM trs_shop_csoport_hun");
        $csoportok = $sth->fetchAll();
    
        if(empty($csoportok))
            throw new Exception("Adatbázis hiba történt a csoportok lekérdezése során, kérem próbálja újra!"); 
        
        $DB = NULL;
        
        
        $_SESSION["fokategoria_csoportok"] = $csoportok;
        
        //form megjelenítése
        shop_kategoria_szerkesz_form($res[0]);
        
    }
    catch(Exception $e){
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        webshop_kategoria_index();
    }
    
}

function shop_kategoria_szerkesz_form($db_data = false){ ?>
    <h2 class="text-muted font-weight-bold mb-2"> Kategória szerkesztése </h2>
    <div class="card">
        <div class="card-body">
            <form action="/wp-admin/index.php?action=webshop&thing=kategoriak&opt=szerkesztesmentes" method="post" enctype="multipart/formdata">
                <div class="form-group">
                    <label for="fkatnev">Megnevezés</label>
                    <input type="text" name="fkatnev" id="fkatnev" class="form-control" value="<?php echo (isset($_SESSION["edit_product_category"]["fkatnev"]) ? $_SESSION["edit_product_category"]["fkatnev"] : $db_data["fkatnev"]); ?>" maxlength="200" required />
                </div>
                <div class="form-group">
                    <label for="fkatleiras">Leírás</label>
                    <textarea name="fkatleiras" id="fkatleiras" class="form-control" rows="5" required><?php echo (isset($_SESSION["edit_product_category"]["fkatleiras"]) ? $_SESSION["edit_product_category"]["fkatleiras"] : $db_data["fkatleiras"]); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="csoportid">Csoport</label>
                    <select name="csoportid" id="csoportid" class="form-control">
                        <?php
                        
                        $out = "";
                        
                        foreach($_SESSION["fokategoria_csoportok"] as $csoport){
                            $out .= '<option value="' . $csoport["csopid"] . '"';
                            
                            if((isset($_SESSION["edit_product_category"]["csoportid"]) && $_SESSION["edit_product_category"]["csoportid"] == $csoport["csopid"])
                                || (isset($db_data["csoportid"]) && $db_data["csoportid"] == $csoport["csopid"]))
                                $out .= " selected";
                                
                            $out .= '>' . $csoport["csoportnev"] . '</option>';
                        }
                        
                        echo $out;
                        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="csoportid">Csoport</label>
                    <select name="csoportid" id="csoportid" class="form-control">
                        <?php
                        
                        $out = "";
                        
                        foreach($csoportok as $csoport){
                            $out .= '<option value="' . $csoport["csopid"] . '"';
                            
                            if((isset($_SESSION["edit_product_category"]["csoportid"]) && $_SESSION["edit_product_category"]["csoportid"] == $csoport["csopid"])
                                || (isset($res[0]["csoportid"]) && $res[0]["csoportid"] == $csoport["csopid"]))
                                $out .= " selected";
                                
                            $out .= '>' . $csoport["csoportnev"] . '</option>';
                        }
                        
                        echo $out;
                        
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fthumbnail">Kategória kép</label>
                    <input type="file" name="fthumbnail" id="fthumbnail" class="form-control" value="" />
                </div>
                <div>
                    <input type="hidden" name="fkatid" value="<?php echo (isset($_SESSION["edit_product_category"]["fkatid"]) ? $_SESSION["edit_product_category"]["fkatid"] : $db_data["fkatid"]); ?>" />
                    <button class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
<?php
}

function shop_kategoria_szerkeszt_mentes(){
    $DB = NULL;
    try {
        
        //adatok ellenőrzése és tisztítása
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["edit_product_category"] = $form_data;
        
        if(!isset($form_data["fkatid"]) || empty($form_data["fkatid"]))
            throw new Exception("Hiányzó kategória azonosító!");
        
        if(!isset($form_data["fkatnev"]) || empty($form_data["fkatnev"]))
            throw new Exception("Hiányzó kategória megnevezés!");
        
        if(!isset($form_data["fkatleiras"]) || empty($form_data["fkatleiras"]))
            throw new Exception("Hiányzó kategória leírás!");
        
        if(!isset($form_data["csoportid"]) || empty($form_data["csoportid"]))
            throw new Exception("Hiányzó csoport azonosító!");
        
        
        //adatok előkészítése
        $id = (int)$form_data["fkatid"];
        
        if(!preg_match("/^[0-9]+$/", $id))
            throw new Exception("Nem megfelelő kategória azonosító!");

        if(!preg_match("/^[0-9]+$/", $form_data["csoportid"]))
            throw new Exception("Nem megfelelő csoport azonosító!");
        
        global $mirol, $mire;
        $fkatfurl = str_replace($mirol, $mire, $form_data["fkatnev"]);
        $fkatfurl = to_linknew($fkatfurl);
        
        
        //adatbázis műveletek
        $DB = connect(true);
        
        //ellenőrzés
        $sth = $DB->prepare("SELECT * FROM trs_shop_fokategoria_hun WHERE fkatid = :fkatid LIMIT 1");
        $sth->bindValue(":fkatid", $id);
        $sth->execute();
        $db_data = $sth->fetchAll();
        
        if(empty($db_data))
            throw new Exception("Nem található a módosítani kívánt kategória!");
        
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM trs_shop_fokategoria_hun WHERE fkatnev = :fkatnev AND fkatid <> :fkatid");
        $sth->bindValue(":fkatnev", $form_data["fkatnev"]);
        $sth->bindValue(":fkatid", $id);
        $sth->execute();
        
        $megnevezes_count = $sth->fetchAll();
        
        if($megnevezes_count[0]["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű főkategória, kérem válasszon másikat!");
        }
        
        //adatbázis adatok frissítése
        $sth = $DB->prepare("UPDATE trs_shop_fokategoria_hun SET fkatnev=:fkatnev, fkatleiras=:fkatleiras, fkatfurl=:fkatfurl, csoportid=:csoportid WHERE fkatid = :fkatid LIMIT 1");
        $sth->bindValue(":fkatnev", $form_data["fkatnev"]);
        $sth->bindValue(":fkatleiras", $form_data["fkatleiras"]);
        $sth->bindValue(":fkatfurl", $fkatfurl);
        $sth->bindValue(":csoportid", $form_data["csoportid"]);
        $sth->bindValue(":fkatid", $id);
        $sth->execute();
        
        $DB = NULL;
        unset($_SESSION["edit_product_category"]);
        unset($_SESSION["fokategoria_csoportok"]);
        
        //átirányítás a lista oldalra
        $_SESSION["php_notification"] = 'Termék kategória sikeresen módosítva!';
        echo '<script>window.location.replace("/wp-admin/index.php?action=webshop&thing=kategoriak");</script>';
        die();
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_kategoria_szerkesz_form();
    }   
}

//
////kategória törlése
//if (isset($_GET["kategoriatorol"])) {
//    $torles = $pdo->query("delete from " . $elotag . "_shop_kategoriak where shop_kategoriaid='" . $_GET["kategoriatorol"] . "'");
//    if ($torles) {
//        echo "<h3 style='color:#00FF00;'>Sikeres kategóriatörlés!</h3>";
//        echo "<script>
//                                function atiranyit()
//                                {
//                                        location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//                                }
//                                ID = window.setTimeout('atiranyit();', 1*300);
//                        </script>";
//    }
//    else {
//        echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kategória törlése!</h3>";
//        echo "<script>
//                                function atiranyit()
//                                {
//                                        location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//                                }
//                                ID = window.setTimeout('atiranyit();', 1*300);
//                        </script>";
//    }
//}
////kategória hozzáadása végrehajtása
//elseif (isset($_POST["ujkategoria"])) {
//    $SafeFile4 = $_FILES["shop_kategkep"]["name"];
//    $SafeFile4 = strtolower($SafeFile4);
//    $SafeFile4 = str_replace("#", "_", $SafeFile4);
//    $SafeFile4 = str_replace("$", "_", $SafeFile4);
//    $SafeFile4 = str_replace("%", "_", $SafeFile4);
//    $SafeFile4 = str_replace("'", "_", $SafeFile4);
//    $SafeFile4 = str_replace(",", "_", $SafeFile4);
//    $SafeFile4 = str_replace("&", "_", $SafeFile4);
//    $SafeFile4 = str_replace("*", "_", $SafeFile4);
//    $SafeFile4 = str_replace("+", "_", $SafeFile4);
//    $SafeFile4 = str_replace("!", "_", $SafeFile4);
//    $SafeFile4 = str_replace("?", "_", $SafeFile4);
//    $SafeFile4 = str_replace("=", "_", $SafeFile4);
//    $SafeFile4 = str_replace("/", "_", $SafeFile4);
//    $SafeFile4 = str_replace("§", "_", $SafeFile4);
//    $SafeFile4 = str_replace("(", "_", $SafeFile4);
//    $SafeFile4 = str_replace(")", "_", $SafeFile4);
//    $SafeFile4 = str_replace("ö", "o", $SafeFile4);
//    $SafeFile4 = str_replace("ő", "o", $SafeFile4);
//    $SafeFile4 = str_replace("ó", "o", $SafeFile4);
//    $SafeFile4 = str_replace("ü", "u", $SafeFile4);
//    $SafeFile4 = str_replace("ű", "u", $SafeFile4);
//    $SafeFile4 = str_replace("ú", "u", $SafeFile4);
//    $SafeFile4 = str_replace("é", "e", $SafeFile4);
//    $SafeFile4 = str_replace("á", "a", $SafeFile4);
//    $SafeFile4 = str_replace("í", "i", $SafeFile4);
//    $SafeFile4 = str_replace("ö", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ö", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ő", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ó", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ü", "u", $SafeFile4);
//    $SafeFile4 = str_replace("Ű", "u", $SafeFile4);
//    $SafeFile4 = str_replace("Ú", "u", $SafeFile4);
//    $SafeFile4 = str_replace("É", "e", $SafeFile4);
//    $SafeFile4 = str_replace("Á", "a", $SafeFile4);
//    $SafeFile4 = str_replace("Í", "i", $SafeFile4);
//    $SafeFile4 = str_replace(" ", "_", $SafeFile4);
//
//    $datummost1 = getDate();
//    $datekeszit1 = mktime($datummost1["hours"], $datummost1["minutes"], $datummost1["seconds"], $datummost1["mon"], $datummost1["mday"], $datummost1["year"]);
//    $dazo2 = date("Ymdhms", $datekeszit1);
//
//    $fajlnev4 = "../shop/kateg/" . $dazo2 . "_" . $SafeFile4;
//    $kategkep = $dazo2 . "_" . $SafeFile4;
//
//    if (move_uploaded_file($_FILES["shop_kategkep"]["tmp_name"], $fajlnev4)) {
//        $kategoriament = $pdo->query("insert into " . $elotag . "_shop_kategoriak(shop_kategkep,shop_kategorianev) values('" . $kategkep . "','" . $_POST["shop_kategorianev"] . "')");
//        if ($kategoriament) {
//            echo "<h3 style='color:#00FF00;'>Sikeres kategória felvétel!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//        else {
//            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kategória mentése!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//    }
//    else {
//        $kategoriament = $pdo->query("insert into " . $elotag . "_shop_kategoriak (shop_kategkep,shop_kategorianev) values('nincskep.png','" . $_POST["shop_kategorianev"] . "')");
//        if ($kategoriament) {
//            echo "<h3 style='color:#00FF00;'>Sikeres kategória felvétel!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//        else {
//            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kategória mentése!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//    }
//}
//elseif (isset($_POST["kategoriamod"])) {
//    $SafeFile4 = $_FILES["shop_kategkep"]["name"];
//    $SafeFile4 = strtolower($SafeFile4);
//    $SafeFile4 = str_replace("#", "_", $SafeFile4);
//    $SafeFile4 = str_replace("$", "_", $SafeFile4);
//    $SafeFile4 = str_replace("%", "_", $SafeFile4);
//    $SafeFile4 = str_replace("'", "_", $SafeFile4);
//    $SafeFile4 = str_replace(",", "_", $SafeFile4);
//    $SafeFile4 = str_replace("&", "_", $SafeFile4);
//    $SafeFile4 = str_replace("*", "_", $SafeFile4);
//    $SafeFile4 = str_replace("+", "_", $SafeFile4);
//    $SafeFile4 = str_replace("!", "_", $SafeFile4);
//    $SafeFile4 = str_replace("?", "_", $SafeFile4);
//    $SafeFile4 = str_replace("=", "_", $SafeFile4);
//    $SafeFile4 = str_replace("/", "_", $SafeFile4);
//    $SafeFile4 = str_replace("§", "_", $SafeFile4);
//    $SafeFile4 = str_replace("(", "_", $SafeFile4);
//    $SafeFile4 = str_replace(")", "_", $SafeFile4);
//    $SafeFile4 = str_replace("ö", "o", $SafeFile4);
//    $SafeFile4 = str_replace("ő", "o", $SafeFile4);
//    $SafeFile4 = str_replace("ó", "o", $SafeFile4);
//    $SafeFile4 = str_replace("ü", "u", $SafeFile4);
//    $SafeFile4 = str_replace("ű", "u", $SafeFile4);
//    $SafeFile4 = str_replace("ú", "u", $SafeFile4);
//    $SafeFile4 = str_replace("é", "e", $SafeFile4);
//    $SafeFile4 = str_replace("á", "a", $SafeFile4);
//    $SafeFile4 = str_replace("í", "i", $SafeFile4);
//    $SafeFile4 = str_replace("ö", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ö", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ő", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ó", "o", $SafeFile4);
//    $SafeFile4 = str_replace("Ü", "u", $SafeFile4);
//    $SafeFile4 = str_replace("Ű", "u", $SafeFile4);
//    $SafeFile4 = str_replace("Ú", "u", $SafeFile4);
//    $SafeFile4 = str_replace("É", "e", $SafeFile4);
//    $SafeFile4 = str_replace("Á", "a", $SafeFile4);
//    $SafeFile4 = str_replace("Í", "i", $SafeFile4);
//    $SafeFile4 = str_replace(" ", "_", $SafeFile4);
//
//    $datummost1 = getDate();
//    $datekeszit1 = mktime($datummost1["hours"], $datummost1["minutes"], $datummost1["seconds"], $datummost1["mon"], $datummost1["mday"], $datummost1["year"]);
//    $dazo2 = date("Ymdhms", $datekeszit1);
//
//    $fajlnev4 = "../shop/kateg/" . $dazo2 . "_" . $SafeFile4;
//    $kategkep = $dazo2 . "_" . $SafeFile4;
//
//    if (move_uploaded_file($_FILES["shop_kategkep"]["tmp_name"], $fajlnev4)) {
//        $kategoriafrissit = $pdo->query("update " . $elotag . "_shop_kategoriak set shop_kategkep='" . $kategkep . "',shop_kategorianev='" . $_POST["shop_kategorianev"] . "' where shop_kategoriaid='" . $_POST["kategoriamod"] . "'");
//        if ($kategoriafrissit) {
//            echo "<h3 style='color:#00FF00;'>Sikeres kategória frissítés!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//        else {
//            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kategória frissítése!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//    }
//    else {
//        $kategoriafrissit = $pdo->query("update " . $elotag . "_shop_kategoriak set shop_kategorianev='" . $_POST["shop_kategorianev"] . "' where shop_kategoriaid='" . $_POST["kategoriamod"] . "'");
//        if ($kategoriafrissit) {
//            echo "<h3 style='color:#00FF00;'>Sikeres kategória frissítés!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//        else {
//            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kategória frissítése!</h3>";
//            echo "<script>
//						function atiranyit()
//						{
//							location.href = 'index.php?lng=hun&page=shop&kategoriak=y';
//						}
//						ID = window.setTimeout('atiranyit();', 1*300);
//					</script>";
//        }
//    }
//}
////kategória szerkesztése űrlap
//elseif (isset($_GET["kategoriamod"])) {
//    $kategoria = $pdo->query("select * from " . $elotag . "_shop_kategoriak where shop_kategoriaid='" . $_GET["kategoriamod"] . "'");
//    $adat = $kategoria->fetch();
//    echo "<h2>KATEGÓRIA MÓDOSÍTÁSA</h2>";
//    echo "<form name='kategoriamod' method='POST' action='index.php?lng=" . $webaktlang . "&page=shop' enctype='multipart/form-data'>
//					<input type='hidden' name='kategoriamod' id='kategoriamod' value='" . $_GET["kategoriamod"] . "'>
//					<b>Kategória neve:</b><br /><input type='text' name='shop_kategorianev' id='shop_kategorianev' style='width:200px;' value='" . $adat["shop_kategorianev"] . "' required><br /><br />
//					<b>Kategória kisképe:</b><br /><input type='file' name='shop_kategkep' id='shop_kategkep'><br /><br />";
//    echo "		<input type='submit' id='kategoriamentes' name='kategoriamentes' value=' KATEGÓRIA FRISSÍTÉSE ' class='btn btn-large btn-secondary'>
//				</form>";
//}
//
//if (isset($_GET["kategoriak"])) { //kategóriák
//    $osszes = $pdo->query("select * from " . $elotag . "_shop_kategoriak");
//    echo "<h3>KATEGÓRIA LISTA</h3>
//					<a href='index.php?lng=" . $webaktlang . "&page=shop&ujkategoria=1' class='btn'>+ hozzáadás &raquo;</a>
//					<br /><br />
//					<table id='datatables' class='display'>
//						<thead>
//							<tr>
//								<th>Kategória ID</th>
//								<th>Kategória név</th>
//								<th>Művelet</th>
//							</tr>
//							<tr>
//								<th>Kategória ID</th>
//								<th>Kategória név</th>
//								<th>Művelet</th>
//							</tr>
//						</thead><tbody>";
//    while ($row = $osszes->fetch()) {
//        echo "<tr>
//							<td align='right'>" . $row['shop_kategoriaid'] . "</td>
//							<td>" . $row['shop_kategorianev'] . "</td>
//							<td align='center'><a href='index.php?lng=" . $webaktlang . "&page=shop&ksz=1&kategoriamod=" . $row["shop_kategoriaid"] . "' class='btn'>módosít</a> 
//								<a href='index.php?lng=" . $webaktlang . "&page=shop&ksz=1&kategoriatorol=" . $row["shop_kategoriaid"] . "' class='btn' onclick='return confirm(\"Biztosan törlöd ezt a kategóriát?\")'>töröl</a></td>
//					   </tr>";
//    }
//    echo "</tbody>
//				</table>";
//}
//
//if (isset($_GET["ujkategoria"])) {
//    echo "<h2>ÚJ KATEGÓRIA FELVÉTELE</h2>";
//    echo "<form name='ujkategoria' method='POST' action='index.php?lng=" . $webaktlang . "&page=shop' enctype='multipart/form-data'>
//					<input type='hidden' name='ujkategoria' id='ujkategoria' value='" . $_GET["ujkategoria"] . "'>
//					<b>Új kategória neve:</b><br /><input type='text' name='shop_kategorianev' id='shop_kategorianev' style='width:200px;' required><br /><br />
//					<b>Kategória kisképe:</b><br /><input type='file' name='shop_kategkep' id='shop_kategkep'><br /><br />
//					<input type='submit' id='kategoriamentes' name='kategoriamentes' value=' KATEGÓRIA MENTÉSE ' class='btn btn-large btn-secondary'><br />
//				</form>";
//}
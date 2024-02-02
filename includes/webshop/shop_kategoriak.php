<?php

if (isset($_REQUEST["opt"])) {

    if ($_REQUEST["opt"] == "ujkategoria")
        view_uj_kategoria_page();
    elseif ($_REQUEST["opt"] == "ujkategoriamentes")
        save_uj_kategoria();
    else
        view_webshop_kategoria_index();
}
else {
    //default működés hívása
    view_webshop_kategoria_index();
}

function view_webshop_kategoria_index() {

    //adatok lekérdezése
    //nézet megjelenítése
    echo '<div class="">
    <h2>Termék kategóriák listája</h2>
    <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=ujkategoria" class="btn btn-primary">+ Új kategória</a>
</div>
<div class="">
    <h2>Jelenlegi főkategóriák</h2>
    <div class="row mx-0">
        <div class="col-lg-4">Kép</div>
        <div class="col-lg-4">Megnevezés</div>
        <div class="col-lg-4">Műveletek</div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-4">a</div>
        <div class="col-lg-4">hgfadhfadhafd</div>
        <div class="col-lg-4">
            <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=szerkesztes" class="btn btn-primary px-2 py-1">Szerkesztés</a>
            <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=torles" class="btn btn-danger px-2 py-1">Törlés</a>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-4">a</div>
        <div class="col-lg-4">hgfadhfadhafd</div>
        <div class="col-lg-4">
            <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=szerkesztes" class="btn btn-primary px-2 py-1">Szerkesztés</a>
            <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=torles" class="btn btn-danger px-2 py-1">Törlés</a>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-4">a</div>
        <div class="col-lg-4">hgfadhfadhafd</div>
        <div class="col-lg-4">
            <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=szerkesztes" class="btn btn-primary px-2 py-1">Szerkesztés</a>
            <a href="' . $_SERVER["php_self"] . '?action=webshop&thing=kategoriak&opt=torles" class="btn btn-danger px-2 py-1">Törlés</a>
        </div>
    </div>
</div>';
}

function view_uj_kategoria_page() {
    ?>
    <div class="">
        <h2>Új kategória hozzáadása</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=webshop&thing=kategoriak&opt=ujkategoriamentes" method="post" enctype="multipart/formdata">
            <div class="mb-2">
                <label class="form-label" for="fkatnev">Megnevezés</label>
                <input type="text" name="fkatnev" id="fkatnev" class="form-control" value="" maxlength="200" required />
            </div>
            <div class="mb-2">
                <label class="form-label" for="fkatleiras">Leírás</label>
                <textarea name="fkatleiras" id="fkatleiras" class="form-control" rows="5"></textarea>
            </div>
            <div class="mb-2">
                <label class="form-label" for="fthumbnail">Kategória kép</label>
                <input type="file" name="fthumbnail" id="fthumbnail" class="form-control" />
            </div>
            <div>
                <input type="hidden" name="csoportid" value="1" />
                <button class="btn btn-primary">Mentés</button>
            </div>
        </form>
    </div>
<?php }

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
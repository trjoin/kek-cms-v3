<?php
$DB = connect(true);
if (isset($_GET["termektorol"])) {
    $torles = $DB->query("delete from " . $elotag . "_shop_termek where t_id=" . $_GET["termektorol"]);
    if ($torles) {
        echo "<h3 style='color:#00FF00;'>Sikeres terméktörlés!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*300);
				</script>";
    }
    else {
        echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék törlése!</h3>";
        echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*300);
				</script>";
    }
}
elseif (isset($_POST["ujtermek"])) {
    $datummost = getDate();
    $datekeszit = mktime($datummost["hours"], $datummost["minutes"], $datummost["seconds"], $datummost["mon"], $datummost["mday"], $datummost["year"]);
    $dazo = date("Ymdhms", $datekeszit);

    //főkép, mert kötelező!
    $SafeFile = $_FILES["t_fkep"]["name"];
    $SafeFile = strtolower($SafeFile);
    $SafeFile = str_replace($mirol, $mire, $SafeFile);

    $fajlnev = "../shop/" . $dazo . "_" . $SafeFile;
    $fokep = $dazo . "_" . $SafeFile;

    //termék PDF, ha van!
    if (isset($_POST["pdfis"]) AND $_POST["pdfis"] == "yes") {
        $SafePDF = $_FILES["t_pdf"]["name"];
        $SafePDF = strtolower($SafePDF);
        $SafePDF = str_replace($mirol, $mire, $SafePDF);
        ;

        //időbélyeg készítée
        $datummost = getDate();
        $datekeszit = mktime($datummost["hours"], $datummost["minutes"], $datummost["seconds"], $datummost["mon"], $datummost["mday"], $datummost["year"]);
        $dazo = date("Ymdhms", $datekeszit);

        $pdfnev = "../shop/" . $dazo . "_" . $SafePDF;
        $pdfdok = $dazo . "_" . $SafePDF;

        if (move_uploaded_file($_FILES["t_fkep"]["tmp_name"], $fajlnev) AND move_uploaded_file($_FILES["t_pdf"]["tmp_name"], $pdfnev)) {
            $termekment = $DB->query("insert into " . $elotag . "_shop_termek (t_gyarto,t_nev,t_ar,t_kategoria,t_fkep,t_kleiras,t_nleiras,t_datum,t_pdf) values('" . $_POST["t_gyarto"] . "','" . $_POST["t_nev"] . "','" . $_POST["t_ar"] . "','" . $_POST["t_kategoria"] . "','" . $fokep . "','" . $_POST["t_kleiras"] . "','" . $_POST["t_nleiras"] . "',now(),'" . $pdfdok . "')");
            if ($termekment) {
                echo "<h3 style='color:#00FF00;'>Sikeres termékfelvétel!</h3>";
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék mentése - SQL Hiba történt!</h3>";
                echo "<a href='javascript:history.go(-1)'>próbálja újra a hiba kijavításával... &raquo;</a>";
            }
        }
        else {
            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kép feltöltése!</h3>";
            echo "<a href='javascript:history.go(-1)'>próbálja újra a hiba kijavításával... &raquo;</a>";
        }
    }
    else {
        if (move_uploaded_file($_FILES["t_fkep"]["tmp_name"], $fajlnev)) {
            $termekment = $DB->query("insert into " . $elotag . "_shop_termek (t_gyarto,t_nev,t_ar,t_kategoria,t_fkep,t_kleiras,t_nleiras,t_datum) values('" . $_POST["t_gyarto"] . "','" . $_POST["t_nev"] . "','" . $_POST["t_ar"] . "','" . $_POST["t_kategoria"] . "','" . $fokep . "','" . $_POST["t_kleiras"] . "','" . $_POST["t_nleiras"] . "',now())");
            if ($termekment) {
                echo "<h3 style='color:#00FF00;'>Sikeres termékfelvétel!</h3><a href='index.php?lng=hun&page=shop&ujtermek=y'><b>ISMÉT ÚJ TERMÉK FELVÉTELE &raquo;</b></a>";
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék mentése - SQL Hiba történt!</h3>";
                echo "<a href='javascript:history.go(-1)'>próbálja újra a hiba kijavításával... &raquo;</a>";
            }
        }
        else {
            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kép feltöltése!</h3>";
            echo "<a href='javascript:history.go(-1)'>próbálja újra a hiba kijavításával... &raquo;</a>";
        }
    }
}
elseif (isset($_POST["termekmod"])) {
    if (isset($_POST["pdfis"]) AND $_POST["pdfis"] == "yes") {
        $SafePDF = $_FILES["t_pdf"]["name"];
        $SafePDF = strtolower($SafePDF);
        $SafePDF = str_replace("#", "_", $SafePDF);
        $SafePDF = str_replace("$", "_", $SafePDF);
        $SafePDF = str_replace("%", "_", $SafePDF);
        $SafePDF = str_replace("'", "_", $SafePDF);
        $SafePDF = str_replace(",", "_", $SafePDF);
        $SafePDF = str_replace("&", "_", $SafePDF);
        $SafePDF = str_replace("*", "_", $SafePDF);
        $SafePDF = str_replace("+", "_", $SafePDF);
        $SafePDF = str_replace("!", "_", $SafePDF);
        $SafePDF = str_replace("?", "_", $SafePDF);
        $SafePDF = str_replace("=", "_", $SafePDF);
        $SafePDF = str_replace("/", "_", $SafePDF);
        $SafePDF = str_replace("§", "_", $SafePDF);
        $SafePDF = str_replace("(", "_", $SafePDF);
        $SafePDF = str_replace(")", "_", $SafePDF);
        $SafePDF = str_replace("ö", "o", $SafePDF);
        $SafePDF = str_replace("ő", "o", $SafePDF);
        $SafePDF = str_replace("ó", "o", $SafePDF);
        $SafePDF = str_replace("ü", "u", $SafePDF);
        $SafePDF = str_replace("ű", "u", $SafePDF);
        $SafePDF = str_replace("ú", "u", $SafePDF);
        $SafePDF = str_replace("é", "e", $SafePDF);
        $SafePDF = str_replace("á", "a", $SafePDF);
        $SafePDF = str_replace("í", "i", $SafePDF);
        $SafePDF = str_replace("ö", "o", $SafePDF);
        $SafePDF = str_replace("Ö", "o", $SafePDF);
        $SafePDF = str_replace("Ő", "o", $SafePDF);
        $SafePDF = str_replace("Ó", "o", $SafePDF);
        $SafePDF = str_replace("Ü", "u", $SafePDF);
        $SafePDF = str_replace("Ű", "u", $SafePDF);
        $SafePDF = str_replace("Ú", "u", $SafePDF);
        $SafePDF = str_replace("É", "e", $SafePDF);
        $SafePDF = str_replace("Á", "a", $SafePDF);
        $SafePDF = str_replace("Í", "i", $SafePDF);
        $SafePDF = str_replace(" ", "_", $SafePDF);

        //időbélyeg készítée
        $datummost = getDate();
        $datekeszit = mktime($datummost["hours"], $datummost["minutes"], $datummost["seconds"], $datummost["mon"], $datummost["mday"], $datummost["year"]);
        $dazo = date("Ymdhms", $datekeszit);

        $pdfnev = "../shop/" . $dazo . "_" . $SafePDF;
        $pdfdok = $dazo . "_" . $SafePDF;

        if (move_uploaded_file($_FILES["t_pdf"]["tmp_name"], $pdfnev)) {
            $termekfrissitpdf = $DB->query("update " . $elotag . "_shop_termek set t_pdf='" . $pdfdok . "' where t_id='" . $_POST["termekmod"] . "'");
            if ($termekfrissitpdf) {
                echo "<h3 style='color:#00FF00;'>Sikeres PDF feltöltés!</h3>";
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a PDF mentése - SQL Hiba történt!</h3>";
            }
        }
        else {
            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a PDF feltöltése!</h3>";
        }
    }
    if (isset($_POST["tobbkepetis"]) AND $_POST["tobbkepetis"] == "yes" AND isset($_POST["fkepetis"]) AND $_POST["fkepetis"] == "yes") {
        $sikeres = 0; // sikeresen feltöltött és mentett fájl
        $hibaszoveg = ""; //hibák szövege
        $osszesfajl = ""; //összes feltölteni kivánt fájl
        $vanfokep = "nincs"; //főkép alapértelmezetten: nincs!
        //további képek feltöltögetése
        for ($i = 0; $i < count($_FILES['t_kepek']['name']); $i++) {
            $SafeFile = $_FILES['t_kepek']['name'][$i];
            $SafeFile = strtolower($SafeFile);
            $SafeFile = str_replace("#", "_", $SafeFile);
            $SafeFile = str_replace("$", "_", $SafeFile);
            $SafeFile = str_replace("%", "_", $SafeFile);
            $SafeFile = str_replace("'", "_", $SafeFile);
            $SafeFile = str_replace(",", "_", $SafeFile);
            $SafeFile = str_replace("&", "_", $SafeFile);
            $SafeFile = str_replace("*", "_", $SafeFile);
            $SafeFile = str_replace("+", "_", $SafeFile);
            $SafeFile = str_replace("!", "_", $SafeFile);
            $SafeFile = str_replace("?", "_", $SafeFile);
            $SafeFile = str_replace("=", "_", $SafeFile);
            $SafeFile = str_replace("/", "_", $SafeFile);
            $SafeFile = str_replace("§", "_", $SafeFile);
            $SafeFile = str_replace("(", "_", $SafeFile);
            $SafeFile = str_replace(")", "_", $SafeFile);
            $SafeFile = str_replace("ö", "o", $SafeFile);
            $SafeFile = str_replace("ő", "o", $SafeFile);
            $SafeFile = str_replace("ó", "o", $SafeFile);
            $SafeFile = str_replace("ü", "u", $SafeFile);
            $SafeFile = str_replace("ű", "u", $SafeFile);
            $SafeFile = str_replace("ú", "u", $SafeFile);
            $SafeFile = str_replace("é", "e", $SafeFile);
            $SafeFile = str_replace("á", "a", $SafeFile);
            $SafeFile = str_replace("í", "i", $SafeFile);
            $SafeFile = str_replace("Ö", "o", $SafeFile);
            $SafeFile = str_replace("Ő", "o", $SafeFile);
            $SafeFile = str_replace("Ó", "o", $SafeFile);
            $SafeFile = str_replace("Ü", "u", $SafeFile);
            $SafeFile = str_replace("Ű", "u", $SafeFile);
            $SafeFile = str_replace("Ú", "u", $SafeFile);
            $SafeFile = str_replace("É", "e", $SafeFile);
            $SafeFile = str_replace("Á", "a", $SafeFile);
            $SafeFile = str_replace("Í", "i", $SafeFile);
            $SafeFile = str_replace(" ", "_", $SafeFile);

            $ext = strtolower(substr(strrchr($SafeFile, "."), 1));

            if ($ext == "jpg" || $ext == "gif" || $ext == "bmp" || $ext == "png") {
                //időbélyeg készítée
                $datummost = getDate();
                $datekeszit = mktime($datummost["hours"], $datummost["minutes"], $datummost["seconds"], $datummost["mon"], $datummost["mday"], $datummost["year"]);
                $dazo = date("Ymdhms", $datekeszit);

                $fajlnev = "../shop/kepek/" . $dazo . "_" . $SafeFile;

                //fájlfeltöltés végrehajtása
                if (move_uploaded_file($_FILES['t_kepek']['tmp_name'][$i], $fajlnev)) {
                    $osszesfajl = $osszesfajl . "|" . $fajlnev;
                    $sikeres++;
                }
                else {
                    $hibaszoveg = $hibaszoveg . "Feltöltési hiba történt: " . $fajlnev . "&nbsp;<br />";
                }
            }
            else {
                $hibaszoveg = $hibaszoveg . "Fájlformátum hiba történt: " . $fajlnev . "&nbsp;<br />";
            }
        }
        //főkép feltöltése
        if (count($_FILES['t_fkep'])) {
            $SafeFile2 = $_FILES["t_fkep"]["name"];
            $SafeFile2 = strtolower($SafeFile2);
            $SafeFile2 = str_replace("#", "_", $SafeFile2);
            $SafeFile2 = str_replace("$", "_", $SafeFile2);
            $SafeFile2 = str_replace("%", "_", $SafeFile2);
            $SafeFile2 = str_replace("'", "_", $SafeFile2);
            $SafeFile2 = str_replace(",", "_", $SafeFile2);
            $SafeFile2 = str_replace("&", "_", $SafeFile2);
            $SafeFile2 = str_replace("*", "_", $SafeFile2);
            $SafeFile2 = str_replace("+", "_", $SafeFile2);
            $SafeFile2 = str_replace("!", "_", $SafeFile2);
            $SafeFile2 = str_replace("?", "_", $SafeFile2);
            $SafeFile2 = str_replace("=", "_", $SafeFile2);
            $SafeFile2 = str_replace("/", "_", $SafeFile2);
            $SafeFile2 = str_replace("§", "_", $SafeFile2);
            $SafeFile2 = str_replace("(", "_", $SafeFile2);
            $SafeFile2 = str_replace(")", "_", $SafeFile2);
            $SafeFile2 = str_replace("ö", "o", $SafeFile2);
            $SafeFile2 = str_replace("ő", "o", $SafeFile2);
            $SafeFile2 = str_replace("ó", "o", $SafeFile2);
            $SafeFile2 = str_replace("ü", "u", $SafeFile2);
            $SafeFile2 = str_replace("ű", "u", $SafeFile2);
            $SafeFile2 = str_replace("ú", "u", $SafeFile2);
            $SafeFile2 = str_replace("é", "e", $SafeFile2);
            $SafeFile2 = str_replace("á", "a", $SafeFile2);
            $SafeFile2 = str_replace("í", "i", $SafeFile2);
            $SafeFile2 = str_replace("ö", "o", $SafeFile2);
            $SafeFile2 = str_replace("Ö", "o", $SafeFile2);
            $SafeFile2 = str_replace("Ő", "o", $SafeFile2);
            $SafeFile2 = str_replace("Ó", "o", $SafeFile2);
            $SafeFile2 = str_replace("Ü", "u", $SafeFile2);
            $SafeFile2 = str_replace("Ű", "u", $SafeFile2);
            $SafeFile2 = str_replace("Ú", "u", $SafeFile2);
            $SafeFile2 = str_replace("É", "e", $SafeFile2);
            $SafeFile2 = str_replace("Á", "a", $SafeFile2);
            $SafeFile2 = str_replace("Í", "i", $SafeFile2);
            $SafeFile2 = str_replace(" ", "_", $SafeFile2);

            $ext2 = strtolower(substr(strrchr($SafeFile2, "."), 1));

            if ($ext2 == "jpg" || $ext2 == "gif" || $ext2 == "bmp" || $ext2 == "png") {
                $datummost1 = getDate();
                $datekeszit1 = mktime($datummost1["hours"], $datummost1["minutes"], $datummost1["seconds"], $datummost1["mon"], $datummost1["mday"], $datummost1["year"]);
                $dazo2 = date("Ymdhms", $datekeszit1);

                $fajlnev2 = "../shop/" . $dazo2 . "_" . $SafeFile2;
                $fokep = $dazo2 . "_" . $SafeFile2;

                if (move_uploaded_file($_FILES["t_fkep"]["tmp_name"], $fajlnev2)) {
                    $vanfokep = "van";
                }
                else {
                    $vanfokep = "nincs";
                }
            }
        }
        if ($sikeres >= 1 AND $vanfokep == "van") {
            $termekfrissit = $DB->query("update " . $elotag . "_shop_termek set t_gyarto='" . $_POST["t_gyarto"] . "',t_nev='" . $_POST["t_nev"] . "',t_kategoria='" . $_POST["t_kategoria"] . "',t_fkep='" . $fokep . "',t_kepek='" . $osszesfajl . "',t_ar='" . $_POST["t_ar"] . "',t_kleiras='" . $_POST["t_kleiras"] . "',t_nleiras='" . $_POST["t_nleiras"] . "' where t_id='" . $_POST["termekmod"] . "'");

            if ($termekfrissit) {
                echo "<h3 style='color:#00FF00;'>Sikeres termék frissítés!</h3>";
                echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése!</h3>";
                $hibaszoveg = $hibaszoveg . "SQL Hiba történt - főkép felöltést IGEN kért.<br />";
                echo "<script>
								function atiranyit()
								{
									location.href = 'index.php?lng=hun&page=shop';
								}
								ID = window.setTimeout('atiranyit();', 1*200);
							</script>";
            }
        }
        elseif ($sikeres >= 1 AND $vanfokep == "nincs") {
            $termekfrissit = $DB->query("update " . $elotag . "_shop_termek set t_gyarto='" . $_POST["t_gyarto"] . "',t_nev='" . $_POST["t_nev"] . "',t_kategoria='" . $_POST["t_kategoria"] . "',t_kepek='" . $osszesfajl . "',t_ar='" . $_POST["t_ar"] . "',t_kleiras='" . $_POST["t_kleiras"] . "',t_nleiras='" . $_POST["t_nleiras"] . "' where t_id='" . $_POST["termekmod"] . "'");

            if ($termekfrissit) {
                echo "<h3 style='color:#00FF00;'>Sikeres termék frissítés!</h3>";
                echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése!</h3>";
                $hibaszoveg = $hibaszoveg . "SQL Hiba történt - főkép felöltést NEM kért.<br />";
                echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
            }
        }
        else {
            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése (SQL HIBA)!</h3><br /><b>HIBÁK:</b><br />" . $hibaszoveg . "";
            echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
        }
    }
    elseif (isset($_POST["tobbkepetis"]) AND $_POST["tobbkepetis"] == "yes" AND !isset($_POST["fkepetis"]) AND $_POST["fkepetis"] != "yes") {
        $sikeres = 0; // sikeresen feltöltött és mentett fájl
        $hibaszoveg = ""; //hibák szövege
        $osszesfajl = ""; //összes feltölteni kivánt fájl
        $vanfokep = "nincs"; //főkép alapértelmezetten: nincs!
        //további képek feltöltögetése
        for ($i = 0; $i < count($_FILES['t_kepek']['name']); $i++) {
            $SafeFile = $_FILES['t_kepek']['name'][$i];
            $SafeFile = strtolower($SafeFile);
            $SafeFile = str_replace("#", "_", $SafeFile);
            $SafeFile = str_replace("$", "_", $SafeFile);
            $SafeFile = str_replace("%", "_", $SafeFile);
            $SafeFile = str_replace("'", "_", $SafeFile);
            $SafeFile = str_replace(",", "_", $SafeFile);
            $SafeFile = str_replace("&", "_", $SafeFile);
            $SafeFile = str_replace("*", "_", $SafeFile);
            $SafeFile = str_replace("+", "_", $SafeFile);
            $SafeFile = str_replace("!", "_", $SafeFile);
            $SafeFile = str_replace("?", "_", $SafeFile);
            $SafeFile = str_replace("=", "_", $SafeFile);
            $SafeFile = str_replace("/", "_", $SafeFile);
            $SafeFile = str_replace("§", "_", $SafeFile);
            $SafeFile = str_replace("(", "_", $SafeFile);
            $SafeFile = str_replace(")", "_", $SafeFile);
            $SafeFile = str_replace("ö", "o", $SafeFile);
            $SafeFile = str_replace("ő", "o", $SafeFile);
            $SafeFile = str_replace("ó", "o", $SafeFile);
            $SafeFile = str_replace("ü", "u", $SafeFile);
            $SafeFile = str_replace("ű", "u", $SafeFile);
            $SafeFile = str_replace("ú", "u", $SafeFile);
            $SafeFile = str_replace("é", "e", $SafeFile);
            $SafeFile = str_replace("á", "a", $SafeFile);
            $SafeFile = str_replace("í", "i", $SafeFile);
            $SafeFile = str_replace("Ö", "o", $SafeFile);
            $SafeFile = str_replace("Ő", "o", $SafeFile);
            $SafeFile = str_replace("Ó", "o", $SafeFile);
            $SafeFile = str_replace("Ü", "u", $SafeFile);
            $SafeFile = str_replace("Ű", "u", $SafeFile);
            $SafeFile = str_replace("Ú", "u", $SafeFile);
            $SafeFile = str_replace("É", "e", $SafeFile);
            $SafeFile = str_replace("Á", "a", $SafeFile);
            $SafeFile = str_replace("Í", "i", $SafeFile);
            $SafeFile = str_replace(" ", "_", $SafeFile);

            $ext = strtolower(substr(strrchr($SafeFile, "."), 1));

            if ($ext == "jpg" || $ext == "gif" || $ext == "bmp" || $ext == "png") {
                //időbélyeg készítée
                $datummost = getDate();
                $datekeszit = mktime($datummost["hours"], $datummost["minutes"], $datummost["seconds"], $datummost["mon"], $datummost["mday"], $datummost["year"]);
                $dazo = date("Ymdhms", $datekeszit);

                $fajlut = "../shop/kepek/" . $dazo . "_" . $SafeFile;
                $fajlnev = $dazo . "_" . $SafeFile;

                //fájlfeltöltés végrehajtása
                if (move_uploaded_file($_FILES['t_kepek']['tmp_name'][$i], $fajlut)) {
                    $osszesfajl = $osszesfajl . "|" . $fajlnev;
                    $sikeres++;
                }
                else {
                    $hibaszoveg = $hibaszoveg . "Feltöltési hiba történt: " . $fajlnev . "&nbsp;<br />";
                }
            }
            else {
                $hibaszoveg = $hibaszoveg . "Fájlformátum hiba történt: " . $fajlnev . "&nbsp;<br />";
            }
        }
        if ($sikeres >= 1 AND $vanfokep == "nincs") {
            $termekfrissit = $DB->query("update " . $elotag . "_shop_termek set t_gyarto='" . $_POST["t_gyarto"] . "',t_nev='" . $_POST["t_nev"] . "',t_kategoria='" . $_POST["t_kategoria"] . "',t_kepek='" . $osszesfajl . "',t_ar='" . $_POST["t_ar"] . "',t_kleiras='" . $_POST["t_kleiras"] . "',t_nleiras='" . $_POST["t_nleiras"] . "' where t_id='" . $_POST["termekmod"] . "'");

            if ($termekfrissit) {
                echo "<h3 style='color:#00FF00;'>Sikeres termék frissítés!</h3>";
                echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése!</h3>";
                $hibaszoveg = $hibaszoveg . "SQL Hiba történt - főkép felöltést NEM kért.<br />";
                echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
            }
        }
        else {
            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése (SQL HIBA)!</h3><br /><b>HIBÁK:</b><br />" . $hibaszoveg . "";
            echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
        }
    }
    elseif (isset($_POST["fkepetis"]) AND $_POST["fkepetis"] == "yes" AND !isset($_POST["tobbkepetis"]) AND $_POST["tobbkepetis"] != "yes") { //ha csak a főképet cseréli
        $SafeFile = $_FILES["t_fkep"]["name"];
        $SafeFile = strtolower($SafeFile);
        $SafeFile = str_replace("#", "_", $SafeFile);
        $SafeFile = str_replace("$", "_", $SafeFile);
        $SafeFile = str_replace("%", "_", $SafeFile);
        $SafeFile = str_replace("'", "_", $SafeFile);
        $SafeFile = str_replace(",", "_", $SafeFile);
        $SafeFile = str_replace("&", "_", $SafeFile);
        $SafeFile = str_replace("*", "_", $SafeFile);
        $SafeFile = str_replace("+", "_", $SafeFile);
        $SafeFile = str_replace("!", "_", $SafeFile);
        $SafeFile = str_replace("?", "_", $SafeFile);
        $SafeFile = str_replace("=", "_", $SafeFile);
        $SafeFile = str_replace("/", "_", $SafeFile);
        $SafeFile = str_replace("§", "_", $SafeFile);
        $SafeFile = str_replace("(", "_", $SafeFile);
        $SafeFile = str_replace(")", "_", $SafeFile);
        $SafeFile = str_replace("ö", "o", $SafeFile);
        $SafeFile = str_replace("ő", "o", $SafeFile);
        $SafeFile = str_replace("ó", "o", $SafeFile);
        $SafeFile = str_replace("ü", "u", $SafeFile);
        $SafeFile = str_replace("ű", "u", $SafeFile);
        $SafeFile = str_replace("ú", "u", $SafeFile);
        $SafeFile = str_replace("é", "e", $SafeFile);
        $SafeFile = str_replace("á", "a", $SafeFile);
        $SafeFile = str_replace("í", "i", $SafeFile);
        $SafeFile = str_replace("ö", "o", $SafeFile);
        $SafeFile = str_replace("Ö", "o", $SafeFile);
        $SafeFile = str_replace("Ő", "o", $SafeFile);
        $SafeFile = str_replace("Ó", "o", $SafeFile);
        $SafeFile = str_replace("Ü", "u", $SafeFile);
        $SafeFile = str_replace("Ű", "u", $SafeFile);
        $SafeFile = str_replace("Ú", "u", $SafeFile);
        $SafeFile = str_replace("É", "e", $SafeFile);
        $SafeFile = str_replace("Á", "a", $SafeFile);
        $SafeFile = str_replace("Í", "i", $SafeFile);
        $SafeFile = str_replace(" ", "_", $SafeFile);

        $ext = strtolower(substr(strrchr($SafeFile, "."), 1));

        if ($ext == "jpg" || $ext == "gif" || $ext == "bmp" || $ext == "png") {
            $datummost = getDate();
            $datekeszit = mktime($datummost["hours"], $datummost["minutes"], $datummost["seconds"], $datummost["mon"], $datummost["mday"], $datummost["year"]);
            $dazo = date("Ymdhms", $datekeszit);

            $fajlnev = "../shop/" . $dazo . "_" . $SafeFile;
            $fokep = $dazo . "_" . $SafeFile;

            if (move_uploaded_file($_FILES["t_fkep"]["tmp_name"], $fajlnev)) {
                $termekfrissit = $DB->query("update " . $elotag . "_shop_termek set t_gyarto='" . $_POST["t_gyarto"] . "',t_nev='" . $_POST["t_nev"] . "',t_kategoria='" . $_POST["t_kategoria"] . "',t_fkep='" . $fokep . "',t_ar='" . $_POST["t_ar"] . "',t_kleiras='" . $_POST["t_kleiras"] . "',t_nleiras='" . $_POST["t_nleiras"] . "' where t_id='" . $_POST["termekmod"] . "'");
                if ($termekfrissit) {
                    echo "<h3 style='color:#00FF00;'>Sikeres termék frissítés!</h3>";
                    echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
                }
                else {
                    echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése!</h3>";
                    echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
                }
            }
            else {
                echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a kép feltöltése a frissítés folyamán!</h3>";
                echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
            }
        }
        else {
            echo "<h3 style='color:#FF0000;'>Nem megfelelő formátumot akartál feltöltni (UPD), az engedélyezettek: JPG, PNG, GIF, BMP!</h3>";
            echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
        }
    }
    else { //képcsere és feltöltés nélküli frissítés
        $termekfrissit = $DB->query("update " . $elotag . "_shop_termek set t_gyarto='" . $_POST["t_gyarto"] . "',t_nev='" . $_POST["t_nev"] . "',t_kategoria='" . $_POST["t_kategoria"] . "',t_ar='" . $_POST["t_ar"] . "',t_kleiras='" . $_POST["t_kleiras"] . "',t_nleiras='" . $_POST["t_nleiras"] . "' where t_id='" . $_POST["termekmod"] . "'");
        if ($termekfrissit) {
            echo "<h3 style='color:#00FF00;'>Sikeres termék frissítés!</h3>";
            echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
        }
        else {
            echo "<h3 style='color:#FF0000;'>Sajnos nem sikerült a termék frissítése - SQL hiba történt a képcsere nélküli mentés folyamán!</h3>";
            echo "<script>
					function atiranyit()
					{
						location.href = 'index.php?lng=hun&page=shop';
					}
					ID = window.setTimeout('atiranyit();', 1*100);
				</script>";
        }
    }
}
elseif (isset($_GET["ujtermek"])) {
    echo "<h2>ÚJ TERMÉK FELVÉTELE</h2>";
    echo "<form name='ujtermek' method='POST' action='index.php?lng=" . $webaktlang . "&page=shop' enctype='multipart/form-data'>
					<input type='hidden' name='ujtermek' id='ujtermek' value='igen'>
					<b>Termék Gyártó:</b><br />
						<select name='t_gyarto' id='t_gyarto' style='width:200px;' required>
							<option value=''>Válasszon gyártót!</option>";
    $gyartok = $DB->query("select * from " . $elotag . "_shop_gyartok");
    while ($eg = $gyartok->fetch()) {
        echo "<option value='" . $eg["shop_gyartonev"] . "'>" . $eg["shop_gyartonev"] . "</option>";
    }
    echo "</select><br /><br />
					<b>Termék Kategória:</b><br />
						<select name='t_kategoria' id='t_kategoria' style='width:200px;'>
							<option value=''>Válasszon kategóriát!</option>";
    $kategoria1 = $DB->query("select * from " . $elotag . "_shop_kategoriak");
    while ($ek1 = $kategoria1->fetch()) {
        echo "<option value='" . $ek1["shop_kategorianev"] . "'>" . $ek1["shop_kategorianev"] . "</option>";
    }
    echo "</select><br /><br />
					<b>Termék neve:</b><br /><input type='text' name='t_nev' id='t_nev' style='width:200px;' required><br /><br />
					<b>Termék ára:</b><br /><input type='text' name='t_ar' id='t_ar' style='width:200px;' required> Ft<br /><br />
					<b>Termék főképe:</b><br /><input type='file' name='t_fkep' id='t_fkep' style='width:200px;' required><br /><br />
					<b>Termék további képei:</b><br />További képeket a mentés után, szerkesztéskor lehet hozzáadni!<br /><br />
					<b>Termékhez PDF prospektus:</b>&nbsp;<input type='checkbox' name='pdfis' id='pdfis' value='yes' onclick='PDFetis(this.form)'><br /><input type='file' name='t_pdf' id='t_pdf' style='width:200px;' disabled><br /><br />
					<b>Termék rövid leírása:</b><br /><input type='text' name='t_kleiras' id='t_kleiras' style='width:200px;' required><br /><br />
					<b>Termék teljes leírása:</b><br /><textarea name='t_nleiras' id='t_nleiras' style='width:400px;height:100px;'></textarea><br /><br />
						<input type='submit' id='termekmentes' name='termekmentes' value=' TERMÉK MENTÉSE ' class='btn btn-large btn-secondary'><br />
			   </form>";
}
elseif (isset($_GET["termekmod"])) {
    $ter_bet = $DB->query("select * from " . $elotag . "_shop_termek where t_id='" . $_GET["termekmod"] . "'");
    $adatok = $ter_bet->fetch();
    echo "<h2>TERMÉK MÓDOSÍTÁSA</h2>";
    echo "<form name='termekmodform' id='termekmodform' method='POST' action='index.php?lng=" . $webaktlang . "&page=shop' enctype='multipart/form-data'>
					<input type='hidden' name='termekmod' id='termekmod' value='" . $_GET["termekmod"] . "'>
					<b>Termék Gyártó:</b><br /><select name='t_gyarto' id='t_gyarto' style='width:200px;' required>
											<option value='" . $adatok["t_gyarto"] . "'>" . $adatok["t_gyarto"] . "</option>";
    $gyartok = $DB->query("select * from " . $elotag . "_shop_gyartok");
    while ($eg = $gyartok->fetch()) {
        echo "<option value='" . $eg["shop_gyartonev"] . "'>" . $eg["shop_gyartonev"] . "</option>";
    }
    echo "</select><br /><br />
					<b>Termék Kategória:</b><br><select name='t_kategoria' id='t_kategoria' style='width:220px;'>";
    if ($adatok["t_kategoria"] != "" AND $adatok["t_kategoria"] != " ") {
        echo "<option value='" . $adatok["t_kategoria"] . "' selected>AKTUÁLIS: " . $adatok["t_kategoria"] . "</option>";
    }
    else {
        echo "<option value=''>Válasszon kategóriát!</option>";
    }
    $kategoria1 = $DB->query("select * from " . $elotag . "_shop_kategoriak");
    while ($ek1 = $kategoria1->fetch()) {
        echo "<option value='" . $ek1["shop_kategorianev"] . "'>" . $ek1["shop_kategorianev"] . "</option>";
    }
    echo "</select><br /><br />
					<b>Termék neve:</b><br /><input type='text' name='t_nev' id='t_nev' style='width:200px;' value='" . $adatok["t_nev"] . "' required><br /><br />
					<b>Termék ára:</b><br /><input type='text' name='t_ar' id='t_ar' style='width:200px;' value='" . $adatok["t_ar"] . "' required> Ft<br /><br />
					<b>Termék főképe:</b><br /><a href='../shop/" . $adatok["t_fkep"] . "' rel='clearbox'><img src='../shop/" . $adatok["t_fkep"] . "' border='0' width='200'></a><br />
						<input type='checkbox' name='fkepetis' id='fkepetis' value='yes' onclick='fokepCsere(this.form)'> <small>(Szeretném a főképet lecserélni.)</small><br />
						<input type='file' name='t_fkep' id='t_fkep' style='width:200px;' disabled><br /><br />
					<b>Termék további képei:</b><br />";
    if ($adatok["t_kepek"] != "") {
        $t_kepek = explode("|", $adatok["t_kepek"]);
        $darabszam = 0;
        foreach ($t_kepek as $valami) {
            if ($t_kepek[$darabszam] == "") {
                echo "&nbsp;";
            }
            else {
                echo "<a href='../shop/kepek/" . $t_kepek[$darabszam] . "' rel='clearbox'><img src='../shop/kepek/" . $t_kepek[$darabszam] . "' border='0' width='100' height='100'></a>&nbsp;";
            }
            $darabszam++;
        }
    }
    else {
        echo "<small>Még nincsen feltöltve több kép!</small>";
    }
    echo "<br /><input type='checkbox' name='tobbkepetis' id='tobbkepetis' value='yes' onclick='tobbikepCsere(this.form)'> <small>(Szeretnék további képeket feltölteni.)</small><br />
							<small>(A meglévő képek törlődnek újak feltöltésekor!)</small><br />
						  <input type='file' name='t_kepek[]' id='t_kepek[]' value='' style='width:200px;' multiple disabled><br /><br />
					<b>Termék prospektus:</b><br />";
    if ($adatok["t_pdf"] != "") {
        echo "<a href='../shop/" . $adatok["t_pdf"] . "' target='_blank'>Prospektus megtekintése</a>";
    }
    else {
        echo "<small>Még nincsen feltöltve PDF dokumentum!</small>";
    }
    echo "	<br /><input type='checkbox' name='pdfis' id='pdfis' value='yes' onclick='PDFetismod(this.form)'><small>Szeretnék új PDF-et feltölteni</small><br /><input type='file' name='t_pdf' id='t_pdf' style='width:200px;' disabled><br /><br />
					<b>Termék rövid leírása:</b><br /><input type='text' name='t_kleiras' id='t_kleiras' style='width:200px;' value='" . $adatok["t_kleiras"] . "' required><br /><br />
					<b>Termék teljes leírása:</b><br /><textarea name='t_nleiras' id='t_nleiras' style='width:400px;height:100px;'>" . $adatok["t_nleiras"] . "</textarea><br /><br />
						<input type='submit' id='termekmentes' name='termekmentes' value=' TERMÉK FRISSÍTÉSE ' class='btn btn-large btn-secondary'><br />
			   </form>";
}

//default
$osszes=$DB->query("select * from ".$elotag."_shop_termek");
			echo "<h3>TERMÉK LISTA</h3>
					<a href='index.php?lng=".$webaktlang."&page=shop&ujtermek=y' class='btn'>+ új termék felvétele &raquo;</a><br>
					<br />
					<table id='datatables' class='display'>
						<thead>
							<tr>
								<th>Gyártó</th>
								<th>Termék név</th>
								<th>Termék kategória</th>
								<th>Termék ár</th>
								<th>Művelet</th>
							</tr>
							<tr>
								<th>Gyártó</th>
								<th>Termék név</th>
								<th>Termék kategória</th>
								<th>Termék ár</th>
								<th>Művelet</th>
							</tr>
						</thead><tbody>";
			while($row = $osszes->fetch())
			{
				echo "<tr>
							<td>".$row['t_gyarto']."</td>
							<td>".$row['t_nev']."</td>
							<td>".$row['t_kategoria']."</td>
							<td>".$row['t_ar']." Ft</td>
							<td align='center'><a href='index.php?lng=".$webaktlang."&page=shop&termekmod=".$row["t_id"]."' title='Termék módosítása'><b>M</b></a>&nbsp;|&nbsp;<a href='index.php?lng=".$webaktlang."&page=shop&termektorol=".$row["t_id"]."' title='Termék törlése' onclick='return confirm(\"Biztosan törlöd ezt a terméket?\")' style='color:#f00;'><b>X</b></a></td>
					   </tr>";
			}
			echo "</tbody>
				</table>";
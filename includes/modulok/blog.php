<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "ujblog")
			blog_hozzaad();
		elseif ($_REQUEST["opt"] == "ujblogmentes")
			blog_uj_mentes();
		elseif ($_REQUEST["opt"] == "szerkesztes")
			blog_szerkeszt();
		elseif ($_REQUEST["opt"] == "szerkesztmentes")
			blog_szerkeszt_mentes();
		elseif ($_REQUEST["opt"] == "torles")
			blog_torles();
		elseif ($_REQUEST["opt"] == "kikapcsol")
			blog_kikapcsol();
		elseif ($_REQUEST["opt"] == "bekapcsol")
			blog_bekapcsol();
		else
			blog_lista();
	}
	else
	{
		blog_lista();
	}
}
else
{
    return false;
}

function blog_lista() 
{
    $DB = NULL;
    try{
		//adatok lekérdezése
		$DB = connect(true);
		$sth = $DB->query("SELECT * FROM " . prefix."_blog_".lang);
		$res = $sth->fetchAll();
		$DB = NULL;

		echo '<div class="card">
			   <div class="card-body">
				<h2>Blog bejegyzések listája</h2>
				<a href="'.adminurl.'index.php?action=modules&thing=blog&opt=ujblog" class="btn btn-primary">+ Új hozzáadása</a>
				<div class="mt-3">';
					if(empty($res))
					{
						echo '<p class="text-danger">Jelenleg nem található egyetlen blog bejegyzés sem!</p>';
					}
					else
					{
						echo '<div class="row mx-0 fw-bolder mb-2">
								<div class="col-lg-4">Cikk címe</div>
								<div class="col-lg-4">Aktív?</div>
								<div class="col-lg-4">Műveletek</div>
							  </div>';
						$out = "";
						foreach($res as $row)
						{ 
							$out .= '<div class="row mx-0">';
								$out .= '<div class="col-lg-4">' . $row["blogcim"] . '</div>';
								$out .= '<div class="col-lg-4">' . ($row["cikkaktiv"]=='1' ? 'IGEN' : 'NEM') . '</div>';
								$out .= '<div class="col-lg-4">';
									$out .= '<a href="'.adminurl.'index.php?action=modules&thing=blog&opt=szerkesztes&blogid=' . $row["blogid"] . '" class="btn btn-outline-primary px-2 py-1"><span class="mdi mdi-wrench"></span> Szerkesztés</a>';
									if($row["cikkaktiv"]=='1')
									{
										$out .= '<a href="'.adminurl.'index.php?action=modules&thing=blog&opt=kikapcsol&blogid=' . $row["blogid"] . '" class="btn btn-outline-warning px-2 py-1"><span class="mdi mdi-power-cycle"></span> Kikapcsol</a>';
									}
									else
									{
										$out .= '<a href="'.adminurl.'index.php?action=modules&thing=blog&opt=bekapcsol&blogid=' . $row["blogid"] . '" class="btn btn-outline-success px-2 py-1"><span class="mdi mdi-power-cycle"></span> Aktivál</a>';
									}
									$out .= '<a href="'.adminurl.'index.php?action=modules&thing=blog&opt=torles&blogid=' . $row["blogid"] . '" class="btn btn-outline-danger px-2 py-1"><span class="mdi mdi-trash-can"></span> Törlés</a>';
								$out .= '</div>';
							$out .= '</div>';
						}
						echo $out;
					}
			echo '</div>
				 </div>
				</div>';
    }
    catch(Exception $e){
        $DB = NULL;
        echo '<p class="text-danger">' . $e->getMessage() . '</p>';
    }
}

function shop_uj_blog_nezet() 
{
    echo '<h2 class="text-muted font-weight-bold mb-2"> Új blog bejegyzés </h2>
			<div class="card">
				<div class="card-body">

					<form action="/wp-admin/index.php?action=modulok&thing=blog&opt=ujcikkmentes" method="post">
						<div class="form-group">
							<label for="blogkatnev">Új blog kategória</label>
							<input type="text" name="blogkatnev" id="blogkatnev" class="form-control" value="<?php echo (isset($_SESSION["new_blog"]["blogkatnev"]) ? $_SESSION["new_blog"]["blogkatnev"] : ""); ?>" maxlength="200" required />
						</div>
						
						<div>
							<button class="btn btn-primary"><span class="mdi mdi-content-save"></span> Mentés</button>
							<a class="btn btn-secondary ms-2" href="/wp-admin/index.php?action=blog&thing=kategoriak"><span class="mdi mdi-arrow-left"></span> Vissza</a>
						</div>
					</form>
				</div>
			</div>';
}

function shop_uj_blog_mentes() 
{
    $DB = NULL;
    try {
        //adatok tisztítása és ellenőrzése
        $form_data = filter_var_array($_POST, FILTER_UNSAFE_RAW);
        $_SESSION["new_blog"] = $form_data;

        if (!isset($form_data["blogkatnev"]) || empty($form_data["blogkatnev"]))
            throw new Exception("A megnevezés kitöltése kötelező!");
        
        
        //adatok bejegyzése
        $DB = connect(true);
        
        //megnevezés egyediségének ellenőrzése
        $sth = $DB->prepare("SELECT count(*) FROM " . prefix ."_blogcat_" . lang . " WHERE blogkatnev = :blogkatnev");
        $sth->bindValue(":blogkatnev", $form_data["blogkatnev"]);
        $sth->execute();
        
        $megnevezes_count = $sth->fetch();
        
        if($megnevezes_count["count(*)"] > 0){
            throw new Exception("Már létezik ilyen nevű blog kategória, kérem válasszon másik megnevezést!");
        }
        
        

        $sql = "INSERT INTO " . prefix ."_blogcat_" . lang . " (blogkatnev) VALUES (:blogkatnev)";
        $sth = $DB->prepare($sql);

        $sth->bindValue(":blogkatnev", $form_data["blogkatnev"]);
        $res = $sth->execute();

        $DB = NULL;

        //átirányítás
        unset($_SESSION["new_blog"]);
        $_SESSION["php_notification"] = 'A blog kategória sikeresen hozzáadva!';

        echo '<script>window.location.replace("/wp-admin/index.php?action=modul&thing=blog");</script>';
        die();
    }
    catch (Exception $e) {
        $DB = NULL;
        $_SESSION["php_err_notification"] = $e->getMessage();
        shop_uj_blog_kategoria_nezet();
    }
}
function shop_gyarto_szerkeszt_mentes()
{
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



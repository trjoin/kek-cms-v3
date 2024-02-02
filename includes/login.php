<?php
	//LOGIN SECTION
	if(isset($_POST["username"]) AND $_POST["username"]!="" AND $_POST["username"]!=" ")
	{
		$pdo = connect();
		$login=$pdo->prepare("select * from ".$prefix."_users_hun where usernev=? and userpwd=? ");
		$login->execute(array($_POST["username"],hash('sha256', $_POST['password'])));
		if($login->rowCount()>0)
		{
			$egy_sor=$login->fetch(\PDO::FETCH_ASSOC);
			$_SESSION["userlogged"]=$egy_sor["teljesnev"];
			$_SESSION["useremail"]=$egy_sor["useremail"];
			$_SESSION["jogkor"]=$egy_sor["jogkor"];
			$_SESSION["munkamenet"]=date("Ymdhis");
			
			//$support = telepitési idő az adatbázisból, ha lesz
			$support="2024-02-01";
			$datumt = strtotime($support); //telepítési idő
			$finale = strtotime(date("Y-m-d", strtotime("+24 month", $datumt))); //telepítéstől számított +24 hónap - azaz a lejárat napja!
			$ma = strtotime(date("Y-m-d"));
			if($finale>=$ma)
			{
				$error="";
			}
			else
			{
				$error="<span style='color:#f00;'>Az Ön szoftverének terméktámogatása (24 hónap) lejárt, a további zavartalan használhatoz kérlek keressen minket elérhetőségeinken!</span><br>";
			}
		}
		else
		{
			$pdo->query("update ".$prefix."_users_hun set login_fails=login_fails+1 where usernev='".$_POST["username"]."'");
			$error="<span style='color:#f00;'>Hibás felhasználónév vagy jelszó!</span><br>";
		}
	}
	//LOST PASSWORD SECTION
	elseif(isset($_POST["lostusername"]) AND isset($_POST["lostuseremail"]))
	{
		$pdo = connect();
		if($_POST["lostusername"]!="" AND $_POST["lostuseremail"]!="")
		{
			$losted=$pdo->prepare("select * from ".$prefix."_users_hun where usernev=? and useremail=? ");
			$losted->execute(array($_POST["lostusername"],$_POST["lostuseremail"]));
			if($losted->rowCount()>0)
			{
				//token készités és bejegyzés
				$l=$losted->fetch();
				$tokensave=$pdo->query("update ".$prefix."_users_hun set token='".md5($_POST["lostusername"])."' where usernev='".$_POST["lostusername"]."' and useremail='".$_POST["lostuseremail"]."'");
				
				$targy="Új jelszó kérése a ".$absp." weboldal adminisztrációs felületéhez";
				$mailcim  = $_POST["lostuseremail"];
				$headers  = "MIME-Version: 1.0" . "\r\n";    
				$headers .= "Content-type:text/html;charset=utf8" . "\r\n";   
				$headers .= "From: <".$defaultmail.">" . "\r\n";
				$ido = ("Kérelem érkezett: ".date("Y.m.d. H:i:s", time())."\r\n\r\n");
				$level=mail ($mailcim, $targy, "<b>ÚJ JELSZÓ IGÉNYLÉS!</b><br /><br />Az adatforgalmi figyelő értesít, hogy új jelszót kértél a weboldalad adminisztrációs felületéhez.<br /><br />
					<b>Weboldalad adatai:</b><br />URL link: ".$absp."<br /><br />Az új jelszavad az alábbi linkre való kattintással tudod biztonságosan igényelni:<br />
					<a href='".$absp."/wp-admin/index.php?lost=1&lpwd=".md5($_POST["lostusername"])."'>KÉREM AZ ÚJ JELSZÓT</a><br><br />
					Ha nem te voltál az aki az új jelszót igényelte, akkor ezt az üzenetet nyugodtan hagyd figyelmen kivül, nincs teendőd, védett a weboldalad.<br><br>" .$ido. "<br />",$headers);
				if($level)
				{
					$error="<span style='color:#090;'>Sikeres, nézd meg a postafiókod.</span>";
				}
				else
				{
					$error="<span style='color:#f00;'>Nem sikerült kiküldeni a jelszó pótlásához szükséges levelet, kérlek vedd fel velünk a kapcsolatot.</span>";
				}
			}
			else
			{
				$error="<span style='color:#f00;'>Sajnos valamelyik adat nem helyes, kérlek próbáld meg újra!</span>";
			}
		}
		else
		{
			$error="<span style='color:#f00;'>Sajnos valamelyik mező üresen maradt, kérlek próbáld meg újra!</span>";
		}
	}
	//LOST PASSWORD RETURN SECTION
	elseif(isset($_REQUEST["lpwd"]) AND $_REQUEST["lpwd"]!="" AND $_REQUEST["lpwd"]!=" " AND !isset($_POST["tokencheck"]))
	{
		$pdo = connect();
		$beload=$pdo->query("select * from ".$prefix."_users_hun where token='".$_REQUEST["lpwd"]."'");
		if($beload->rowCount()>0)
		{
			$error="<span style='color:#090;'>Sikeresen megkaptuk új jelszó készítési kérelmed!</span>";
			$siker=1;
		}
		else
		{
			$error="<span style='color:#f00;'>Sajnos váratlan hiba történt vagy a jelszópótló tokened lejárt, kérlek próbáld újra!</span>";
		}
	}
	//NEW PASSWORD SAVE ACTION
	elseif(isset($_POST["newusername"]) AND isset($_POST["newuserpassword"]) AND isset($_POST["tokencheck"]))
	{
		$pdo = connect();
		if($_POST["newusername"]!="" AND $_POST["newuserpassword"]!="" AND $_POST["tokencheck"]!="")
		{
			$beload=$pdo->query("select * from ".$prefix."_users_hun where usernev='".$_POST["newusername"]."' AND token='".$_POST["tokencheck"]."'");
			if($beload->rowCount()>0)
			{
				$tokensave=$pdo->query("update ".$prefix."_users_hun set token='',userpwd='".hash('sha256', $_POST['newuserpassword'])."' where usernev='".$_POST["newusername"]."' and token='".$_POST["tokencheck"]."'");
				if($tokensave)
				{
					$error="<span style='color:#090;'>Sikeresen megváltoztattad a jelszavad, mostmár bejelentkezhetsz <a href='/'>ide kattintva</a>!</span>";
					$siker=1;
				}
				else
				{
					$error="<span style='color:#f00;'>Sajnos adatbázis hiba történt, kérlek vedd fel velünk a kapcsolatot!</span>";
					$siker=1;
				}
			}
			else
			{
				$error="<span style='color:#f00;'>Sajnos elronthattad a felhasználói nevedet, vagy a tokened lejárt!</span>";
				$siker=1;
			}
		}
		else
		{
			$error="<span style='color:#f00;'>Sajnos valamelyik mező üresen maradt, kérlek próbáld újra!</span>";
		}
	}
	else
	{
		$error="";
	}
?>
<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php
		/*** LOGIN SECTION ***/
		if(!isset($_REQUEST["lost"]))
		{
			echo '<title>BEJELENTKEZÉS A RENDSZERBE - K.E.K. ADMIN V3.0 dev.</title>';
		}
		/*** LOST PASSWORD SECTION ***/
		else
		{
			echo '<title>ELFEJELTETT JELSZÓ PÓTLÁSA - K.E.K. ADMIN V3.0 dev.</title>';
		}
	?>
    <link rel="stylesheet" href="./assets/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./assets/css/style.css">
	<link rel="shortcut icon" href="/favicon.png">
	<link rel="apple-touch-icon" href="/favicon.png">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth loginbg">
          <div class="row flex-grow mx-auto">
            <div class="col-lg-4 p-5 d-flex justify-content-center align-items-center">
				<img src="./assets/images/trjoin.png" class="img-fluid" draggable="false" alt="webes tartalom kezelés könnyedén" loading="lazy">
			</div>
		<?php
			/*** LOGIN SECTION ***/
			if(!isset($_REQUEST["lost"]))
			{
		?>
			<div class="col-lg-4 my-2">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="./assets/images/logo.png" draggable="false" class="img-fluid" alt="KEK CMS webes tartalomkezelő rendszer" loading="lazy">
                </div>
                <h3 class="text-dark">Hello! Készen állsz?</h3>
                <h4 class="font-weight-light text-dark">Jelentkezz be a munka megkezdéséhez.</h4>
				<?php
					echo $error;
					if(isset($_SESSION["jogkor"]) AND $_SESSION["jogkor"]!="")
					{
						echo '<center><img src="/assets/images/preloader.gif"></center>';
						//bejelentkeztetve átirányitjuk a főoldalra ahol már a dashboard fogja fogadni a login helyett.
						echo '<script>
								function atiranyit()
								{
									location.href = "/wp-admin/index.php";
								}
								ID = window.setTimeout("atiranyit();", 1*3000);
							</script>';
					}
				?>
                <form class="pt-3" method="POST" action="./index.php" autocomplete="off">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="loginUsername" name="username" placeholder="Felhasználói neved" aria-label="Felhasználói név" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="loginPassword" name="password" placeholder="Jelszavad" aria-label="Belépési jelszó" autocomplete="off" required>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block d-flex w-100 justify-content-center align-items-center btn-secondary btn-lg font-weight-medium auth-form-btn" type="submit" aria-label="Belépés a rendszerbe">BELÉPÉS</button><br>
					<a class="btn btn-block d-flex btn-success justify-content-center align-items-center btn-lg font-weight-medium auth-form-btn" href="./index.php?lost" aria-label="Elfelejtett jelszó pótlása">Elfelejtetted jelszavad?</a>
                  </div>
                </form>
              </div>
            </div>
		<?php
			}
			/*** LOST PASSWORD SECTION ***/
			else
			{
		?>
			<div class="col-lg-4 my-2">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="./assets/images/logo.png" draggable="false" class="img-fluid" alt="KEK CMS webes tartalomkezelő rendszer">
                </div>
                <h3 class="text-dark">Elfelejtetted a jelszavad?</h3>
                <h4 class="font-weight-light text-dark">Kérd itt a pótlását.</h4>
				<?php
					echo $error;
					//lost password save new
					if(isset($siker) AND $siker==1)
					{
				?>
				<form class="pt-3" method="POST">
					<input type="hidden" name="tokencheck" value="<?php echo $_REQUEST["lpwd"]; ?>">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="newUsername" name="newusername" placeholder="Felhasználói neved" aria-label="Felhasználói név" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="newUserpassword" name="newuserpassword" placeholder="Új jelszó" aria-label="Új jelszó megadása" autocomplete="off" required>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block d-flex w-100 justify-content-center align-items-center btn-secondary btn-lg font-weight-medium auth-form-btn" type="submit" aria-label="Elfelejtett jelszó pótlásának kérése">MENTÉS</button>
                  </div>
                </form>
				<?php
					}
					//lost password form
					else
					{
				?>
                <form class="pt-3" method="POST" action="./index.php?lost">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="lostUsername" name="lostusername" placeholder="Felhasználói neved" aria-label="Felhasználói név" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="lostUseremail" name="lostuseremail" placeholder="Regisztrált email címed" aria-label="Regisztrált email cím" autocomplete="off" required>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block d-flex w-100 justify-content-center align-items-center btn-secondary btn-lg font-weight-medium auth-form-btn" type="submit" aria-label="Elfelejtett jelszó pótlásának kérése">KÉREM</button><br>
					<a class="btn btn-block d-flex btn-success justify-content-center align-items-center btn-lg font-weight-medium auth-form-btn" href="/" aria-label="Vissza a bejelentkezéshez">Beugrott mégis?<br>Ugorj vissza a belépéshez</a>
                  </div>
                </form>
				<?php
					}
				?>
              </div>
            </div>
		<?php
			}
		?>
			<div class="col-lg-4 white-bg my-2">
              <div class="auth-form-light text-left p-5">
				<script>
					$(function(){
						$.ajax({
							type: "GET",
							url: "https://trswebdesign.hu/hirekxml.php",
							dataType: "xml",
							success: function(xml) {
								var $newsData = $(xml).find('news:eq(0)');
								var $newsTitle = $($newsData).find('title:eq(0)').text();
								var $newsContent = $($newsData).find('content:eq(0)').text();
								$("#newsbox").html('<h5 class="text-dark">' + $newsTitle + '</h5><div id="content"><p class="text-dark">' + $newsContent + '</p></div>');
							}
						});
					})
				</script>
                <h3 class="text-dark">Újdonságok, érdekességek...</h3><br><br>
				<div class="p5" id="newsbox"> </div>
				<p class="text-dark"><small></small></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
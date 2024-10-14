<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
	<li class="nav-item nav-category">Vezérlőpult</li>
	<li class="nav-item <?php if(!isset($_REQUEST["action"])){echo'active';} ?>">
	  <a class="nav-link" href="./index.php">
		<span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
		<span class="menu-title">Műszerfal</span>
	  </a>
	</li>
	<li class="nav-item nav-category">Weboldal kezelés</li>
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='weboldal'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#page-layouts" aria-expanded="false" aria-controls="page-layouts">
		<span class="icon-bg"> <i class="mdi mdi-web menu-icon"></i> </span>
		<span class="menu-title">Weboldal</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='weboldal'){echo'show';} ?>" id="page-layouts">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='oldalak'){echo'active';} ?>" href="./index.php?action=weboldal&thing=oldalak">Oldalak és tartalom</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='menukezelo'){echo'active';} ?>" href="./index.php?action=weboldal&thing=menukezelo">Menü kezelő</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='oldalsav'){echo'active';} ?>" href="./index.php?action=weboldal&thing=oldalsav">Oldalsáv elemek</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='kozossegimedia'){echo'active';} ?>" href="./index.php?action=weboldal&thing=kozossegimedia">Közösségi média</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='beallitasok'){echo'active';} ?>" href="./index.php?action=weboldal&thing=beallitasok">Alapbeállítások</a></li><!-- ide kerül majd karbantartás mód, debug mód, weboldal default settings, sitemap generator, gdpr, db backupper to ftp -->
		  <?php
			if(isset($_SESSION["jogkor"]) AND $_SESSION["jogkor"]=="3")
			{
		?>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='hibanaplo'){echo'active';} ?>" href="./index.php?action=weboldal&thing=hibanaplo">Hiba napló</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='backupper'){echo'active';} ?>" href="./index.php?action=weboldal&thing=backupper">DB Biztonsági mentés</a></li>
		<?php
			}
		?>
		</ul>
	  </div>
	</li>
	
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='stat'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#page-stats" aria-expanded="false" aria-controls="page-stats">
		<span class="icon-bg"> <i class="mdi mdi-chart-areaspline menu-icon"></i> </span>
		<span class="menu-title">Statisztika</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='stat'){echo'show';} ?>" id="page-stats">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='latogatok'){echo'active';} ?>" href="./index.php?action=stat&thing=latogatok">Látogatók</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='marketing'){echo'active';} ?>" href="./index.php?action=stat&thing=marketing">Marketing</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='vasarlasok'){echo'active';} ?>" href="./index.php?action=stat&thing=vasarlasok">Vásárlások</a></li>
		</ul>
	  </div>
	</li>

	<li class="nav-item nav-category">Webáruház</li>
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='shopset'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#parameters" aria-expanded="false" aria-controls="parameters">
		<span class="icon-bg"><i class="mdi mdi-settings menu-icon"></i></span>
		<span class="menu-title">Beállítások</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='shopset'){echo'show';} ?>" id="parameters">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='parameterek'){echo'active';} ?>" href="./index.php?action=shopset&thing=parameterek">Paraméterek</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='szallmodok'){echo'active';} ?>" href="./index.php?action=shopset&thing=szallmodok">Szállítási módok</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='fizmodok'){echo'active';} ?>" href="./index.php?action=shopset&thing=fizmodok">Fizetési módok</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='webshop'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#boltom" aria-expanded="false" aria-controls="boltom">
		<span class="icon-bg"> <i class="mdi mdi-store menu-icon"></i> </span>
		<span class="menu-title">Boltom</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='webshop'){echo'show';} ?>" id="boltom">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='csoportok'){echo'active';} ?>" href="./index.php?action=webshop&thing=csoportok">Csoportok</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='kategoriak'){echo'active';} ?>" href="./index.php?action=webshop&thing=kategoriak">Kategóriák</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='gyartok'){echo'active';} ?>" href="./index.php?action=webshop&thing=gyartok">Gyártók</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='termekek'){echo'active';} ?>" href="./index.php?action=webshop&thing=termekek">Termékek</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='termek_opciok'){echo'active';} ?>" href="./index.php?action=webshop&thing=termek_opciok">Termék opciók</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='akciok'){echo'active';} ?>" href="./index.php?action=webshop&thing=akciok">Akció tervezés</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='arsavok'){echo'active';} ?>" href="./index.php?action=webshop&thing=arsavok">Ársávok</a></li>
		  <li class="nav-item"> <a class="nav-link <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='letoltheto'){echo'active';} ?>" href="./index.php?action=webshop&thing=letoltheto">Letölthető elemek</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='vasarlok'){echo'active';} ?>">
	  <a class="nav-link" href="./index.php?action=vasarlok&thing=listaz">
		<span class="icon-bg"><i class="mdi mdi-account-group menu-icon"></i></span>
		<span class="menu-title">Vásárlók</span>
	  </a>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='megrendelesek'){echo'active';} ?>">
	  <a class="nav-link" href="./index.php?action=megrendelesek&thing=listaz">
		<span class="icon-bg"><i class="mdi mdi-basket menu-icon"></i></span>
		<span class="menu-title">Megrendelések</span>
	  </a>
	</li>

	<li class="nav-item nav-category">Beépülő modulok</li>
<!-- MODULOK MEGTEKINTÉSE, HOG YMELYIK VAN BEKAPCSOLVA MERT CSAK AZOKAT ENGEDÉLYEZZÜK IDE -->
<?php
	$DB = NULL;
	$DB = connect(true);
	$beloadmodules=$DB->query("select * from ".prefix."_modul_".$_SESSION["lang"]." where aktiv='1' AND modulcont='' OR aktiv='1' AND modulcont IS NULL");
	while($modulok=$beloadmodules->fetch())
	{
		$modulnev=cserekari($modulok["modulnev"]);
		echo '<li class="nav-item '.(isset($_REQUEST["thing"]) && $_REQUEST["thing"]==$modulnev ? 'active' : '').'">
				  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok'.$modulok["modulid"].'" aria-expanded="false" aria-controls="modulok'.$modulok["modulid"].'">
					<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
					<span class="menu-title">'.$modulok["modulnev"].'</span>
					<i class="menu-arrow"></i>
				  </a>
				  <div class="collapse '.(isset($_REQUEST["thing"]) && $_REQUEST["thing"]==$modulnev ? 'show' : '').'" id="modulok'.$modulok["modulid"].'">
					<ul class="nav flex-column sub-menu">
					  <li class="nav-item"> <a class="nav-link '.(isset($_REQUEST["op"]) && $_REQUEST["op"]=='hozzaadas' ? 'active' : '').'" href="./index.php?action=modules&thing='.$modulnev.'&op=hozzaadas">Új hozzáadása</a></li>
					  <li class="nav-item"> <a class="nav-link '.(isset($_REQUEST["op"]) && $_REQUEST["op"]=='lista' ? 'active' : '').'" href="./index.php?action=modules&thing='.$modulnev.'&op=lista">Megtekintés</a></li>
					</ul>
				  </div>
				</li>';
	}
	$DB = NULL;
?>
	<li class="nav-item nav-category"><hr></li>
	<li class="nav-item">
	  <a class="nav-link" href="https://www.lootpack.hu/support.php" target="_blank">
		<span class="icon-bg"><i class="mdi mdi-lifebuoy menu-icon"></i></span>
		<span class="menu-title">Segítséget kérek &raquo;</span>
	  </a>
	</li>
  </ul>
</nav>
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
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=oldalak">Oldalak és tartalom</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=menukezelo">Menü kezelő</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=oldalsav">Oldalsáv elemek</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=kozossegimedia">Közösségi média</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=beallitasok">Alapbeállítások</a></li><!-- ide kerül majd karbantartás mód, debug mód, weboldal default settings, sitemap generator, gdpr, db backupper to ftp -->
		  <?php
			if(isset($_SESSION["jogkor"]) AND $_SESSION["jogkor"]=="3")
			{
		?>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=hibanaplo">Hiba napló</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=weboldal&thing=backupper">DB Biztonsági mentés</a></li>
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
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=stat&thing=latogatok">Látogatók</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=stat&thing=vasarlasok#">Vásárlások</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=stat&thing=marketing">Marketing</a></li><!-- pop-up, google analytics meg egyéb faszságok -->
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
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=shopset&thing=parameterek">Paraméterek</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=shopset&thing=szallmodok">Szállítási módok</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=shopset&thing=fizmodok">Fizetési módok</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='webshop'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#boltom" aria-expanded="false" aria-controls="boltom">
		<span class="icon-bg"><i class="mdi mdi-store menu-icon"></i></span>
		<span class="menu-title">Boltom</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["action"]) AND $_REQUEST["action"]=='webshop'){echo'show';} ?>" id="boltom">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=csoportok">Csoportok</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=kategoriak">Kategóriák</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=gyartok">Gyártók</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=termekek">Termékek</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=termek_opciok">Termék opciók</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=akciok">Akció tervezés</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=arsavok">Ársávok</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=webshop&thing=letoltheto">Letölthető elemek</a></li>
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
	<li class="nav-item <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='nyelvek'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok" aria-expanded="false" aria-controls="modulok">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Nyelvek</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='nyelvek'){echo'show';} ?>" id="modulok">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=nyelvek&op=ujnyelv">Új nyelv telepítése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=nyelvek&op=listaz">Nyelvek megtekintése</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='kepvalto'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok1" aria-expanded="false" aria-controls="modulok1">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Képváltó</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='kepvalto'){echo'show';} ?>" id="modulok1">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=kepvalto&op=ujkepvalto">Új hozzáadása</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=kepvalto&op=listaz">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='galeria'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok2" aria-expanded="false" aria-controls="modulok2">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Galéria</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='galeria'){echo'show';} ?>" id="modulok2">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=galeria&op=ujkepfeltoltes">Új kép hozzáadása</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=galeria&op=ujgaleria">Új galéria létrehozása</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=galeria&op=listaz">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='videok'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok3" aria-expanded="false" aria-controls="modulok3">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Videók</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='videok'){echo'show';} ?>" id="modulok3">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=videok&op=ujvideo">Új hozzáadása</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=videok&op=listaz">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='blog'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok4" aria-expanded="false" aria-controls="modulok4">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Blog</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='blog'){echo'show';} ?>" id="modulok4">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=blog&op=ujcikk">Új hozzáadása</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=blog&op=listaz">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='letoltesek'){echo'active';} ?>">
	  <a class="nav-link" data-bs-toggle="collapse" href="./index.php#modulok5" aria-expanded="false" aria-controls="modulok5">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Letöltések</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse <?php if(isset($_REQUEST["thing"]) AND $_REQUEST["thing"]=='letoltesek'){echo'show';} ?>" id="modulok5">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=letoltesek&op=ujletoltes">Új hozzáadása</a></li>
		  <li class="nav-item"> <a class="nav-link" href="./index.php?action=modulok&thing=letoltesek&op=listaz">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item nav-category"><hr></li>
	<li class="nav-item">
	  <a class="nav-link" href="https://www.lootpack.hu/support.php" target="_blank">
		<span class="icon-bg"><i class="mdi mdi-lifebuoy menu-icon"></i></span>
		<span class="menu-title">Segítséget kérek &raquo;</span>
	  </a>
	</li>
  </ul>
</nav>
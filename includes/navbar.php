<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
	<li class="nav-item nav-category">Vezérlőpult</li>
	<li class="nav-item active">
	  <a class="nav-link" href="index.php">
		<span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
		<span class="menu-title">Műszerfal</span>
	  </a>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#page-layouts" aria-expanded="false" aria-controls="page-layouts">
		<span class="icon-bg"> <i class="mdi mdi-web menu-icon"></i> </span>
		<span class="menu-title">Weboldal</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="page-layouts">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Oldalak és tartalom</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Menü kezelő</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Oldalsáv elemek</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Közösségi média</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Alapbeállítások</a></li><!-- ide kerül majd karbantartás mód, debug mód, weboldal default settings, sitemap generator, gdpr, db backupper to ftp -->
		  <?php
			if(isset($_SESSION["jogkor"]) AND $_SESSION["jogkor"]=="0")
			{
		?>
		  <li class="nav-item"> <a class="nav-link" href="#">Hiba napló</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">DB Biztonsági mentés</a></li>
		<?php
			}
		?>
		</ul>
	  </div>
	</li>
	
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#page-stats" aria-expanded="false" aria-controls="page-stats">
		<span class="icon-bg"> <i class="mdi mdi-chart-areaspline menu-icon"></i> </span>
		<span class="menu-title">Statisztika</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="page-stats">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Látogatók</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Vásárlások</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Marketing</a></li><!-- pop-up, google analytics meg egyéb faszságok -->
		</ul>
	  </div>
	</li>

	<li class="nav-item nav-category">Webáruház</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#parameters" aria-expanded="false" aria-controls="parameters">
		<span class="icon-bg"><i class="mdi mdi-settings menu-icon"></i></span>
		<span class="menu-title">Beállítások</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="parameters">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Paraméterek</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Szállítási módok</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Fizetési módok</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#boltom" aria-expanded="false" aria-controls="boltom">
		<span class="icon-bg"><i class="mdi mdi-store menu-icon"></i></span>
		<span class="menu-title">Boltom</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="boltom">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=csoportok">Csoportok</a></li>
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=kategoriak">Kategóriák</a></li>
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=gyartok">Gyártók</a></li>
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=termekek">Termékek</a></li>
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=termek_opciok">Termék opciók</a></li>
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=akciok">Akció tervezés</a></li>
		  <li class="nav-item"> <a class="nav-link" href="/index.php?action=webshop&thing=arsavok">Ársávok</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Letölthető elemek</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" href="index.php">
		<span class="icon-bg"><i class="mdi mdi-account-group menu-icon"></i></span>
		<span class="menu-title">Vásárlók</span>
	  </a>
	</li>
	<li class="nav-item">
	  <a class="nav-link" href="index.php">
		<span class="icon-bg"><i class="mdi mdi-basket menu-icon"></i></span>
		<span class="menu-title">Megrendelések</span>
	  </a>
	</li>

	<li class="nav-item nav-category">Beépülő modulok</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#modulok" aria-expanded="false" aria-controls="modulok">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Nyelvek</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="modulok">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Új nyelv telepítése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Nyelvek megtekintése</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#modulok1" aria-expanded="false" aria-controls="modulok1">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Képváltó</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="modulok1">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Új feltöltése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#modulok2" aria-expanded="false" aria-controls="modulok2">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Galéria</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="modulok2">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Új feltöltése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#modulok3" aria-expanded="false" aria-controls="modulok3">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Videók</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="modulok3">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Új feltöltése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#modulok4" aria-expanded="false" aria-controls="modulok4">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Blog</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="modulok4">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Új feltöltése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-bs-toggle="collapse" href="index.php#modulok5" aria-expanded="false" aria-controls="modulok5">
		<span class="icon-bg"><i class="mdi mdi-star-box menu-icon"></i></span>
		<span class="menu-title">Letöltések</span>
		<i class="menu-arrow"></i>
	  </a>
	  <div class="collapse" id="modulok5">
		<ul class="nav flex-column sub-menu">
		  <li class="nav-item"> <a class="nav-link" href="#">Új feltöltése</a></li>
		  <li class="nav-item"> <a class="nav-link" href="#">Megtekintés</a></li>
		</ul>
	  </div>
	</li>
  </ul>
</nav>
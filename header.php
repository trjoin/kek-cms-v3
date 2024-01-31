<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>K.E.K. ADMIN V3.0 dev.</title>
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/assets/css/demo_2/style.css">
    <link rel="shortcut icon" href="/assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.php" style="color:#fff;">KEK ADMIN</a>
          <a class="navbar-brand brand-logo-mini" href="index.php" style="color:#fff;">KEK</a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
		  <div class="search-field d-none d-xl-block">
            <form class="d-flex align-items-center h-100" action="index.html#">
              Box of shits and useless tools
            </form>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="index.php#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="/assets/images/faces/face28.png" alt="">
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1">Adlép Omed</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown dropdown-menu-end p-0 border-0 font-size-sm" aria-labelledby="profileDropdown" data-x-placement="bottom-end">
                <div class="p-2">
                  <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="index.php?action=profile">
                    <span>Profil</span><!-- ide kerül az aktuális felhasználó adatainak kezelése (név, email, tel, pwd, profilkép) -->
                    <i class="mdi mdi-account-outline ms-1"></i>
                  </a>
				  <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="index.php?action=users">
                    <span>Felhasználók</span><!-- ide kerül majd a felhasználó lista kezeléssel és az újak létrehozása -->
                    <i class="mdi mdi-account-group"></i>
                  </a>
				<?php
					if(isset($_SESSION["jogkor"]) AND $_SESSION["jogkor"]=="0")
					{
				?>
				  <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="index.php?action=modules">
                    <span>Modulok</span><!-- ide kerül a modul lista, alap modulok telepitője, egyéni modulok irása, listázója -->
                    <i class="mdi mdi-view-module"></i>
                  </a>
				<?php
					}
				?>
                  <div role="separator" class="dropdown-divider"></div>
                  <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="index.php?out">
                    <span>Kilépés</span>
                    <i class="mdi mdi-logout ms-1"></i>
                  </a>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="index.php#" data-bs-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
			<!-- egyetlen értesitési sor -->
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-cart-plus"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Vásárlások</h6>
                    <p class="text-gray ellipsis mb-0">5 új megrendelés érkezett!</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
			<!-- egyetlen értesitési sor -->
              </div>
            </li>
          </ul>
		  <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>

      <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-category">Vezérlőpult</li>
            <li class="nav-item">
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
                  <li class="nav-item"> <a class="nav-link" href="#">Csoportok</a></li>
				  <li class="nav-item"> <a class="nav-link" href="#">Kategóriák</a></li>
				  <li class="nav-item"> <a class="nav-link" href="#">Gyártók</a></li>
				  <li class="nav-item"> <a class="nav-link" href="#">Termékek</a></li>
				  <li class="nav-item"> <a class="nav-link" href="#">Termék opciók</a></li>
				  <li class="nav-item"> <a class="nav-link" href="#">Akció tervezés</a></li>
				  <li class="nav-item"> <a class="nav-link" href="#">Ársávok</a></li>
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

        <div class="main-panel">
          <div class="content-wrapper">
            <div class="d-xl-flex justify-content-between align-items-start">
              <h2 class="text-muted font-weight-bold mb-2"> MŰSZERFAL </h2>
              <div class="d-sm-flex justify-content-xl-between align-items-center mb-2">
                <div class="dropdown ms-0 ml-md-4 mt-2 mt-lg-0">
                  <i class="mdi mdi-calendar me-1"></i><?php echo date("Y.m.d.").", ".$napok[date("N")]; ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                      <div class="col-12 grid-margin">
                        <div class="card">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                  <h4 class="card-title mb-0">Aktuális</h4>
                                  <div class="dropdown dropdown-arrow-none">
                                    <button class="btn p-0 text-muted dropdown-toggle" type="button" id="dropdownMenuIconButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuIconButton1">
                                      <h6 class="dropdown-header">Settings</h6>
                                      <a class="dropdown-item" href="index.php#">Action</a>
                                      <a class="dropdown-item" href="index.php#">Another action</a>
                                      <a class="dropdown-item" href="index.php#">Something else here</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="index.php#">Separated link</a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-3 col-sm-4 grid-margin  grid-margin-lg-0">
                                <div class="wrapper pb-5 border-bottom">
                                  <div class="text-wrapper d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0">Total Profit</p>
                                    <span class="text-success">+ 2.95%</span>
                                  </div>
                                  <h3 class="mb-0 font-weight-bold">$ 92556</h3>
                                  <canvas id="total-profit-dark"></canvas>
                                </div>
                                <div class="wrapper pt-5">
                                  <div class="text-wrapper d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0">Expenses</p>
                                    <span class="text-muted">+ 52.95%</span>
                                  </div>
                                  <h3 class="mb-4 font-weight-bold">$ 59565</h3>
                                  <canvas id="total-expences-dark"></canvas>
                                </div>
                              </div>
                              <div class="col-lg-9 col-sm-8 grid-margin  grid-margin-lg-0">
                                <div class="ps-0 pl-lg-4 ">
                                  <div class="d-xl-flex justify-content-between align-items-center mb-2">
                                    <div class="d-lg-flex align-items-center mb-lg-2 mb-xl-0">
                                      <h3 class="font-weight-bold me-2 mb-0">Devices sales</h3>
                                      <h5 class="mb-0 text-muted">( growth 62% )</h5>
                                    </div>
                                    <div class="d-lg-flex">
                                      <p class="me-2 mb-0 text-muted">Timezone:</p>
                                      <p class="font-weight-bold mb-0">GMT-0400 Eastern Delight Time</p>
                                    </div>
                                  </div>
                                  <div class="graph-custom-legend clearfix" id="device-sales-legend"></div>
                                  <canvas id="device-sales-dark"></canvas>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
				</div>
			</div>
		</div>
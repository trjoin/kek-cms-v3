<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>K.E.K. ADMIN V3.0 dev.</title>
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- saját css-ek -->
    <link rel="stylesheet" href="/assets/kekcms/css/admin_style.css?v=<?php echo time(); ?>">
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
<?php
	include("includes/navbar.php");
?>
        <div class="main-panel">
			<div class="content-wrapper">
<div class="row flex-grow h-100 negyszaznegy">
	<div class="col-lg-12 mx-auto mt-5">
	  <div class="row align-items-center d-flex flex-row mt-5">
		<div class="col-lg-6 pr-lg-4">
		  <h1 class="display-1 mb-0" style="text-align: right;color:#000;">404-es hiba</h1>
		</div>
		<div class="col-lg-6 error-page-divider pl-lg-4">
		  <h2 style="text-align: left;color:#000;">Bocsesz!</h2>
		  <h3 class="font-weight-light" style="text-align: left;color:#000;">Amit próbálsz kisajtolni a rendszerből, az még vagy már nem létezik.</h3>
		</div>
	  </div>
	  <div class="row mt-5">
		<div class="col-12 text-center mt-xl-2">
		  <h4 class="font-weight-light" style="color:#000;">A kért művelet: <?php echo $_REQUEST["action"].' => '.$_REQUEST["thing"]; ?></h4>
		</div>
	  </div>
	  <div class="row mt-5">
		<div class="col-12 text-center mt-xl-2">
		  <a class="text-white font-weight-medium btn btn-primary btn-lg" href="./index.php">Vissza a műszerfalhoz</a>
		</div>
	  </div>
	</div>
</div>
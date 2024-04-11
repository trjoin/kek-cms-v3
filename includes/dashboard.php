<?php
	if(isset($_SESSION["munkamenet"]) AND $_SESSION["munkamenet"]!="")
	{
?>
<div class="d-xl-flex justify-content-between align-items-start">
  <h2 class="text-muted font-weight-bold mb-2"> MŰSZERFAL </h2>
  <?php echo $_SESSION["debugmode"]; ?>
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
					  <h2 class="card-title mb-0">Szép napot és jó munkát a K.E.K. CMS rendszerébe.</h2>
					</div>
				  </div>

				  <div class="col-lg-7 grid-margin grid-margin-lg-0">
					<div class="ps-0 pl-lg-4">
					  <div class="d-xl-block mb-2">
						<div class="d-lg-block mb-lg-2 mb-xl-0">
						  <h4 class="font-weight-bold mb-0">FONTOS!</h4>
						  <p class="mb-0">Többnyelvű oldal esetében a menüpontok és tartalmaik az MODULOK főmenüben található NYELVEK választó segítségével módosíthatóak, az alábbi módon:<br>
							- válassza ki a kívánt nyelvet a MODULOK főmenüből,<br>
							- kattintson a hozzá tartozó linkre - azaz a nyelv nevére,<br>
							- a főmenüben máris a választott idegen nyelvi tartalmak jelennek meg,<br>
							- ezekre kattintva már szerkeszteni is fogja tudni.
						  </p>
						  <br>
						  <h4 class="font-weight-bold mb-0">FIGYELEM!</h4>
						  <p class="mb-0">Új menüpont létrehozásánál, CSAK az aktuális - oldal által használt és beállított - nyelven jön létre a tartalom! A többi nyelven a tartalmakat egyénileg létre kell hozni!
						  </p>
						</div>
					  </div>
					</div>
				  </div>
				  
				  <div class="col-lg-5 grid-margin grid-margin-lg-0">
					  <div class="ps-0 pl-lg-4">
					    <div class="d-xl-block mb-2">
						<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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
										$("#newsbox").html('<h5 class="text-white">' + $newsTitle + '</h5><p class="text-white">' + $newsContent + '</p>');
									}
								});
							})
						</script>
						<h3 class="text-white">Újdonságok, érdekességek:</h3><br>
						<div id="newsbox"> </div>
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
<?php
	}
?>
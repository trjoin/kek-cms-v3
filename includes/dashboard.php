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
										$("#newsbox").html('<h5 class="text-white">' + $newsTitle + '</h5><p class="text-white">' + $newsContent.replace("\r\n","<br>") + '</p>');
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
	  
	  <div class="col-12 grid-margin">
		<div class="card">
		  <div class="card-body">
			<div class="row">
				
				<?php
					//stat vezérlő script
					$DB = NULL;
					$DB = connect(true);
					$ma=date("Y-m-d");
					//látogtók
					$latogatok=$DB->query("select count(statid) as mennyiseg, SUM(hanyszornezte) as hanyszor from ".prefix."_visitorstat_hun where mikornezett='".$ma."'");
					$l=$latogatok->fetch();
					$szazalekl=intval($l["mennyiseg"])/1000;
					//vásárlások
					$vasarlasok=$DB->query("select *,count(rendelesid) as darabszam,termekarak from ".prefix."_shop_megrendelesek_hun where renddatum BETWEEN '".$ma." 00:00:00' AND '".$ma." 23:59:59'");
					$v=$vasarlasok->fetch();
					if($v["darabszam"]!=0)
					{
						$szazalekv=intval($v["darabszam"])/1000;
						$penz=explode(",",$v["termekarak"]);
						$osszeg=0;
						$darabszam=$v["darabszam"];
						foreach($penz as $p)
						{
							if($p!="")
							{
								$osszeg=$osszeg+$p;
							}
						}
					}
					else
					{
						//nem volt ma még vásárlás
						$osszeg=0;
						$darabszam=0;
						$szazalekv=0;
					}
					//felhasználók száma
					$userszam=0;
					$vasarloszam=0;
					$feliratkozok=$DB->query("select count(newsid) as userszam from ".prefix."_newslist_hun where newsdate='".$ma."'");
					if($feliratkozok->rowCount()>0)
					{
						$f=$feliratkozok->fetch();
						$userszam=$f["userszam"];
					}
					$vasarlok=$DB->query("select count(vasarloid) as vasarloszam from ".prefix."_shop_vasarlok_hun");
					if($vasarlok->rowCount()>0)
					{
						$k=$vasarlok->fetch();
						$vasarloszam=$k["vasarloszam"];
					}
					$fullview=intval($userszam)+intval($vasarloszam);
					$szazalekf=$fullview/1000;
				?>
						<script>
							$(function() {
								$('.dashboard-progress-latogatok-dark').circleProgress({
									value: <?php echo $szazalekl; ?>,
									size: 125,
									thickness: 7,
									startAngle: 0,
									emptyFill: "#eef0fa",
									fill: {
										gradient: ["#7922e5"]
									}
								});
								$('.dashboard-progress-vasarlasok-dark').circleProgress({
									value: <?php echo $szazalekv; ?>,
									size: 125,
									thickness: 7,
									startAngle: 0,
									emptyFill: "#eef0fa",
									fill: {
										gradient: ["#f76b1c"]
									}
								});
								$('.dashboard-progress-userek-dark').circleProgress({
									value: <?php echo $szazalekf; ?>,
									size: 125,
									thickness: 7,
									startAngle: 0,
									emptyFill: "#eef0fa",
									fill: {
										gradient: ["#b4ec51"]
									}
								});
							});
						  </script>

				<div class="col-sm-12">
					<div class="d-flex justify-content-between align-items-center mb-4">
					  <h2 class="card-title mb-0">Mai statisztika <small>(<?php echo str_replace("-",".",$ma); ?>.)</small></h2>
					</div>
				  </div>
				  <div class="col-xl-4 col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
					<div class="card">
					  <div class="card-body text-center">
						<h5 class="mb-2 font-weight-normal">Látogatók</h5>
						<h2 class="mb-4 font-weight-bold"><?php echo $l["mennyiseg"]; ?> db egyedi</h2>
						<div class="dashboard-progress dashboard-progress-latogatok-dark d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-account-circle icon-md absolute-center"></i></div>
						<h4 class="mb-0 font-weight-bold mt-2"><?php if($l["hanyszor"]==""){echo '0';}else{echo $l["hanyszor"];}; ?> db összesen</h4>
						<p class="mt-4 mb-0 text-muted"> &nbsp; </p>
					  </div>
					</div>
				  </div>
				   <div class="col-xl-4 col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
					<div class="card">
					  <div class="card-body text-center">
						<h5 class="mb-2 font-weight-normal">Vásárlások</h5>
						<h2 class="mb-4 font-weight-bold"><?php echo number_format($osszeg,0,",","."); ?> Ft</h2>
						<div class="dashboard-progress dashboard-progress-vasarlasok-dark d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-cart icon-md absolute-center"></i></div>
						<h4 class="mb-0 font-weight-bold mt-2"><?php echo $darabszam; ?> db</h4>
						<p class="mt-4 mb-0 text-muted"> &nbsp; </p>
					  </div>
					</div>
				  </div>
				  <div class="col-xl-4  col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
					<div class="card">
					  <div class="card-body text-center">
						<h5 class="mb-2 font-weight-normal">Felhasználók</h5>
						<h2 class="mb-4 font-weight-bold"><?php echo $fullview; ?> db</h2>
						<div class="dashboard-progress dashboard-progress-userek-dark d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-eye icon-md absolute-center"></i></div>
						<p class="mt-4 mb-0 text-muted">Regisztrációk, feliraktozók, vásárlók</p>
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
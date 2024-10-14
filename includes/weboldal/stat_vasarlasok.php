<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	vasar_lista();
}
else
{
    return false;
}

	function vasar_lista()
	{
		$DB = NULL;
		$DB = connect(true);
		//mai vásárlások
		$ma=date("Y-m-d");
		$vasarlasokma=$DB->query("select *,count(rendelesid) as darabszam,termekarak from ".prefix."_shop_megrendelesek_hun where renddatum BETWEEN '".$ma." 00:00:00' AND '".$ma." 23:59:59'");
		$v=$vasarlasokma->fetch();
		if($v["darabszam"]!=0)
		{
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
		}
		//összes vásárlás
		$vasarlasokall=$DB->query("select *,count(rendelesid) as darabszam,termekarak from ".prefix."_shop_megrendelesek_hun");
		$va=$vasarlasokall->fetch();
		if($va["darabszam"]!=0)
		{
			$penzall=explode(",",$va["termekarak"]);
			$osszegall=0;
			$darabszamall=$va["darabszam"];
			foreach($penzall as $p)
			{
				if($p!="")
				{
					$osszegall=$osszegall+$p;
				}
			}
		}
		else
		{
			//nem volt még vásárlás
			$osszegall=0;
			$darabszamall=0;
		}
		
		echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
		
		echo '<div class="card">
				<div class="card-body">
					<h2>Vásárlási statisztika</h2>
					
					<div class="row">
						<div class="col-sm-6 row">
						  <div class="col-sm-12">
							<div class="d-flex justify-content-between align-items-center mb-4">
							  <h2 class="card-title mb-0">Mai statisztika <small>('.str_replace("-",".",$ma).'.)</small></h2>
							</div>
						  </div>
						  <div class="col-xl-6 col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
							<div class="card">
							  <div class="card-body text-center">
								<h5 class="mb-2 font-weight-normal">Vásárlások (db)</h5>
								<h2 class="mb-4 font-weight-bold">'.$darabszam.' db</h2>
								<p class="mt-4 mb-0 text-muted"> &nbsp; </p>
							  </div>
							</div>
						  </div>
						   <div class="col-xl-6 col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
							<div class="card">
							  <div class="card-body text-center">
								<h5 class="mb-2 font-weight-normal">Vásárlások (Ft)</h5>
								<h2 class="mb-4 font-weight-bold">'.number_format($osszeg,0,",",".").' Ft</h2>
								<p class="mt-4 mb-0 text-muted"> &nbsp; </p>
							  </div>
							</div>
						  </div>
						</div>
						<div class="col-sm-6 row">
						  <div class="col-sm-12">
							<div class="d-flex justify-content-between align-items-center mb-4">
							  <h2 class="card-title mb-0">Összesített statisztika</h2>
							</div>
						  </div>
						  <div class="col-xl-6 col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
							<div class="card">
							  <div class="card-body text-center">
								<h5 class="mb-2 font-weight-normal">Vásárlások (db)</h5>
								<h2 class="mb-4 font-weight-bold">'.$darabszamall.' db</h2>
								<p class="mt-4 mb-0 text-muted"> &nbsp; </p>
							  </div>
							</div>
						  </div>
						   <div class="col-xl-6 col-lg-6 col-sm-6 grid-margin grid-margin-lg-0 stretch-card">
							<div class="card">
							  <div class="card-body text-center">
								<h5 class="mb-2 font-weight-normal">Vásárlások (Ft)</h5>
								<h2 class="mb-4 font-weight-bold">'.number_format($osszegall,0,",",".").' Ft</h2>
								<p class="mt-4 mb-0 text-muted"> &nbsp; </p>
							  </div>
							</div>
						  </div>
						</div>
					</div>';
		
		//chart-hoz havi stat
		$aktualisev=date("Y");
		$haviertekek=array();
		for($i=1; $i<=12; $i++)
		{
			if($i<10){$i=str_pad($i, 2, '0', STR_PAD_LEFT);}
			$timemost = strtotime(''.$i.'/01/'.$aktualisev.'');
			$ehonap = date('Y-m-d', $timemost);
			$kovhonap = date('Y-m-d', strtotime('+1 month', $timemost));
			
			$vasarlasokhavi=$DB->query("select *,count(rendelesid) as darabszam,termekarak from ".prefix."_shop_megrendelesek_hun where renddatum BETWEEN '".$ehonap." 00:00:00' AND '".$kovhonap." 23:59:59'");
			$vak=$vasarlasokhavi->fetch();
			if($vak["darabszam"]!=0)
			{
				$penzho=explode(",",$vak["termekarak"]);
				$osszegho=0;
				foreach($penzho as $o)
				{
					if($o!="")
					{
						$osszegho=$osszegho+$o;
					}
				}
			}
			else
			{
				$osszegho=0;
			}
			
			array_push($haviertekek,$osszegho);
		}
		
		$havistat="";
		foreach($haviertekek as $val)
		{
			$havistat="'".$val."', ";
		}
		
		echo '		<div class="mt-3">
						<h4>Éves statisztika</h4>
						<canvas id="myChart"></canvas>
					</div>
				</div>
			</div>';
			
		echo '<script>
				  const ctx = document.getElementById("myChart");

				  new Chart(ctx, {
					type: "bar",
					data: {
					  labels: ["Január", "Február", "Március", "Április", "Május", "Június", "Július", "Augusztus", "Szeptember", "Október", "November", "December"],
					  datasets: [{
						label: "Vásárolt érték",
						data: ['.$havistat.' ],
						borderWidth: 1
					  }]
					},
					options: {
					  scales: {
						y: {
						  beginAtZero: true
						}
					  }
					}
				  });
				</script>';
	}
?>

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
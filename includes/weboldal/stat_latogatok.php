<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	stat_lista();
}
else
{
    return false;
}

	function stat_lista()
	{
		echo '<div class="card">
				<div class="card-body">
					<h2>Látogatói statisztika <small>(tárhely szintű)</small></h2>
					<div class="mt-3">
						<iframe src="https://adlepomed.hu/cgi-bin/awstats.pl?config=adlepomed.hu.viszontelado-tarhely.hu&ssl=1&lang=hu" frameborder="0" style="position:relative; top:0; left:0; bottom:0; right:0; width:100%; height:100vh; border:none; margin:0; padding:0; overflow:hidden; overflow-y:auto;  z-index:999999;">
							Your browser does not support iframes
						</iframe>
						<!--KÉSŐBBIEKBEN EZ IGY NÉZ KI: iframe src => ".url."/cgi-bin/awstats.pl?config=".domain.".viszontelado-tarhely.hu&ssl=1&lang=hu -->
					</div>
				</div>
			</div>';
	}
?>

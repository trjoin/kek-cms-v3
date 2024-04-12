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
		$naplofajl="./error_log";
		echo '<div class="card">
				<div class="card-body">
					<h2>Vásárlási statisztika</h2>
					<div class="mt-3">
						<h2>HAMAROSAN...</h2>
					</div>
				</div>
			</div>';
	}
?>

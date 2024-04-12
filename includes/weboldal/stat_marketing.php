<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	marketing_lista();
}
else
{
    return false;
}

	function marketing_lista()
	{
		$naplofajl="./error_log";
		echo '<div class="card">
				<div class="card-body">
					<h2>Marketinghez szükséges követőkódok és egyéb lehetőségek beillesztése</h2>
					<div class="mt-3">
						<h2>HAMAROSAN...</h2>
					</div>
				</div>
			</div>';
	}
?>

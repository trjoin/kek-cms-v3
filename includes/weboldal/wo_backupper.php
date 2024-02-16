<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "torles")
			backupper_torles();
		else
			backupper_lista();
	}
	else
	{
		backupper_lista();
	}
}
else
{
    return false;
}

	function backupper_torles()
	{
		$DB = NULL;
		try
		{
			unlink("./error_log");
			$_SESSION["php_notification"]="Sikeres művelet";
			echo '<script>window.location.replace("'.adminurl.'index.php?action=weboldal&thing=hibanaplo");</script>';
			die();
		}
		catch (Exception $e)
		{
			$DB = NULL;
			$_SESSION["php_err_notification"] = $e->getMessage();
			backupper_lista();
		}
	}
	function backupper_lista()
	{
		echo '<div class="card">
				<div class="card-body">
					<h2>Adatbázis biztonsági mentés</h2>
					<div class="mt-3">';
			echo '<p class="text-danger">HAMAROSAN.... </p>';
		echo '		</div>
				</div>
			</div>';
	}
?>

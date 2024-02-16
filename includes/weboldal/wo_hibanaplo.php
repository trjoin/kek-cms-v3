<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(isset($_REQUEST["opt"]))
	{
		if ($_REQUEST["opt"] == "torles")
			naplo_torles();
		else
			naplo_lista();
	}
	else
	{
		naplo_lista();
	}
}
else
{
    return false;
}

	function naplo_torles()
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
			naplo_lista();
		}
	}
	function naplo_lista()
	{
		$naplofajl="./error_log";
		echo '<div class="card">
				<div class="card-body">
					<h2>ADMIN HIBA NAPLÓ</h2>
					<div class="mt-3">';
						if(file_exists($naplofajl))
						{
							$myfile = fopen($naplofajl, "r") or die("Unable to open file!");
							$olvasas = fread($myfile,filesize($naplofajl));
							echo '<div style="max-height:1000px;overflow-y:auto;width:100%;display:block;position:relative;">';
								echo '<blockquote>'.nl2br($olvasas).'</blockquote>';
							echo '</div>';
							fclose($myfile);
							echo '<a class="btn btn-outline-danger mt-3" href="'.adminurl.'index.php?action=weboldal&thing=hibanaplo&opt=torles" onclick="return confirm(\'Biztosan törlöd a komplett naplót? Ez nem visszavonható!\')"><span class="mdi mdi-trash-can"></span> Napló ürítése</a>';
						}
						else
						{
							echo '<p class="text-danger">Jelenleg nem található hibanapló, valószinüleg jól dolgoztunk! ;) </p>';
						}

			echo '	</div>
				</div>
			</div>';
	}
?>

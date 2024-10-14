<?php
if(isset($_SESSION["userlogged"]) AND $_SESSION["userlogged"]!="" AND $_SESSION["userlogged"]!=" ")
{
	if(!isset($_REQUEST["thing"])){
		//Kezdetleges hibakezelés
		echo '<p class="text-danger">Hiányzó modul azonosító!<p>';
		return false;
	}
	else
	{
		$path = 'includes/profile/' . $_REQUEST["thing"] . '.php';
	}

	if(!file_exists($path)){
		echo '<p class="text-danger">Nem található modul!<p>';
		return false;
	}

	include_once $path;
}
else
{
    return false;
}
?>
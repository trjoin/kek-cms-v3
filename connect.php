<?php
	function connect()
	{
		$pdo = new PDO('mysql:host=localhost;dbname='.DBNAME.'', ''.DBUSER.'', ''.DBPASS.''); 
		$pdo->query("SET NAMES 'utf8'"); 
		return $pdo;
	}
?>
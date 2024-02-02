<?php
	function connect()
	{
		$pdo = new PDO('mysql:host='.HOST_NAME.';dbname='.DB_NAME.'', ''.USER_NAME.'', ''.PASSWD.''); 
		$pdo->query("SET NAMES 'utf8'"); 
		return $pdo;
	}
?>
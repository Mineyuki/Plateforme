<?php

try{
	$bd = new PDO('mysql:host=localhost;dbname=marc','marc','marc');
	$bd->query('SET NAMES utf8');
	$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
}
catch(PDOException $e){
	die('<p> La connexion a �chou�. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
}

?>

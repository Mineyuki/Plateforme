<?php

try{
	$bd = new PDO('pgsql:host=aquabdd;dbname=promotion16','11500147','2408019669J');
	$bd->query('SET NAMES utf8');
	$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
}
catch(PDOException $e){
	die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
}

?>

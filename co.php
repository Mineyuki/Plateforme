<?php

try{

	$bd = new PDO('mysql:host=localhost;dbname=marc', 'marc', 'marc'); // Pour ce connecter via la base de donner de l'IUT.
	$bd->query('SET NAMES utf8');
	$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
}
catch (PDOException $e)
{
	die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
}

?>

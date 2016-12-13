<?php

require('cnx.php');
require('Fonctions.php');

$tab = array("cle1" => "val1", "cle2" => "val2", "cle3" => "val3", "cle4"=> "val4");

$tabCle = array("cle1", "cle2", "cle3", "cle4");

if(testCles($tabCle, $tab) == true)
	echo "<p> testCles fonctionne</p>";
else
	echo "<p> fonctionne toujours pas</p>";
	

?>
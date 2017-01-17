<?php
//Récupération des variables de configuration
$query = $bd->query('SELECT * FROM forum_config');
$config = array();
while($data=$query->fetch())
{
    $config[$data['config_nom']] = $data['config_valeur']; 
}
$query->CloseCursor();
$id = $_SESSION['id'];

?>

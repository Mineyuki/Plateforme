<?php
require('config.php');
define('INSCRIT',2);
define('MODO',3);
define('ADMIN',4);

define('ERR_IS_CO','Vous ne pouvez pas accéder à cette page si vous n\'êtes pas connecté');
define('ERR_WRONG_USER','Vous n\'êtes pas le bon utilisateur');
define('ERR_FLOOD','Contrôle anti flood, vous ne pouvez poster que toutes les '.$config['temps_flood'].' sec.<br />
Veuillez attendre un moment puis recommencer');
define('ERR_AUTH_ADMIN','Vous ne pouvez pas accéder à cette page si vous n\'êtes pas un administrateur');
define('ERR_AUTH_VIEW','Vous ne pouvez pas accéder à cette page si vous n\'êtes pas connecté');
define('ERR_AUTH_DELETE','Vous ne pouvez pas accéder à cette page si vous n\'êtes pas connecté');
define('ERR_AUTH_DELETE_TOPIC','Vous ne pouvez pas accéder à cette page si vous n\'êtes pas connecté');
define('ERR_AUTH_EDIT','Vous ne pouvez pas editer sous ce compte');
define('ERR_AUTH_VERR','Vous ne pouvez pas vérouiller de topic');
define('ERR_FOR_EXIST','Ce forum n\'existe pas');
define('ERR_CAT_EXIST','Cette catégorie de forum n\'existe pas');


?>

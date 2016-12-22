<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Espace de travail</title>
<?php require('body.php');?>
<?php
	session_start();
			
	if(!isset($_SESSION['connexion']))
	{
		header("Location:Connexion.php");
		exit();
	}
?>
<?php require('footer.php');?>

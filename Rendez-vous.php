<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Rendez-vous</title>
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

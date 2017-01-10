<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Ressources</title>
<?php require('body.php');?>
<?php			
	if(!isset($_SESSION['connexion']))
	{
		header("Location:Connexion.php");
		exit();
	}
?>
<?php require('footer.php');?>

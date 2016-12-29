<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php require('body.php');
	
	if(!isset($_SESSION['connexion']))
	{
		header("Location:../Connexion.php");
		exit();
	}
?>

		<ol class="breadcrumb">
			<li><a href="../../Accueil.php">Accueil</a></li>
			<li class="active">Actualité</li>
		</ol>

<?php require('footer.php');?>

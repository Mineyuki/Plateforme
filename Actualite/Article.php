<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Ecrire un article</title>
<?php require('body.php');
	
	if(!isset($_SESSION['connexion']))
	{
		header("Location:../Connexion.php");
		exit();
	}
?>

		<ol class="breadcrumb">
			<li><a href="../Accueil.php">Accueil</a></li>
			<li><a href="Actualite.php">Actualité</a></li>
			<li class="active">Ecrire un article</li>
		</ol>
	

<?php require('footer.php');?>

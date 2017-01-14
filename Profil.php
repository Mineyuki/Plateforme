<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Profil</title>
<?php 
	require('body.php');

/*
 * Si les personnes ne sont pas connectées, ils sont envoyés à la page connexion.
 */

	if(!isset($_SESSION['connexion']))
	{
		header("Location:Connexion.php");
		exit();
	}

	require('co.php');

	$request = 'SELECT * FROM membres WHERE mail = :mail';
	$requete = $bd->prepare($request);
	$requete->bindValue('mail',$_SESSION['email']);
	$requete->execute();
	$information = $requete->fetch(PDO::FETCH_ASSOC);
	
?>

	<ol class="breadcrumb">
		<li><a href="Accueil.php">Accueil</a></li>
		<li class="active">Profil</li>
	</ol>
	
	<div class="container">
		<h1 class="text-center"><?php echo $_SESSION['nom']?></h1>
		<hr>
	</div>

<?php require('footer.php');?>

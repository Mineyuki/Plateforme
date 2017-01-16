<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Profil</title>
<?php 
	require('body.php');

/*
 * Si les personnes ne sont pas connectées, ils sont envoyés à la page connexion.
 */

	if(!isset($_SESSION['connexion']))
		header("Location:Connexion.php");

	require('co.php');
	$check = true;

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
		<div class="col-md-6 col-md-offset-3">
			<form class="form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
				<label>Nom</label>
				<input class="form-control" type="text" name="nom" value="<?php echo $information['nom']; ?>" disabled>
				<label>Prénom</label>
				<input class="form-control" type="text" name="prenom" value="<?php echo $information['prenom']; ?>" disabled>
				<label>Adresse mail</label>
				<?php
					if(isset($_POST['mail1']) and !filter_var($_POST['mail1'], FILTER_VALIDATE_EMAIL)){
							echo "<p>Adresse mail non valide !</p>";
							$check=false;
					}
				?>
				<input class="form-control" type="text" name="mail1" value="<?php echo $information['mail']; ?>">
				<label>Confirmer votre adresse mail</label>
				<input class="form-control" type="text" name="mail2">
				<label>Votre mot de passe</label>
				<input class="form-control" type="password" name="mdp1" value="<?php echo $information['motdepasse']; ?>">
				<label>Confirmer votre mot de passe</label>
				<input class="form-control" type="password" name="mdp2"><br/>
				<input class="btn btn-lg btn-primary btn-block" type ="submit" value="Modifier" name="modification">
			</form>
		</div>
	</div>

<?php
	require('footer.php');
?>

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
	$check=true;

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
		<h1 class="text-center"><?php echo $_SESSION['nom'];?></h1>
		<h2 class="text-center"><?php echo $information['categorie'];?></h2>
		<hr>
		<div class="col-md-6 col-md-offset-3">
			<form class="form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
				<label>Nom</label>
				<input class="form-control" type="text" value="<?php echo $information['nom']; ?>" disabled>
				<input class="form-control" type="hidden" name="nom" value="<?php echo $information['nom']; ?>">
				<label>Prénom</label>
				<input class="form-control" type="text" value="<?php echo $information['prenom']; ?>" disabled>
				<input class="form-control" type="hidden" name="prenom" value="<?php echo $information['prenom']; ?>">
				<label>Pseudo pour le forum</label>
				<?php
						if(isset($_POST['pseudo']) and trim($_POST['pseudo'])==''){
							echo '<p>Champ obligatoire !</p>';
							$check=false;
						}
				?>
				<input class="form-control" type="text" name="pseudo" value="<?php echo $information['membre_pseudo']; ?>" maxlength="255">
				<label>Adresse mail</label>
				<?php
					if(isset($_POST['mail1']) and !filter_var($_POST['mail1'], FILTER_VALIDATE_EMAIL)){
							echo "<p>Adresse mail non valide !</p>";
							$check=false;
					}
				?>
				<input class="form-control" type="text" name="mail1" value="<?php echo $information['mail']; ?>" maxlength="255">
				<label>Confirmer votre adresse mail</label>
				<?php
						if(isset($_POST['mail2']) and $_POST['mail1']!=$_POST['mail2']){
							echo "<p>Les adresses sont différentes !</p>";
							$check=false;
						}
				?>
				<input class="form-control" type="text" name="mail2" value="<?php echo $information['mail']; ?>" maxlength="255">
				<label>Votre mot de passe</label>
				<p>Il doit être supérieur à 6 caractères</p>
				<?php
						if(isset($_POST['mdp1']) and strlen($_POST['mdp1'])<6){
							echo "<p>Mot de passe non valide !</p>";
							$check=false;
						}
				?>
				<input class="form-control" type="password" name="mdp1" value="<?php echo $information['motdepasse']; ?>" maxlength="255">
				<label>Confirmer votre mot de passe</label>
				<?php
						if(isset($_POST['mdp2']) and $_POST['mdp1']!=$_POST['mdp2']){
							echo "<p>Les mots de passes sont différents !</p>";
							$check=false;
						}
				?>
				<input class="form-control" type="password" name="mdp2" value="<?php echo $information['motdepasse']; ?>" maxlength="255"><br/>
				<input class="btn btn-lg btn-primary btn-block" type ="submit" value="Modifier" name="modification">
			</form>
		</div>
	</div>

<?php
	require('footer.php');
	$pseudo = htmlspecialchars($_POST['pseudo']);
	$mail = htmlspecialchars($_POST['mail1']);
	$mdp = htmlspecialchars($_POST['mdp1']);
	$nom = htmlspecialchars($_POST['nom']);
	$prenom = htmlspecialchars($_POST['prenom']);

	$mdp = sha1($_POST['mdp1']);

	if(isset($_POST['modification']) and $check==1){
		$req = 'UPDATE membres SET membre_pseudo = :pseudonyme WHERE nom = :nom and prenom = :prenom';
		$requete = $bd->prepare($req); echo $pseudo; echo $nom;
		$requete->bindValue(':pseudonyme', $pseudo); // Vérification attaque par injection
		$requete->bindValue(':nom', $nom); // Vérification attaque par injection
		$requete->bindValue(':prenom', $prenom); // Vérification attaque par injection
		$requete->execute();
		echo '<script>
			document.location.href="Accueil.php"
		</script>
		<br />
		<h1 class="text-center">Profil modifié !</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	}
?>

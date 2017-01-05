<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Formulaire d'inscription</title>

<?php require('body.php');
	$check=true;
?>

		<div class="container">
			<div class="row">
				<div class="col-xs-4 col-md-offset-4 ">
					<form id="inscription" class="form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
						<h2 class="form-signin-heading">Inscrivez-vous :</h2>
						<br/>
						<label>Nom *</label>
						<?php
							if(isset($_POST['inscription']) and trim($_POST['nom'])==''){
								echo "<p>Champ obligatoire !</p>";
								$check=false;
							}
						?>
						<input class="form-control" type="text" placeholder="Votre nom" name ="nom" maxlength="255">
						<label>Prénom *</label>
						<?php
							if(isset($_POST['inscription']) and trim($_POST['prenom'])==''){
								echo "<p>Champ obligatoire !</p>";
								$check=false;
							}
						?>
						<input class="form-control" type="text" placeholder="Votre prénom" name="prenom" maxlength="255">
						<label>Adresse Mail *</label>
						<?php
							if(isset($_POST['inscription']) and !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
								echo "<p>Adresse mail non valide !</p>";
								$check=false;
							}
						?>
						<input class="form-control" type="text" placeholder="Votre email" name ="email" maxlength="255"/>
						<label>Confimer votre adresse mail *</label>
						<?php
							if(isset($_POST['inscription']) and $_POST['email']!=$_POST['email2']){
								echo "<p>Les adresses sont différentes !</p>";
								$check=false;
							}
						?>
						<input class="form-control" type="text" placeholder="Confirmer votre mail" name ="email2" maxlength="255"/>
						<label>Mot de passe *</label>
						<p>Il doit être supérieur à 6 caractères</p>
						<?php
							if(isset($_POST['inscription']) and strlen($_POST['mdp'])<6){
								echo "<p>Mot de passe non valide !</p>";
								$check=false;
							}
						?>
						<input class="form-control" type="password" placeholder="Mot de passe" name ="mdp" maxlength="255"/>
						<label>Confirmer votre mot de passe *</label>
						<?php
							if(isset($_POST['inscription']) and $_POST['mdp']!=$_POST['mdp2']){
								echo "<p>Les mots de passes sont différents !</p>";
								$check=false;
							}
						?>
						<input class="form-control" type="password" placeholder=" Confirmer votre mdp" name ="mdp2" maxlength="255"/>
						<input class="btn btn-lg btn-primary btn-block" type ="submit" value="S'inscrire" name="inscription">
						</br></br>
					</form>
					<p>*Champ obligatoire</p>

					<?php

					/*
					 * Connexion à la base de donnée
					 */

					require('co.php');

					/*
					 * Lors de la validation du formulaire
					 */

					if(isset($_POST["inscription"])){

						/*
						 * Si les champs ont bien été remplis
						 */

						if($check){
							/*
							 * Vérification balises non permises
							 */

							$nom = htmlspecialchars($_POST["nom"]);
							$prenom = htmlspecialchars($_POST["prenom"]);
							$email = htmlspecialchars($_POST["email"]);

							/*
							 * Cryptage des mots de passes
							 */

							$mdp = sha1($_POST["mdp"]);

							/*
							 * Demande à la base de donnée
							 */

							$requete = "SELECT * FROM membres where mail = :mail and nom = :nom and prenom = :prenom";
							$req = $bd->prepare($requete);

							/*
							 * Vérification pas de piratage au niveau des données
							 */

							$req->bindValue(':mail', $email);
							$req->bindValue(':nom', $nom);
							$req->bindValue(':prenom', $prenom);

							/*
							 * Exécution dans la base de donnée
							 */

							$req-> execute();

							/*
							 * Compte nombre de ligne
							 */

							$existe = $req->rowCount();

							/*
							 * Vérification si le nom, la personne et l'email est présent dans la base
							 */

							if($existe == 0){
								$insertUsr = $bd->prepare("INSERT INTO membres (nom, prenom, mail, motdepasse) VALUES (:nom, :prenom, :mail, :mdp)");
								$insertUsr->bindValue(':mail', $email);
								$insertUsr->bindValue(':nom', $nom);
								$insertUsr->bindValue(':prenom', $prenom);
								$insertUsr->bindValue(':mdp',$mdp);
								$insertUsr->execute();
								echo"<script>
								document.location.href=\"Connexion.php\"
								</script>";
							}
							else{
								echo "L'adresse mail a déjà été utilisée !";
							}
						}
					}
					?>
				</div>	
			</div>
		</div>
				
<?php require('footer.php');?>

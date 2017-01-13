<?php
	session_start();

/*
 * Se déconnecter de la plateforme
 */
	foreach($_SESSION as $nom => $donnee)
		unset($_SESSION[$nom]);
?>

<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Connexion</title>

<?php require('body.php');?>
		
		<div class="container">
    
			<div class="row ">
			
				<div class="col-xs-4 col-md-offset-4 ">

<!-- 
  -- Formulaire de connexion 
  -->

					<form id="connexion" class="form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
						<h2 class="form-signin-heading">Connectez-vous :</h2>
						<label for="Email" >Adresse mail</label> 
						<input type="text" id="Email" class="form-control" placeholder="Adresse mail" name="emailconnex"> 
						<label for="mdp" >Mot de passe</label> 
						<input type="password" id="mdpconnex" class="form-control" placeholder="Mot de passe" name="motdepasse">
						<!-- <div class="checkbox"><label> <input type="checkbox" value="remember-me"> Remember me </label>
						</div>-->
						<input class="btn btn-lg btn-primary btn-block" type="submit"name="connexion" value="Se Connecter">
					</br>
					</br>
					 <CENTER>
					<?php

/*
 * Requiert la connexion dans la base de donnée pour retrouver les données
 */
						require('co.php');

/*
 * Si $_POST['connexion'] contient des données lors de l'envoie du formulaire
 */
						if(isset($_POST["connexion"])){

/*
 * Vérification faille XXS des différents champs d'entrée
 */
 
							$mailconx = htmlspecialchars($_POST['emailconnex']);
							$mdpconx = htmlspecialchars($_POST['motdepasse']);

/*
 * Si l'un des champs d'entrée n'est pas vide
 */
							if(trim($_POST['emailconnex']) != "" and trim($_POST['motdepasse']) != ""){

/*
 * Codage en sha1 du mot de passe
 */
								$mdp = sha1($_POST['motdepasse']);

/*
 * Preparer la requête SQL pour obtenir la ligne où apparait le mail et le mot de passe correspondant 
 */

								$req = $bd->prepare("SELECT * FROM membres where mail = :mail and motdepasse = :mdp ");

/*
 * Prévention d'attaque par injection
 */

								$req->bindValue(':mail',$_POST['emailconnex']);
								$req->bindValue(':mdp',$mdp);

								$req->execute();

/*
 * Recherche de la première ligne renvoyé par la requête SQL
 */

								$res = $req->fetch();

/*
 * Si il y a un résultat, on stocke des des données utile dans $_SESSION pour les prochaines pages dont on a besoin
 */
								if( $res != false){
									$_SESSION['email'] = $res['mail'];
									$_SESSION['categorie'] = $res['categorie'];
									$_SESSION['connexion'] = 'connecte';
									$_SESSION['nom'] = $res['nom'] . ' ' . $res['prenom'];
									echo"<script>
										document.location.href=\"Accueil.php\"
									</script>";
								}
								else{
									echo "<p>Mauvais mail ou mot de passe ! </p>";
								}
							}
							else
								echo "<p>Tout les champs doivent être renseignés ! </p>";
						}
					?>
					 	<p> Vous n'êtes pas encore inscrit cliquez <a href="formulaire.php">ici</a> !</p>
					 </CENTER>
					</form>
				</div>				
			</div>			
		</div>
		
<?php require('footer.php');?>

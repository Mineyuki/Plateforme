<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Connexion</title>
<?php require('body.php');?>
		
		<div class="container">
    
			<div class="row ">
			
				<div class="col-xs-4 col-md-offset-4 ">

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
								require('co.php');
								if(isset($_POST["connexion"])){
									$mailconx =htmlspecialchars($_POST['emailconnex']);
									$mdpconx = htmlspecialchars($_POST['motdepasse']);
									if(trim($_POST['emailconnex']) != "" and trim($_POST['motdepasse']) != ""){
										$mdp = sha1($_POST['motdepasse']);
										$req = $bd->prepare("SELECT * FROM membres where mail = :mail and motdepasse = :mdp ");
										$req->bindValue(':mail',$_POST['emailconnex']);
										$req->bindValue(':mdp',$mdp);
										$req->execute();
										$res = $req->fetch();
										if( $res != false){					
											session_start();
											$_SESSION['email'] = $res['mail'];
											$_SESSION['connexion'] = 'connecte';
											$_SESSION['nom'] = $res['nom'];
											$_SESSION['ecriture_article'] = $res['ecriture_article'];
											header("Location: Accueil.php");
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

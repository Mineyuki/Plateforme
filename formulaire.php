<?php require('head.php');?>
		<title>Formulaire d'inscription</title>
<?php require('body.php');?>

		<div class="container">
			<div class="row">
				<div class="col-xs-4 col-md-offset-4 ">
					<form id="inscription" class="form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
						<h2 class="form-signin-heading">Inscrivez-vous :</h2>
						<br/>
						<label for="Email" class="sr-only">Adresse Mail </label>
						<input type="text" placeholder="Email" id = "email" class="form-control" required="" autofocus="" name ="email"/>
						<label for="email2" class="sr-only"> Confimer votre adresse mail </label>
						<input type="text" placeholder="Confirmer votre mail" id = "email2" class="form-control" required="" autofocus="" name ="email2"/>
						<label for="mdp" class="sr-only"> Mot de passe </label>
						<input type="password" placeholder="Mot de passe" id = "mdp" class="form-control" required="" autofocus="" name ="mdp"/>
						<label for="mdp2" class="sr-only"> Confirmer votre mdp  </label>
						<input type="password" placeholder=" Confirmer votre mdp" id = "mdp2" class="form-control" required="" autofocus=""name ="mdp2"/>
						<input class="btn btn-lg btn-primary btn-block" type ="submit" value="S'inscrire" name="inscription">
						</br></br>
					</form>

					<?php

					require('co.php');

					if (isset($_POST["inscription"])){
						if(trim($_POST['email']) !="" and trim($_POST['email2']) !="" and trim($_POST['mdp']) !="" and trim($_POST['mdp2']) !="" ){
							$email = htmlspecialchars($_POST["email"]);
							$email2 = htmlspecialchars($_POST["email2"]);
							$mdp = sha1($_POST["mdp"]);
							$mdp2 = sha1($_POST["mdp2"]);

							if($email == $email2){
								if(filter_var($email,FILTER_VALIDATE_EMAIL)){
									$req = $bd->prepare("SELECT * FROM membres where mail = :mail ");
									$req->bindValue(':mail', $email);
									$req-> execute();
									$existe = $req->rowCount();
									if($existe == 0){
										if($mdp == $mdp2){
											$insertUsr = $bd->prepare("INSERT INTO membres (mail, motdepasse) VALUES (:mail, :mdp)");
											$insertUsr->bindValue('mail', $email);
											$insertUsr->bindValue('mdp', $mdp);
											$insertUsr->execute();
											$erreur = " Votre compte a été créer ";
											header('Location: Connexion.php');
										}
										else{
											$erreur = "Les mots de passe ne corespondent pas ! ";
										}
										}
									else{
										$erreur = "L'adresse mail a déjà été utilisé !  ";
									}
									}
							else{
								$erreur = "L'adresse mail n'est pas valide ! ";
							}
							}
						else{
							$erreur = "Les adresses mail ne corespondent pas ! ";
						}
						}
					else{
						$erreur = "Tous les champs doivent être renseignés ! ";
					} 
					echo'<p>'.$erreur.'</p>';
					}
					?>
				</div>	
			</div>
		</div>
				
<?php require('footer.php');?>

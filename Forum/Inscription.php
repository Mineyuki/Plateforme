<html>
	<head>
		<meta charset="utf-8"/>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Inscription</title>
		<link rel="stylesheet" href="css/provisoire.css"/>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
		
		<div class="container">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<div class="row">
					<div class="col-md-offset-2 col-md-7">
						<h1> Inscription <br/> <small> Merci de renseigner vos informations </small></h1>
					</div>
				</div>

				<div class="row">
					<div class="col-md-offset-2 col-md-2">
						<div class="form-group">
							<label for="Nom">Nom</label>
							<input type="text" class="form-control" id="nom" placeholder="Nom" name="nom">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="Prenom">Prénom</label>
							<input type="text" class="form-control" id="prenom" placeholder="Prénom" name="prenom">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-offset-2 col-md-6">
						<div class="form-group">
							<label for="Email">Email address</label>
							<input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-2 col-md-3">
						<div class="form-group">
							<label class="radio-inline"><input type="radio" name="categorie" value="etudiant">Etudiant</label>
							<label class="radio-inline"><input type="radio" name="categorie" value="professeur">Professeur</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-2 col-md-3">
						<div class="form-group">
							<label for="Password">Mot de passe</label>
							<input type="password" class="form-control" id="mdp" placeholder="Mot de passe" name="mdp">
						</div>
					</div>
					<div class="col-md-offset-1 col-md-3">
						<div class="form-group">
							<label for="Vpassword">Vérification mot de passe</label>
							<input type="password" class="form-control" id="mdp2" placeholder="Vérification mot de passe" name="mdp2">
						</div>
					</div>
					
				</div>
				
				</br>
				<div class="row">
					<div class="col-md-offset-5  col-md-1">
						<input type="submit" class="btn btn-primary">
					</div>
				</div>
			</form>
			<br/>
			<br/>
	</body>
</html>



<?php

require('cnx.php');
print_r($_POST);	

/*
	probleme bootstrap niveau formulaire non existant

*/

if (isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['mdp2']) && isset($_POST['categorie']) && isset($_POST['nom']) && isset($_POST['prenom'])) 
{
	
	if(trim($_POST['email']) !="" and trim($_POST['mdp']) !="" and trim($_POST['mdp2']) !="" && trim($_POST['categorie']) != "" && trim($_POST['nom']) != '' && trim($_POST['prenom']) != '')
	{
		$nom = htmlspecialchars($_POST["nom"]);
		$prenom = htmlspecialchars($_POST["prenom"]);
		$email = htmlspecialchars($_POST["email"]);
		$mdp = sha1($_POST["mdp"]);
		$mdp2 = sha1($_POST["mdp2"]);
		$pseudo = "$prenom $nom";

			if(filter_var($email,FILTER_VALIDATE_EMAIL))
			{
				$req = $bd->prepare("SELECT * FROM membres where mail = :mail ");
				$req->bindValue(':mail', $email);
				$req-> execute();
				$existe = $req->rowCount();
				if($existe == 0)
				{

					if($mdp == $mdp2)
					{
						$reqID = $bd->prepare('select LAST_INSERT_ID() as id from membres');
						$reqID->execute();
						$res = $reqID->fetch(PDO::FETCH_NUM);
						$insertUsr = $bd->prepare("INSERT INTO membres (nom, prenom, membre_pseudo, mail, motdepasse, categorie) VALUES (:nom, :prenom, :pseudo, :mail, :mdp, :cate)");
						$insertUsr->bindValue(':mail', $email);
						$insertUsr->bindValue(':mdp', $mdp);
						$insertUsr->bindValue(':nom', $nom);
						$insertUsr->bindValue(':prenom', $prenom);
						
						$insertUsr->bindValue(':pseudo', $pseudo);
						$insertUsr->bindValue(':cate', $_POST['categorie']);
						$insertUsr->execute();
						$erreur = " Votre compte a été créer ";
						session_start();
						$_SESSION['email'] = $email;
						$_SESSION['prenom'] = $prenom;
						$_SESSION['pseudo'] = $pseudo;
						$_SESSION['categorie'] = $_POST['categorie'];
						$insertUsr->CloseCursor();
						$req = $bd-> prepare('select membre_id, membre_rang from membres where mail = :mail');
						$req->bindValue(':mail', $_SESSION['email']);
						$req->execute();
						$res = $req->fetch(PDO::FETCH_NUM);
						$_SESSION['id'] = $res[0];
						$_SESSION['level'] = $res[1];

						header('Location: Accueil.php');
					}
					else
					{
						$erreur = "Les mots de passe ne corespondent pas ! ";
					}
				}
				else
					{
						$erreur = "L'adresse mail a déjà été utilisé !  ";
					}
			}
			else
			{
				$erreur = "L'adresse mail n'est pas valide ! ";
			}	

	}
	else
	{
		
		$erreur = "Tous les champs doivent être renseignés ! ";
	} 

	 echo'<p>'.$erreur.'</p>';
	
}


?>
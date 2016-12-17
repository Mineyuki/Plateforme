<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Diplôme	Interuniversitaire </title>

			<!-- Bootstrap -->
	
		<link href="css/provisoire.css" rel="stylesheet">
		<link href="css/bootstrap.css" rel="stylesheet" >
		<link rel="shortcut icon" href="image/Logo_IUT_Villetaneuse.png"/>

		<script src="js/bootstrap-dropdown.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</head>

	<body>

		<nav id="navi" class="navbar navbar-default navbar-fixed-top"> 
				
			<div class="container-fluid"> 
				
				<ul class="nav navbar-nav">
					<li><a href="Accueil.html"><img id="logo" src="image/Logo_IUT_Villetaneuse.png" alt="Accueil"></a></li>
					<li class="dropdown navigation">
						<a href="Formation.html" class="dropdown-toogle">Formation</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="Formation/DIU.html">Formation Modulaire et Diplômante Interuniversitaire</a></li>
							<li><a href="Formation/GEA.html">Formations en Gestion, Comptabilité, Ressources Humaines, Management</a></li>
							<li><a href="Formation/CJ.html">Formations en Juridique, Notariat, Finance</a></li>
							<li><a href="Formation/INFO.html">Formations en Informatique, Systèmes, Logiciels</a></li>
							<li><a href="Formation/RT.html">Formations en Réseaux, Télécommunications</a></li>
							<li><a href="Formation/GEII.html">Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies</a></li>
						</ul>
					</li>		
					<li class="navigation"><a href="Ressource.html">Ressource</a></li>
					<li class="navigation"><a href="Travail.html">Espace de travail</a></li>
					<li class="navigation"><a href="Rendez-vous.html">Rendez-vous</a></li>
					<li class="navigation"><a href="Actualite.html">Actualites</a></li>
					<li class="navigation"><a href="Forum.html">Forum</a></li>   
					<li class="navigation"><a href="connexion.php"><span class="glyphicon glyphicon-user"></span> Connexion</a></li>
				</ul>
					
				<div id="recherche">
					<form class="navbar-form navbar-left" role="search">
							
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Rechercher">
						</div>
								
						<button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></button>
					</form>
				</div>
					
			</div>
					
		</nav>

		<div class="container">

			<div class="row">

				<div class="col-xs-4 col-md-offset-4 ">
		
		<form id="inscription" class="form" method="POST" action="<?echo $_SERVER['PHP_SELF'];?>">
			<h2 class="form-signin-heading">Inscrivez-vous :</h2>
				
						<br/>
							<label for="Email" class="sr-only">Adresse Mail </label>
						
							<input type="text" placeholder="Email" id = "email" class="form-control" required="" autofocus=""
								name ="email"/>
					
							<label for="email2" class="sr-only"> Confimer votre adresse mail </label>
						
							<input type="text" placeholder="Confirmer votre mail" id = "email2" class="form-control" required="" autofocus=""
								name ="email2"/>
						
							<label for="mdp" class="sr-only"> Mot de passe </label>
						
							<input type="password" placeholder="Mot de passe" id = "mdp" class="form-control" required="" autofocus=""
								name ="mdp"/>
						
							<label for="mdp2" class="sr-only"> Confirmer votre mdp  </label>
						
							<input type="password" placeholder=" Confirmer votre mdp" id = "mdp2" class="form-control" required="" autofocus=""
								name ="mdp2"/>
					<input class="btn btn-lg btn-primary btn-block" type ="submit" value="S'inscrire" name="inscription">
			</br></br>
		</form>
				</div>
				
			</div>
			
		</div>
				
			<hr class="featurette-divider">
		
		<footer class="main-footer">
			
			<div class="red-text text-center">
				<p><a href="#">Contacts</a> · <a href="#">Mentions légales</a></p>
				
				<a rel="nofollow" target="_blank"  href="https://www.facebook.com"><img src= "image/facebook_circle.png" width="40" height= "40" alt="connexion facebook"/></a>
				<a rel="nofollow" target="_blank"  href="https://twitter.com"><img src= "image/twitter_circle.png" width="40" height= "40" alt="connexion twitter"></a>
				<a rel="nofollow" target="_blank" href="https://mail.google.com"><img src= "image/gmail_circle.png" width="40" height= "40" alt="connexion gmail"/></a>
				<a rel="nofollow" target="_blank"  href="https://www.instagram.com/"><img src= "image/instagram_circle.png" width="40" height= "40" alt="connexion instrgram"/></a>
			</div>

		</footer>

	</body>
</html>



<?php

require('co.php');

if (isset($_POST["inscription"]))
{
	if(trim($_POST['email']) !="" and trim($_POST['email2']) !="" and trim($_POST['mdp']) !="" and trim($_POST['mdp2']) !="" )
	{
		$email = htmlspecialchars($_POST["email"]);
		$email2 = htmlspecialchars($_POST["email2"]);
		$mdp = sha1($_POST["mdp"]);
		$mdp2 = sha1($_POST["mdp2"]);

		if($email == $email2)
		{
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
						$insertUsr = $bd->prepare("INSERT INTO membres (mail, motdepasse) VALUES (:mail, :mdp)");
						$insertUsr->bindValue('mail', $email);
						$insertUsr->bindValue('mdp', $mdp);
						$insertUsr->execute();
						$erreur = " Votre compte a été créer ";
						header('Location: connexion.php');
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
			$erreur = "Les adresses mail ne corespondent pas ! ";
		}
		

	}
	else
	{
		$erreur = "Tous les champs doivent être renseignés ! ";
	} 

	 echo'<p>'.$erreur.'</p>';

}


?>

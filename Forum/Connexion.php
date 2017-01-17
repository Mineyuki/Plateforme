<html>
	<head>
		<meta charset="utf-8"/>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Connexion </title>
		<link rel="stylesheet" href="css/provisoire.css"/>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<center>
		<h2> Connexion</h2>
		<br/>
		<form method="POST" action="">
			<table>
				<tr>
					<td align="right">
						<label for="Email"> Mail : </label>
					</td>
					<td>
						<input type="email" placeholder="Email" id = "Email"
							name ="emailconnex"/>
					</td>
				</tr>
				<tr>
					<td align="right">
						<label for="mdp"> Mot de passe : </label>
					</td>
					<td>
						<input type="password" placeholder="Mot de passe" id = "mdpconnex"
							name ="motdepasse"/>
					</td>
				</tr>
			</table>
				<br/>
			<input type ="submit" value="Se connecter" name="connexion">
			</form>
		</form>
	</body>
</html>

<?php

require('cnx.php');
{
	if(isset($_POST["emailconnex"]) && isset($_POST['motdepasse']))
	{

		if(trim($_POST['emailconnex']) != "" and trim($_POST['motdepasse']) != "" )
		{
			$mailconx =htmlspecialchars($_POST['emailconnex']);
			$mdpconx = htmlspecialchars($_POST['motdepasse']);
			
				$mdp = sha1($mdpconx);
				$req = $bd->prepare("SELECT * FROM membres where mail = :mail and motdepasse = :mdp ");
				$req->bindValue(':mail',$mailconx);
				$req->bindValue(':mdp',$mdp);
				$req->execute();
				$res = $req->fetch();
				if( $res != false)
				{					
					session_start();
					$_SESSION['email'] = $res['mail'];
					$_SESSION['categorie'] = $res['categorie'];
					$_SESSION['prenom'] = $res['prenom'];
					$req = $bd-> prepare('select membre_id, membre_pseudo, membre_rang from membres where mail = :mail');
					$req->bindValue(':mail', $_SESSION['email']);
					$req->execute();
					$res = $req->fetch(PDO::FETCH_NUM);
					$_SESSION['id'] = $res[0];
					$_SESSION['pseudo'] = $res[1];
					$_SESSION['level'] = $res[2];
					header('Location: Accueil.php');
						/*Prendre les informations necessaire
							rediriger vers la page (a définir)*/
				}

						
					
		else
			{
				echo "<p>Mauvais mail ou mot de passe ! </p>";
			}
		}
	else
			echo "<p>Tout les champs doivent être renseignés ! </p>";
	}

}
?>
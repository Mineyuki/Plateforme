<html>
	<head>
		<title> </title>
		<meta charset="utf-8">
	</head>
	<body>
		<center>
		<h2> Connexion
		 </h2>
		<br/>
		<form method="POST" action="">
			<table>
				<tr>
					<td align="right">
						<label for="Email"> Mail : </label>
					</td>
					<td>
						<input type="text" placeholder="Email" id = "Email"
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
					echo'<p> Bonjour, vous êtes connecté avec l\'adresse mail :'.$_SESSION['email'].' ! </p>';
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

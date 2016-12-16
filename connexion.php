<?php

require('co.php');
{
	if(isset($_POST["connexion"]))
	{
	
		$mailconx =htmlspecialchars($_POST['emailconnex']);
		$mdpconx = htmlspecialchars($_POST['motdepasse']);

		if(trim($_POST['emailconnex']) != "" and trim($_POST['motdepasse']) != "" )
		{
				$mdp = sha1($_POST['motdepasse']);
				$req = $bd->prepare("SELECT * FROM membres where mail = :mail and motdepasse = :mdp ");
				$req->bindValue(':mail',$_POST['emailconnex']);
				$req->bindValue(':mdp',$mdp);
				$req->execute();
				$res = $req->fetch();
				if( $res != false)
				{					
					session_start();
					$_SESSION['email'] = $res['mail'];
					echo'<p> Bonjour, vous êtes connecté avec l\'adresse mail :'.$_SESSION['email'].' ! </p>';
					header("Location: Accueil.php");
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

<?php session_start(); ?>
<!-- PAGE DE CONNEXION -->
<?php
	/* Connexion base de donnée */
	require('co.php');

	/* Vérification entrée mail et mot de passe non vide */
	if(isset($_POST['emailconnex']) and trim($_POST['emailconnex'])!=''
		and isset($_POST['motdepasse']) and trim($_POST['motdepasse'])!='')
	{
		/* Codage en sha1 de motdepasse */
		$mot = sha1($_POST['motdepasse']);
		/* Préparation de la requête SQL */
		$requete = "SELECT * FROM membres where mail = :mail and motdepasse = :mdp ";
		$req = $bd->prepare($requete);
		/* Vérification attaque injection SQL */
		$req->bindValue(':mail',$_POST['emailconnex']);
		$req->bindValue(':mdp',$_POST['motdepasse']);
		/* Execution de la requête SQL */
		$req->execute();
		$res = $req->fetch(PDO::FETCH_ASSOC))
		if($res){
			if( htmlspecialchars($_POST['emailconnex'], ENT_QUOTES) == $res['mail']
				and sha1(htmlspecialchars($_POST['motdepasse'], ENT_QUOTES)) == $ligne['motdepasse']){
				$_SESSION['connecte'] = true;
				header("Location: Accueil.php");
			}
		}					
		else
		{
			echo "<p>Mauvais mail ou mot de passe ! </p>";
		}
	}
	else
		echo "<p>Tout les champs doivent être renseignés ! </p>";

?>

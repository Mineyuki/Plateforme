<html>
	<head>
		<title> Inscription</title>
		<meta charset="utf-8">
	</head>
	<body>
		<center>
		<h2> Inscription </h2>
		
		<br/>
		<form method="POST" >
			<table>
				<tr>
					<td align="right">
						<label for="Email"> Mail : </label>
					</td>
					<td>
						<input type="text" placeholder="Email" id = "email"
							name ="email"/>
					</td>
				</tr>
				<tr>
					<td align="right">
						<label for="email2"> Confimer votre adresse mail : </label>
					</td>
					<td>
						<input type="text" placeholder="Confirmer votre mail" id = "email2"
							name ="email2"/>
					</td>
				</tr>
				<tr>
					<td >
						<label for="categorie"> Êtes-vous étudiant ou professeur : </label>
					</td >
					<td>
						<INPUT type= "radio" name="categorie" value="etudiant" checked> étudiant
					</td>
					<td>
						<INPUT type= "radio" name="categorie" value="professeur"> professeur
					</td>
				</tr>
				<tr>
					<td align="right">
						<label for="mdp"> Mot de passe : </label>
					</td>
					<td>
						<input type="password" placeholder="Mot de passe" id = "mdp"
							name ="mdp"/>
					</td>
				</tr>
				<tr>
					<td align="right">
						<label for="mdp2"> Confirmer votre mdp : </label>
					</td>
					<td>
						<input type="password" placeholder=" Confirmer votre mdp" id = "mdp2"
							name ="mdp2"/>
					</td>
				</tr>
			</table>
			<br/>
			<input type ="submit" value="S'inscrire" name="inscription">
		</form>
			<br/>
			<br/>
		</center>
	</body>
</html>



<?php

require('cnx.php');

if (isset($_POST['email']) && $_POST['email2'] && $_POST['mdp'] && $_POST['mdp2'] && $_POST['categorie'])
{
	if(trim($_POST['email']) !="" and trim($_POST['email2']) !="" and trim($_POST['mdp']) !="" and trim($_POST['mdp2']) !="" && trim($_POST['categorie']) != "")
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
						$insertUsr = $bd->prepare("INSERT INTO membres (mail, motdepasse, categorie) VALUES (:mail, :mdp, :cate)");
						$insertUsr->bindValue(':mail', $email);
						$insertUsr->bindValue(':mdp', $mdp);
						$insertUsr->bindValue(':cate', $_POST['categorie']);
						$insertUsr->execute();
						$erreur = " Votre compte a été créer ";
						session_start();
						header('Location: Calendrier.php');
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
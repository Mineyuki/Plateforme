<?php session_start(); 
require('cnx.php');
?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		<title> profil </title>

		<link rel="stylesheet" href="css/provisoire.css"/>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	</head>
	<body>
		<?php require('Navigation.php'); 
		$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'consulter';
		$membre = isset($_GET['m'])?(int) $_GET['m']:'';
		$id=$_SESSION['id'];
		?>
		<?php
		//On regarde la valeur de la variable $action
		switch($action)
		{
			//Si c'est "consulter"
			case "consulter":
				//On récupère les infos du membre
				$query=$bd->prepare('SELECT membre_pseudo, membre_avatar,
				mail, membre_post
				FROM membres WHERE membre_id=:membre');
				$query->bindValue(':membre',$membre, PDO::PARAM_INT);
				$query->execute();
				$data=$query->fetch();

				//On affiche les infos sur le membre
				echo '<p><i>Vous êtes ici</i> : <a href="./Forum.php?f=1">Index du forum</a> --> 
				profil de '.stripslashes(htmlspecialchars($data['membre_pseudo']));
				echo'<h1>Profil de '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</h1>';
				   
				echo'<img src="./images/avatars/'.$data['membre_avatar'].'"
				alt="Ce membre n a pas d avatar" />';
				   
				echo'<p><strong>Adresse E-Mail : </strong>
				<a href="mailto:'.stripslashes($data['mail']).'">
				'.stripslashes(htmlspecialchars($data['mail'])).'</a><br />';
			 
				echo'Ce membre a posté <strong>'.$data['membre_post'].'</strong> messages
				<br /><br />';
				$query->CloseCursor();
			break;
			?>
			<?php
			//Si on choisit de modifier son profil
			case "modifier":
			if (empty($_POST['sent'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
			{
				//On commence par s'assurer que le membre est connecté
				if ($id==0) erreur(ERR_IS_NOT_CO);
				else{

					//On prend les infos du membre
					$query=$bd->prepare('SELECT membre_pseudo, nom, prenom, mail,
					membre_avatar
					FROM membres WHERE membre_id=:id');
					$query->bindValue(':id',$id,PDO::PARAM_INT);
					$query->execute();
					$data=$query->fetch();
					echo '<p><i>Vous êtes ici</i> : <a href="./Forum.php?f=1">Index du forum</a> --> Modification du profil';
					echo '<h1>Modifier son profil</h1>';
					
					echo '<form method="post" action="voirprofil.php?action=modifier" enctype="multipart/form-data">
				   
			 
					<fieldset><legend>Identifiants</legend>
					Pseudo : <strong>'.stripslashes(htmlspecialchars($data['membre_pseudo']))  .'</strong><br />       
					<label for="password">Nouveau mot de Passe :</label>
					<input type="password" name="password" id="password" /><br />
					<label for="confirm">Confirmer le mot de passe :</label>
					<input type="password" name="confirm" id="confirm"  />
					</fieldset>
			 
					<fieldset><legend>Contacts</legend>
					<label for="email">Votre adresse E_Mail :</label>
					<input type="text" name="email" id="email"
					value="'.stripslashes($data['mail']).'" /><br />
					
					<fieldset><legend>Profil sur le forum</legend>
					<label for="avatar">Changer votre avatar :</label>
					<input type="file" name="avatar" id="avatar" />
					(Taille max : 10 ko)<br /><br />
					<label><input type="checkbox" name="delete" value="Delete" />
					Supprimer l avatar</label>
					Avatar actuel :
					<img src="./images/avatars/'.$data['membre_avatar'].'"
					alt="pas d avatar" />
				  
					</fieldset>
					<p>
					<input type="submit" value="Modifier son profil" />
					<input type="hidden" id="sent" name="sent" value="1" />
					</p></form>';
					$query->CloseCursor();
				}
			}   
			else //Sinon on est dans la page de traitement
			{
				 $mdp_erreur = NULL;
				$email_erreur1 = NULL;
				$email_erreur2 = NULL;
				$avatar_erreur = NULL;
				$avatar_erreur1 = NULL;
				$avatar_erreur2 = NULL;
				$avatar_erreur3 = NULL;

				//Encore et toujours notre belle variable $i :p
				$i = 0;
				$temps = time();
				$email = $_POST['email'];
				$pass = md5($_POST['password']);
				$confirm = md5($_POST['confirm']);


				//Vérification du mdp
				if ($pass != $confirm || empty($confirm) || empty($pass))
				{
					 $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
					 $i++;
				}

				//Vérification de l'adresse email
				//Il faut que l'adresse email n'ait jamais été utilisée (sauf si elle n'a pas été modifiée)

				//On commence donc par récupérer le mail
				$query=$bd->prepare('SELECT mail FROM membres WHERE membre_id =:id'); 
				$query->bindValue(':id',$id,PDO::PARAM_INT);
				$query->execute();
				$data=$query->fetch();
				if (strtolower($data['mail']) != strtolower($email))
				{
					//Il faut que l'adresse email n'ait jamais été utilisée
					$query=$bd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE mail =:mail');
					$query->bindValue(':mail',$email,PDO::PARAM_STR);
					$query->execute();
					$mail_free=($query->fetchColumn()==0)?1:0;
					$query->CloseCursor();
					if(!$mail_free)
					{
						$email_erreur1 = "Votre adresse email est déjà utilisé par un membre";
						$i++;
					}

					//On vérifie la forme maintenant
					if (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
					{
						$email_erreur2 = "Votre nouvelle adresse E-Mail n'a pas un format valide";
						$i++;
					}
				}
				//Vérification de l'avatar
			 
				if (!empty($_FILES['avatar']['size']))
				{
					//On définit les variables :
					$maxsize = 30072; //Poid de l'image
					$maxwidth = 100; //Largeur de l'image
					$maxheight = 100; //Longueur de l'image
					//Liste des extensions valides
					$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' );
			 
					if ($_FILES['avatar']['error'] > 0)
					{
						$avatar_erreur = "Erreur lors du tranfsert de l'avatar : ";
					}
					if ($_FILES['avatar']['size'] > $maxsize)
					{
						$i++;
						$avatar_erreur1 = "Le fichier est trop gros :
						(<strong>".$_FILES['avatar']['size']." Octets</strong>
						contre <strong>".$maxsize." Octets</strong>)";
					}
			 
					$image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
					if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
					{
						$i++;
						$avatar_erreur2 = "Image trop large ou trop longue :
						(<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
						<strong>".$maxwidth."x".$maxheight."</strong>)";
					}
			 
					$extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
					if (!in_array($extension_upload,$extensions_valides) )
					{
						$i++;
						$avatar_erreur3 = "Extension de l'avatar incorrecte";
					}
				}
				
				if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
				{
					if (!empty($_FILES['avatar']['size']))
					{
							$nomavatar=move_avatar($_FILES['avatar']);
							$query=$bd->prepare('UPDATE membres
							SET membre_avatar = :avatar 
							WHERE membre_id = :id');
							$query->bindValue(':avatar',$nomavatar,PDO::PARAM_STR);
							$query->bindValue(':id',$id,PDO::PARAM_INT);
							$query->execute();
							$query->CloseCursor();
					}
			 
					//Une nouveauté ici : on peut choisis de supprimer l'avatar
					if (isset($_POST['delete']))
					{
						$query=$bd->prepare('UPDATE membres
						SET membre_avatar=0 WHERE membre_id = :id');
						$query->bindValue(':id',$id,PDO::PARAM_INT);
						$query->execute();
						$query->CloseCursor();
					}
			 
					echo'<h1>Modification terminée</h1>';
					echo'<p>Votre profil a été modifié avec succès !</p>';
					echo'<p>Cliquez <a href="./Forum.php?f=1">ici</a> 
					pour revenir à la page d\'index des topics</p>';
			 
					//On modifie la table
			 
					$query=$bd->prepare('UPDATE membres
					SET  membre_mdp = :mdp, mail=:mail, 
					WHERE membre_id=:id');
					$query->bindValue(':mdp',$pass,PDO::PARAM_INT);
					$query->bindValue(':mail',$email,PDO::PARAM_STR);
					$query->bindValue(':id',$id,PDO::PARAM_INT);
					$query->execute();
					$query->CloseCursor();
				}
				else
				{
					echo'<h1>Modification interrompue</h1>';
					echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';
					echo'<p>'.$i.' erreur(s)</p>';
					echo'<p>'.$mdp_erreur.'</p>';
					echo'<p>'.$email_erreur1.'</p>';
					echo'<p>'.$email_erreur2.'</p>';
					echo'<p>'.$avatar_erreur.'</p>';
					echo'<p>'.$avatar_erreur1.'</p>';
					echo'<p>'.$avatar_erreur2.'</p>';
					echo'<p>'.$avatar_erreur3.'</p>';
					echo'<p> Cliquez <a href="./voirprofil.php?action=modifier">ici</a> pour recommencer</p>';
				}
			} //Fin du else
			break;
		 
		default: //Si jamais c'est aucun de ceux-là c'est qu'il y a eu un problème :o
		echo'<p>Cette action est impossible</p>';
		 
		} //Fin du switch
		?>
</div>
</body>
</html>


	
	
	
	
	
	
	</body>
</html>




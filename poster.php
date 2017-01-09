<?php 
session_start();
require('cnx.php');
require('config.php');
require('Fonctions.php');
require('constants.php');
?>

<html>
	<head>
		<meta charset="utf-8"/>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Poster</title>
		<link rel="stylesheet" href="css/provisoire.css"/>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="bbcode.js"></script>
		
	</head>
	<body>
	<?php
	//Qu'est ce qu'on veut faire ? poster, répondre ou éditer ?
	$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
	$id = $_SESSION['id'];
	$forum=1;
	//Il faut être connecté pour poster !
	if ($id==0) erreur(ERR_IS_CO);

	//Si on veut poster un nouveau topic, la variable f se trouve dans l'url,
	//On récupère certaines valeurs
	if (isset($_GET['f']))
	{
		$query= $bd->prepare('SELECT forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
		FROM forum_forum WHERE forum_id =:forum');
		$query->bindValue(':forum',$forum,PDO::PARAM_INT);
		$query->execute();
		$data=$query->fetch();
		
		if (!verif_auth($data['auth_view']))
		{
			erreur(ERR_AUTH_VIEW);
		}
		else
		{
			echo '<p><i>Vous êtes ici</i> : 
			<a href="./Forum.php?f=1">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
			--> Nouveau topic</p>';
		}
		$query->CloseCursor();
	}
 
	//Sinon c'est un nouveau message, on a la variable t et
	//On récupère f grâce à une requête
	elseif (isset($_GET['t']))
	{
		$topic = (int) $_GET['t'];
		$query=$bd->prepare('SELECT topic_titre, forum_topic.forum_id,
		forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
		FROM forum_topic
		LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
		WHERE topic_id =:topic');
		$query->bindValue(':topic',$topic,PDO::PARAM_INT);
		$query->execute();
		$data=$query->fetch();
		
		if (!verif_auth($data['auth_view']))
		{
			erreur(ERR_AUTH_VIEW);
		}
		else
		{
			$forum = $data['forum_id'];  
			echo '<p><i>Vous êtes ici</i> :
			<a href="./Forum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
			--> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
			--> Répondre</p>';
		}
		$query->CloseCursor();  
		
	}
 
	//Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
	//On ne connait que le post, il faut chercher le reste
	elseif (isset ($_GET['p']))
	{
		$post = (int) $_GET['p'];
		$query=$bd->prepare('SELECT post_createur, forum_post.topic_id, topic_titre, forum_topic.forum_id,
		forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
		FROM forum_post
		LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
		LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
		WHERE forum_post.post_id =:post');
		$query->bindValue(':post',$post,PDO::PARAM_INT);
		$query->execute();
		$data=$query->fetch();
		
		if (!verif_auth($data['auth_view']))
		{
			erreur(ERR_AUTH_VIEW);
		}
		else
		{


			$topic = $data['topic_id'];
			$forum = $data['forum_id'];
		 
			echo '<p><i>Vous êtes ici</i> :
			<a href="./Forum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
			--> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
			--> Modérer un message</p>';
		}
		$query->CloseCursor();  
	}
	
	?>
	
	<?php
	switch($action)
	{
		case "repondre": //Premier cas on souhaite répondre
		
		if (!verif_auth($data['auth_post']))

		{
			erreur(ERR_AUTH_POST);
		}
		else
		{
		?>

			<h1>Poster une réponse</h1>
			 
			<form method="post" action="postok.php?action=repondre&amp;t=<?php echo $topic ?>" name="formulaire">
			 
				<fieldset><legend>Mise en forme</legend>
					<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
					<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
					<input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
					<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
					<br /><br />
					<img src="./image/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(' :D ');return(false)" />
					<img src="./image/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(' :lol: ');return(false)" />
					<img src="./image/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(' :triste: ');return(false)" />
					<img src="./image/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(' :frime: ');return(false)" />
					<img src="./image/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies(' XD ');return(false)" />
					<img src="./image/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(' :s ');return(false)" />
					<img src="./image/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(' :o ');return(false)" />
					<img src="./image/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(' :interrogation: ');return(false)" />
					<img src="./image/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(' :exclamation: ');return(false)" />
				</fieldset>
				 
				<fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" name="message"></textarea></fieldset>
				<p>
				<input type="submit" name="submit" value="Envoyer" />
				<input type="reset" name = "Effacer" value = "Effacer"/>
				</p>
			</form>
		<?php 
		}
		break;
		 
		case "nouveautopic":
		
		if (verif_auth($data['auth_topic']))
		{
			?>
			<h1>Nouveau topic</h1>
			<form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo $forum ?>" name="formulaire">
			 
				<fieldset><legend>Titre</legend>
					<input type="text" size="80" id="titre" name="titre" />
				</fieldset>
				 
				<fieldset><legend>Mise en forme</legend>
					<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
					<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
					<input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
					<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
					<br /><br />
					<img src="./image/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
					<img src="./image/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
					<img src="./image/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
					<img src="./image/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
					<img src="./image/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
					<img src="./image/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
					<img src="./image/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
				</fieldset>
				 
				<fieldset><legend>Message</legend>
					<textarea cols="80" rows="8" id="message" name="message"></textarea>
					<?php
					if (verif_auth($data['auth_annonce']))
					{
						?>
						<label><input type="radio" name="mess" value="Annonce" />Annonce</label>
						<label><input type="radio" name="mess" value="Message" checked="checked" />Topic</label><br />
						<?php
					}
					?>

				</fieldset>
				<p>
				<input type="submit" name="submit" value="Envoyer" />
				<input type="reset" name = "Effacer" value = "Effacer" /></p>
			</form>
		<?php
		}
		else{
			erreur(ERR_AUTH_TOPIC);
		}
		break;
		case "edit": //Si on veut éditer le post
			//On récupère la valeur de p
			$post = (int) $_GET['p'];
			echo'<h1>Edition</h1>';
		 
			//On lance enfin notre requête
		 
			$query=$bd->prepare('SELECT post_createur, post_texte, auth_modo FROM forum_post
			LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
			WHERE post_id=:post');
			$query->bindValue(':post',$post,PDO::PARAM_INT);
			$query->execute();
			$data=$query->fetch();

			$text_edit = $data['post_texte']; //On récupère le message

			//Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin) 
			if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
			{
				// Si cette condition n'est pas remplie ça va barder :o
				erreur(ERR_AUTH_EDIT);
			}
			else //Sinon ça roule et on affiche la suite
			{
				//Le formulaire de postage
				?>
				<form method="post" action="postok.php?action=edit&amp;p=<?php echo $post ?>" name="formulaire">
				<fieldset><legend>Mise en forme</legend>
				<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
				<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
				<input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)"/>
				<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
				<br /><br />
				<img src="./image/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
				<img src="./image/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
				<img src="./image/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
				<img src="./image/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
				<img src="./image/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
				<img src="./image/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
				<img src="./image/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
				</fieldset>
		 
				<fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" name="message"><?php echo $text_edit ?>
				</textarea>
				</fieldset>
				<p>
				<input type="submit" name="submit" value="Editer !" />
				<input type="reset" name = "Effacer" value = "Effacer"/></p>
				</form>
				<?php
			}
		break; //Fin de ce cas :o
		
		case "delete": //Si on veut supprimer le post
			//On récupère la valeur de p
			$post = (int) $_GET['p'];
			//Ensuite on vérifie que le membre a le droit d'être ici
			echo'<h1>Suppression</h1>';
			$query=$bd->prepare('SELECT post_createur, auth_modo
			FROM forum_post
			LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
			WHERE post_id= :post');
			$query->bindValue(':post',$post,PDO::PARAM_INT);
			$query->execute();
			$data = $query->fetch();
		 
			if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
			{
				// Si cette condition n'est pas remplie ça va barder :o
				erreur(ERR_AUTH_DELETE); 
			}
			else //Sinon ça roule et on affiche la suite
			{
				echo'<p>Êtes vous certains de vouloir supprimer ce post ?</p>';
				echo'<p><a href="./postok.php?action=delete&amp;p='.$post.'">Oui</a> ou <a href="./Forum.php?f=1">Non</a></p>';
			}
			$query->CloseCursor();
		break;
		?>


	
		<?php
		default: //Si jamais c'est aucun de ceux là c'est qu'il y a eu un problème :o
		echo'<p>Cette action est impossible</p>';
	} //Fin du switch
	?>

	</body>
</html>




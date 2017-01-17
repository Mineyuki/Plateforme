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
		<title> Sujet </title>
		<link rel="stylesheet" href="../css/provisoire.css"/>
		<link rel="stylesheet" href="../css/bootstrap.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<?php require('Navigation.php'); 
		
		//On récupère la valeur de t
		$topic = (int) $_GET['t'];
 
		//A partir d'ici, on va compter le nombre de messages pour n'afficher que les 15 premiers
		$query=$bd->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id, topic_last_post,
		forum_name, auth_view, auth_topic, auth_post 
		FROM forum_topic 
		LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id 
		WHERE topic_id = :topic');
		$query->bindValue(':topic',$topic,PDO::PARAM_INT);
		$query->execute();
		$data=$query->fetch();
		
		if (!verif_auth($data['auth_view']))
		{
			ereur(ERR_AUTH_VIEW);
		}
		else{


			$forum=$data['forum_id']; 
			$totalDesMessages = $data['topic_post'] + 1;
			$nombreDeMessagesParPage = $config['post_par_page'];
			$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
			
			//Nombre de pages
			$page = (isset($_GET['page']))?intval($_GET['page']):1;
			//On affiche l'image répondre
			$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;
			
			
			?>
			
			<?php
			echo '<p><i>Vous êtes ici</i> :
			<a href="./Forum.php?f=1">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
			--> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
			echo '<h1>'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br /><br />';
			?>
			
			

			<?php
			
			if (verif_auth($data['auth_post']))
				{	
					echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
					<button type="button" class="btn btn-outline-info">Répondre</button></a>';
				}
			if (verif_auth($data['auth_topic']))
			{		
				//On affiche l'image nouveau topic
				echo'<a href="./poster.php?action=nouveautopic&amp;f='.$data['forum_id'].'">
				<button type="button" class="btn btn-outline-info">Nouveau topic</button></a>';
			}
			$query->CloseCursor(); 
			//Enfin on commence la boucle !
			
			
			//requête recupérant les posts par rapport au topic concerné 
			$query=$bd->prepare('SELECT post_id , post_createur , post_texte , post_time ,
			membre_id, membre_pseudo, membre_avatar, membre_post
			FROM forum_post
			LEFT JOIN membres ON membres.membre_id = forum_post.post_createur
			WHERE topic_id =:topic
			ORDER BY post_id
			LIMIT :premier, :nombre');
			$query->bindValue(':topic',$topic,PDO::PARAM_INT);
			$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
			$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
			$query->execute();
	 
			//On vérifie que la requête a bien retourné des messages
			if ($query->rowCount()<1)
			{
				echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et reessayez</p>';
			}
			else
			{
				//Si tout roule on affiche notre tableau puis on remplit avec une boucle
				?>
				<div class="table-responsive">
				<table class="tableauTopic">
				<tr>
					<th class="vt_auteur"><strong>Auteurs</strong></th>             
					<th class="vt_mess"><strong>Messages</strong></th>       
				</tr>
				<?php
				while ($data = $query->fetch())
				{
					//On commence à afficher le pseudo du créateur du message :
					//On vérifie les droits du membre
					//(partie du code commentée plus tard)
					echo'<tr><td class="enteteTableauForum"><strong>
					<a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
					'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>';
			   
					/* Si on est l'auteur du message, on affiche des liens pour
					Modérer celui-ci.
					Les modérateurs pourront aussi le faire, il faudra donc revenir sur
					ce code un peu plus tard ! */     
	   
					if ($id == $data['post_createur'])
					{
						echo'<td class="enteteTableauForum" id=p_'.$data['post_id'].'>Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
						<a href="./poster.php?p='.$data['post_id'].'&amp;action=delete">
						<img src="./image/supprimer.gif" alt="Supprimer"
						title="Supprimer ce message" class="imgAvatar" /></a>   
						<a href="./poster.php?p='.$data['post_id'].'&amp;action=edit">
						<img src="./image/editer.gif" alt="Editer"
						title="Editer ce message" height="18" width="22" class="imgAvatar"/></a></td></tr>';
					}
					else
					{
						echo'<td class="enteteTableauForum">
						Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
						</td></tr>';
					}
		   
					//Détails sur le membre qui a posté
					echo'<tr><td class="interieurTableau">
					<img src="image/avatars/'.$data['membre_avatar'].'" alt="" height="45px" width="45px" style="text-align:center;"/></td>';
				   
					//Message
					echo'<td class="interieurTableau">'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'</td></tr>';
					
				} //Fin de la boucle ! \o/
				$query->CloseCursor();
				?><br/>
				</table>
				
				</div>
				
				<?php
				
				echo '<p>Page : ';
					echo get_list_page($page, $nombreDePages, './voirtopic.php?t='.$topic);
				echo'</p>';


		   
				//On ajoute 1 au nombre de visites de ce topic
				$query=$bd->prepare('UPDATE forum_topic
				SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
				$query->bindValue(':topic',$topic,PDO::PARAM_INT);
				$query->execute();
				$query->CloseCursor();
			echo '<br/>';
			} //Fin du if qui vérifiait si le topic contenait au moins un message
			//On affiche les pages 1-2-3 etc...
			for ($i = 1 ; $i <= $nombreDePages ; $i++)
			{
				if ($i == $page) //On affiche pas la page actuelle en lien
				{
					echo $i;
				}
				else
				{
					echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">' . $i . '</a> ';
				}
			}
			echo'</p>';
	 
			
			
			$query = $bd->prepare('SELECT topic_locked FROM forum_topic WHERE topic_id = :topic');
			$query->bindValue(':topic',$topic,PDO::PARAM_INT);
			$query->execute();
			$data=$query->fetch();

			if ($data['topic_locked'] == 1) // Topic verrouillé !
			{
				echo'<a href="./postok.php?action=unlock&t='.$topic.'">
				<button type="button" class="btn btn-outline-info">Déverouiller le topic</button></a>';
			}
			else //Sinon le topic est déverrouillé !
			{
				echo'<a href="./postok.php?action=lock&amp;t='.$topic.'">
				<button type="button" class="btn btn-outline-info">Vérouiller le topic</button></a>';
			}
			$query->CloseCursor();


			
			
		} //Fin du if vérifiant l'autorisation de l'utilsateur
		
		

		
		
		?>       
	</body>
</html>



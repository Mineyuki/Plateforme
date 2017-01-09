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
		<title> Rendez-vous </title>
		<link rel="stylesheet" href="css/provisoire.css"/>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<?php 
		$id=(isset($_SESSION['id']))?(int) $_SESSION['id']:0;
		require('Navigation.php'); 
		$forum = (int) $_GET['f'];
		$query=$bd->prepare('SELECT forum_name, forum_topic, auth_view, auth_topic FROM forum_forum where forum_id = :forum');
		$query->bindValue(':forum',$forum,PDO::PARAM_INT);

		$query->execute();
		$data=$query->fetch(PDO::FETCH_ASSOC);

		$totalDesMessages = $data['forum_topic'] + 1;
		$nombreDeMessagesParPage = 20;
		$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
		
		?>
		
		<?php
		echo '<p><i>Vous êtes ici</i> : <a href="./Forum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>';

		//Nombre de pages


		$page = (isset($_GET['page']))?intval($_GET['page']):1;

		//On affiche les pages 1-2-3, etc.
		echo '<p>Page : ';
		for ($i = 1 ; $i <= $nombreDePages ; $i++)
		{
			if ($i == $page) //On ne met pas de lien sur la page actuelle
			{
				echo $i;
			}
			else
			{
				echo '<a href="Forum.php?f='.$forum.'&amp;page='.$i.'">'.$i.'</a>';
			}
		}
		echo '</p>';


		$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

		//Le titre du forum
		echo '<h1>'.stripslashes(htmlspecialchars($data['forum_name'])).'</h1><br /><br />';


		//Et le bouton pour poster un nouveau topic
		echo'<a href="./poster.php?action=nouveautopic&amp">
		<button type="button" class="btn btn-outline-info">Nouveau topic</button></a>';
		$query->CloseCursor();
		?>

		<?php
		//On prend tout ce qu'on a sur les Annonces du forum
       

		$query=$bd->prepare('SELECT forum_topic.topic_id, topic_titre, topic_createur, topic_vu, topic_post, topic_time, topic_last_post,
		Mb.membre_pseudo AS membre_pseudo_createur, post_createur, post_time, Ma.membre_pseudo AS membre_pseudo_last_posteur, post_id FROM forum_topic 
		LEFT JOIN membres Mb ON Mb.membre_id = forum_topic.topic_createur
		LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
		LEFT JOIN membres Ma ON Ma.membre_id = forum_post.post_createur    
		WHERE topic_genre = "Annonce" and forum_topic.forum_id = :forum
		ORDER BY topic_last_post DESC');
		$query->bindValue(':forum',$forum,PDO::PARAM_INT);
		$query->execute();
		?>
		
		<?php
		//On lance notre tableau seulement s'il y a des requêtes !
		if ($query->rowCount()>0)
		{
		?>
			<table class="tableauForum">   
			<tr>
				<th class="enteteForum">Annonce</th>
				<th class="titre enteteForum "><strong>Titre</strong></th>             
				<th class="nombremessages enteteForum "><strong>Réponses</strong></th>
				<th class="auteur enteteForum "><strong>Auteur</strong></th>                       
				<th class="derniermessage enteteForum "><strong>Dernier message</strong></th>
			</tr>  
			<?php
			//On commence la boucle
			while ($data=$query->fetch())
			{
                //Pour chaque topic :
                //Si le topic est une annonce on l'affiche en haut
                //mega echo de bourrain pour tout remplir
               
                echo'<tr><td class="interieurTableau"><img src="./image/annonce.gif" alt="Annonce" /></td>

                <td id="titre class="interieurTableau""><strong>Annonce : </strong>
                <strong><a href="./voirtopic.php?t='.$data['topic_id'].'"                 
                title="Topic commencé à
                '.date('H\hi \l\e d M,y',$data['topic_time']).'">
                '.stripslashes(htmlspecialchars($data['topic_titre'])).'</a></strong></td>

                <td class="nombremessages interieurTableau">'.$data['topic_post'].'</td>

                <td class="interieurTableau"><a href="./voirprofil.php?m='.$data['topic_createur'].'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a></td>';

               	//Selection dernier message
				$nbr_post = $data['topic_post'] +1;
				$page = ceil($nbr_post / $nombreDeMessagesParPage);

                echo '<td class="derniermessage interieurTableau">Par
                <a href="./voirprofil.php?m='.$data['post_createur'].'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a><br />
                A <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.date('H\hi \l\e d M y',$data['post_time']).'</a></td></tr>';
			}
			?>
			</table>
			<?php
		}
		$query->CloseCursor();
		?>
			
		<?php
		//On prend tout ce qu'on a sur les topics normaux du forum


		$query=$bd->prepare('SELECT forum_topic.topic_id, topic_titre, topic_createur, topic_vu, topic_post, topic_time, topic_last_post,
		Mb.membre_pseudo AS membre_pseudo_createur, post_id, post_createur, post_time, Ma.membre_pseudo AS membre_pseudo_last_posteur FROM forum_topic
		LEFT JOIN membres Mb ON Mb.membre_id = forum_topic.topic_createur
		LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
		LEFT JOIN membres Ma ON Ma.membre_id = forum_post.post_createur   
		WHERE topic_genre <> "Annonce" AND forum_topic.forum_id = :forum 
		ORDER BY topic_last_post DESC
		LIMIT :premier ,:nombre');	
		$query->bindValue(':forum',$forum,PDO::PARAM_INT);

		$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
		$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
		$query->execute();
		
		if ($query->rowCount()>0)
		{
			?>
			<table class="tableauForum">
			<tr>
				<th class="enteteForum">Message</th>
				<th class="titre enteteForum "><strong>Titre</strong></th>             
				<th class="nombremessages enteteForum "><strong>Réponses</strong></th>
				<th class="auteur enteteForum "><strong>Auteur</strong></th>                       
				<th class="derniermessage enteteForum "><strong>Dernier message  </strong></th>
			</tr>
			<?php
			//On lance la boucle
       
			while ($data = $query->fetch())
			{
                //Ah bah tiens... re vla l'echo de fou
                echo'<tr><td class="interieurTableau"><img src="image/message.gif" alt="Message" /></td>

                <td class="titre interieurTableau">
                <strong><a href="./voirtopic.php?t='.$data['topic_id'].'"                 
                title="Topic commencé à
                '.date('H\hi \l\e d M,y',$data['topic_time']).'">
                '.stripslashes(htmlspecialchars($data['topic_titre'])).'</a></strong></td>

                <td class="nombremessages interieurTableau">'.$data['topic_post'].'</td>

                <td class="interieurTableau"><a href="./voirprofil.php?m='.$data['topic_createur'].'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data['membre_pseudo_createur'])).'</a></td>';

               	//Selection dernier message
				$nombreDeMessagesParPage = 20;
				$nbr_post = $data['topic_post'] +1;
				$page = ceil($nbr_post / $nombreDeMessagesParPage);

                echo '<td class="derniermessage interieurTableau">Par
                <a href="./voirprofil.php?m='.$data['post_createur'].'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data['membre_pseudo_last_posteur'])).'</a><br />
                A <a href="./voirtopic.php?t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.date('H\hi \l\e d M y',$data['post_time']).'</a></td></tr>';

			}
			?>
			</table>
			<?php
		}
		else //S'il n'y a pas de message
		{
			echo'<p>Ce forum ne contient aucun sujet actuellement</p>';
		}
		$query->CloseCursor();
		?>

		
	</body>
</html>	

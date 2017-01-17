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
		<link rel="stylesheet" href="../css/provisoire.css"/>
		<link rel="stylesheet" href="../css/bootstrap.css"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="bbcode.js"></script>
	</head>
	<body>
	
	<?php
	$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):''; //On récupère la valeur de la variable $action
 
	
	switch($action) //On switch sur $action
	{
		case "consulter": //Si on veut lire un message
 
			echo'<p><i>Vous êtes ici</i> : <a href="./Forum.php?f=1">Forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Consulter un message</p>';
			$id_mess = (int) $_GET['id']; //On récupère la valeur de l'id
			echo '<h1>Consulter un message</h1><br /><br />';

			//La requête nous permet d'obtenir les infos sur ce message :
			$query = $bd->prepare('SELECT  mp_expediteur, mp_receveur, mp_titre,               
			mp_time, mp_text, mp_lu, membre_id, membre_pseudo, membre_avatar,
			membre_post
			FROM forum_mp
			LEFT JOIN membres ON membre_id = mp_expediteur
			WHERE mp_id = :id');
			$query->bindValue(':id',$id_mess,PDO::PARAM_INT);
			$query->execute();
			$data=$query->fetch();

			// Attention ! Seul le receveur du mp peut le lire !
			if ($id != $data['mp_receveur']) erreur(ERR_WRONG_USER);
			   
			//bouton de réponse
			echo'<p><a href="./messagesprives.php?action=repondre&amp;dest='.$data['mp_expediteur'].'">
			<img src="./image/repondre.gif" alt="Répondre" 
			title="Répondre à ce message" /></a></p>'; 
			?>
			<table>     
				<tr>
					<th class="vt_auteur"><strong>Auteur</strong></th>             
					<th class="vt_mess"><strong>Message</strong></th>       
				</tr>
				<tr>
				<td>
					<?php echo'<strong>
					<a href="./voirprofil.php?m='.$data['membre_id'].'&amp;action=consulter">
					'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></strong></td>
					<td>Posté à '.date('H\hi \l\e d M Y',$data['mp_time']).'</td>';
					?>
				</tr>
				<tr>
					<td>
						<?php
							
						//Ici des infos sur le membre qui a envoyé le mp
						echo'<p><img src="./image/avatars/'.$data['membre_avatar'].'" alt=""  height="45px" width="45px" style="text-align:center;/>
						
						<br />Messages : '.$data['membre_post'].'
						</p>
						</td><td>';
							
						echo code(nl2br(stripslashes(htmlspecialchars($data['mp_text'])))).'
						</td></tr></table>';
			?>
			<?php
			if ($data['mp_lu'] == 0) //Si le message n'a jamais été lu
			{
				$query->CloseCursor();
				$query=$bd->prepare('UPDATE forum_mp 
				SET mp_lu = :lu
				WHERE mp_id= :id');
				$query->bindValue(':id',$id_mess, PDO::PARAM_INT);
				$query->bindValue(':lu','1', PDO::PARAM_STR);
				$query->execute();
				$query->CloseCursor();
			}
        
		break; //La fin !
		?>
		<?php
		case "nouveau": //Nouveau mp
			   
			echo'<p><i>Vous êtes ici</i> : <a href="./Forum.php?f=1">Forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Ecrire un message</p>';
			echo '<h1>Nouveau message privé</h1><br /><br />';
			?>
			<form method="post" action="postok.php?action=nouveaump" name="formulaire">
				<p>
					<label for="to">Envoyer à : </label>
					<input type="text" size="30" id="to" name="to" />
					<br />
					<label for="titre">Titre : </label>
					<input type="text" size="80" id="titre" name="titre" />
					<br /><br />
					<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
					<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
					<input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
					<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
					<br /><br />

					<textarea cols="80" rows="8" id="message" name="message"></textarea>
					<br />
					<input type="submit" name="submit" value="Envoyer" />
					<input type="reset" name="Effacer" value="Effacer" />
				</p>
		   </form>

		<?php
		break; 
		?>
		<?php
		case "repondre": //On veut répondre
			echo'<p><i>Vous êtes ici</i> : <a href="./Forum.php?f=1">Forum</a> --> <a href="./messagesprives.php">Messagerie privée</a> --> Ecrire un message</p>';
			echo '<h1>Répondre à un message privé</h1><br /><br />';

			$dest = (int) $_GET['dest'];
			?>
			<form method="post" action="postok.php?action=repondremp&amp;dest=<?php echo $dest ?>" name="formulaire">
				<p>
					<label for="titre">Titre : </label><input type="text" size="80" id="titre" name="titre" />
					<br /><br />
					<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
					<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
					<input type="button" id="souligné" name="souligné" value="Souligné" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
					<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
					<br /><br />
					<textarea cols="80" rows="8" id="message" name="message"></textarea>
					<br />
					<input type="submit" name="submit" value="Envoyer" />
					<input type="reset" name="Effacer" value="Effacer"/>
				</p>
			</form>
		   <?php
		break;
		?>
		<?php
		case "supprimer":
		   
		//On récupère la valeur de l'id
		$id_mess = (int) $_GET['id'];
		//Il faut vérifier que le membre est bien celui qui a reçu le message
		$query=$bd->prepare('SELECT mp_receveur
		FROM forum_mp WHERE mp_id = :id');
		$query->bindValue(':id',$id_mess,PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		//Sinon la sanction est terrible :p
		if ($id != $data['mp_receveur']) erreur(ERR_WRONG_USER);
		$query->CloseCursor(); 

		//2 cas pour cette partie : on est sûr de supprimer ou alors on ne l'est pas
		$sur = (int) $_GET['sur'];
		//Pas encore certain
		if ($sur == 0)
		{
		echo'<p>Etes-vous certain de vouloir supprimer ce message ?<br />
		<a href="./messagesprives.php?action=supprimer&amp;id='.$id_mess.'&amp;sur=1">
		Oui</a> - <a href="./messagesprives.php">Non</a></p>';
		}
		//Certain
		else
		{
			$query=$bd->prepare('DELETE from forum_mp WHERE mp_id = :id');
			$query->bindValue(':id',$id_mess,PDO::PARAM_INT);
			$query->execute();
			$query->CloseCursor(); 
			echo'<p>Le message a bien été supprimé.<br />
			Cliquez <a href="./messagesprives.php">ici</a> pour revenir à la boite
			de messagerie.</p>';
		}

		break;
		?>
		<?php
		//Si rien n'est demandé ou s'il y a une erreur dans l'url 
		//On affiche la boite de mp.
		default;
			
			echo'<p><i>Vous êtes ici</i> : <a href="./Forum.php?f=1">Forum</a> --> <a href="./messagesprives.php">Messagerie privée</a>';
			echo '<h1>Messagerie Privée</h1><br /><br />';

			$query=$bd->prepare('SELECT mp_lu, mp_id, mp_expediteur, mp_titre, mp_time, membre_id, membre_pseudo
			FROM forum_mp
			LEFT JOIN membres ON forum_mp.mp_expediteur = membres.membre_id
			WHERE mp_receveur = :id ORDER BY mp_id DESC');
			$query->bindValue(':id',$id,PDO::PARAM_INT);
			$query->execute();
			echo'<p><a href="./messagesprives.php?action=nouveau">
			<button type="button" class="btn btn-class-secondary">Nouveau</button>
			</a></p>';
			if ($query->rowCount()>0)
			{
				?>
				<table>
				<tr>
				<th></th>
				<th class="mp_titre"><strong>Titre</strong></th>
				<th class="mp_expediteur"><strong>Expéditeur</strong></th>
				<th class="mp_time"><strong>Date</strong></th>
				<th><strong>Action</strong></th>
				</tr>

				<?php
				//On boucle et on remplit le tableau
				while ($data = $query->fetch())
				{
					echo'<tr>';
					//Mp jamais lu, on affiche l'icone en question
					if($data['mp_lu'] == 0)
					{
					echo'<td><img src="./image/message_non_lu.gif" alt="Non lu" /></td>';
					}
					else //sinon une autre icone
					{
					echo'<td><img src="./image/message_lu.gif" alt="Déja lu" /></td>';
					}
					echo'<td id="mp_titre">
					<a href="./messagesprives.php?action=consulter&amp;id='.$data['mp_id'].'">
					'.stripslashes(htmlspecialchars($data['mp_titre'])).'</a></td>
					<td id="mp_expediteur">
					<a href="./voirprofil.php?action=consulter&amp;m='.$data['membre_id'].'">
					'.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></td>
					<td id="mp_time">'.date('H\hi \l\e d M Y',$data['mp_time']).'</td>
					<td>
					<a href="./messagesprives.php?action=supprimer&amp;id='.$data['mp_id'].'&amp;sur=0">supprimer</a></td></tr>';
				} //Fin de la boucle
				$query->CloseCursor();
				echo '</table>';

			} //Fin du if
			else
			{
				echo'<p>Vous n avez aucun message privé pour l instant, cliquez
				<a href="./Forum.php?f=1">ici</a> pour revenir à la page d index</p>';
			}
		} //Fin du switch
		?>

</body>
</html>


		




			
    

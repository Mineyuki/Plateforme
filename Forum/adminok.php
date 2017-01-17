<?
require('Fonctions.php');
require('config.php');
require('constants.php');

$cat = htmlspecialchars($_GET['cat']); //on récupère dans l'url la variable cat
switch($cat) //1er switch
{
	case "config":
		echo'<h1>Configuration du forum</h1>';
		//On récupère les valeurs et le nom de chaque entrée de la table
		$query=$bd->query('SELECT config_nom, config_valeur FROM forum_config');
		//Avec cette boucle, on va pouvoir contrôler le résultat pour voir s'il a changé
		while($data = $query->fetch())
		{
			if ($data['config_valeur'] != $_POST[$data['config_nom']])
		{
				//On met ensuite à jour
				$valeur = htmlspecialchars($_POST[$data['config_nom']]);
			$query=$bd->prepare('UPDATE forum_config SET config_valeur = :valeur
				WHERE config_nom = :nom');
				$query->bindValue(':valeur', $valeur, PDO::PARAM_STR);
				$query->bindValue(':nom', $data['config_nom'],PDO::PARAM_STR);
				$query->execute();
		}
		}
		$query->CloseCursor();
		//Et le message !
		echo'<br /><br />Les nouvelles configurations ont été mises à jour !<br />  
		Cliquez <a href="./admin.php">ici</a> pour revenir à l administration';
	break;
	
		
		case "droits":    
			//Récupération d'informations
			$auth_view = (int) $_POST['auth_view'];
			$auth_post = (int) $_POST['auth_post'];
			$auth_topic = (int) $_POST['auth_topic'];
			$auth_annonce = (int) $_POST['auth_annonce'];
			$auth_modo = (int) $_POST['auth_modo'];
			
			//Mise à jour
			$query=$bd->prepare('UPDATE forum_forum
			SET auth_view = :view, auth_post = :post, auth_topic = :topic,
			auth_annonce = :annonce, auth_modo = :modo WHERE forum_id = :id');
			$query->bindValue(':view',$auth_view,PDO::PARAM_INT);
			$query->bindValue(':post',$auth_post,PDO::PARAM_INT);
			$query->bindValue(':topic',$auth_topic,PDO::PARAM_INT);
			$query->bindValue(':annonce',$auth_annonce,PDO::PARAM_INT);
			$query->bindValue(':modo',$auth_modo,PDO::PARAM_INT);
			$query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
			$query->execute();
			$query->CloseCursor();
		  
			//Message
			echo'<p>Les droits ont été modifiés !<br />
			Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
		break;
		} //Fin du switch
	break; 
	case "membres":
		case "droits":
		$membre =$_POST['pseudo'];
		$rang = (int) $_POST['droits'];
		$query=$bd->prepare('UPDATE forum_membres SET membre_rang = :rang
		WHERE LOWER(membre_pseudo) = :pseudo');
			$query->bindValue(':rang',$rang,PDO::PARAM_INT);
			$query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
			$query->execute();
			$query->CloseCursor();
		echo'<p>Le niveau du membre a été modifié !<br />
		Cliquez <a href="./admin.php">ici</a> pour revenir à l administration</p>';
		break;
		
		case "ban":
			//Bannissement dans un premier temps
			//Si jamais on n'a pas laissé vide le champ pour le pseudo
			if (isset($_POST['membre']) AND !empty($_POST['membre']))
			{
				$membre = $_POST['membre'];
				$query=$bd->prepare('SELECT membre_id 
				FROM forum_membres WHERE LOWER(membre_pseudo) = :pseudo');    
				$query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
				$query->execute();
				//Si le membre existe
				if ($data = $query->fetch())
				{
					//On le bannit
					$query=$bd->prepare('UPDATE forum_membres SET membre_rang = 0 
					WHERE membre_id = :id');
					$query->bindValue(':id',$data['membre_id'], PDO::PARAM_INT);
					$query->execute();
					$query->CloseCursor();
					echo'<br /><br />
					Le membre '.stripslashes(htmlspecialchars($membre)).' a bien été banni !<br />';
				}
				else 
				{
					echo'<p>Désolé, le membre '.stripslashes(htmlspecialchars($membre)).' n existe pas !
					<br />
					Cliquez <a href="./admin.php?cat=membres&action=ban">ici</a> 
					pour réessayer</p>';
				}
			}
			//Debannissement ici        
			$query = $bd->query('SELECT membre_id FROM forum_membres 
			WHERE membre_rang = 0');
			//Si on veut débannir au moins un membre
			if ($query->rowCount() > 0)
			{
				$i=0;
				while($data= $query->fetch())
				{
					if(isset($_POST[$data'membre_id']]))
					{
						$i++;
						//On remet son rang à 2
						$query=$bd->prepare('UPDATE forum_membres SET membre_rang = 2 
						WHERE membre_id = :id');
						$query->bindValue(':id',$data['membre_id'],PDO::PARAM_INT);
						$query->execute();
						$query->CloseCursor();
					}
				}
				if ($i!=0)
				{
					echo'<p>Les membres ont été débannis<br />
					Cliquez <a href="./admin.php">ici</a> pour retourner à l administration</p>';
				}
			}
		break;
	break;
}
			






?>
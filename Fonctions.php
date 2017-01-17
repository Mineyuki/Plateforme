<?php

require('cnx.php');

class Date{
	
	var $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	var $months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novemebre', 'Décembre');
	
	function getEventsDest($year, $mail){
		global $bd;
		$req = $bd->prepare('select id, title, dateh, mailDest from events where year(dateh)= :year and mailDest = :mail');
		$req->bindValue(':year', $year);
		$req->bindValue(':mail', $mail);
		$req->execute();
		$r = array();
		while($d = $req->fetch(PDO::FETCH_OBJ)){
			$r[strtotime($d->dateh)][$d->id] = $d->title;
		}
		return $r;
	}
	
	function getEventsExpe($year, $mail){
		global $bd;
		$req = $bd->prepare('select id, title, dateh, mailExpe from events where year(dateh)= :year and mailExpe = :mail');
		$req->bindValue(':year', $year);
		$req->bindValue(':mail', $mail);
		$req->execute();
		$r = array();
		while($d = $req->fetch(PDO::FETCH_OBJ)){
			$r[strtotime($d->dateh)][$d->id] = $d->title;
		}
		return $r;
	}
	
	/*	fonction permettant de récuperer le calendrier d'une année passé en paramètre */
	
	function getAll($year){
		$r = array();
		
		$date = new DateTime($year.'-01-01');
		
		while($date->format('Y') <= $year){
			/*	On récupère l'année sur 4 chiffres xxxx */
			$y = $date->format('Y');
			
			/*	On récupère le mois sans le zéro pour les mois à un chiffre */
			$m = $date->format('n');
			
			/*	On récupère le jour sans le zéro pour les 9 premiers jours */
			$d = $date->format('j');
			
			/* Permet d'avoir les jours de la semaine qui vont de 1 à 7 et pas de 0 à 6*/
			$w = str_replace('0', '7',$date->format('w'));
			
			/* On stocke dans le tableau que le retournera à la fin de la boucle les différents jours de la semaine*/ 
			$r[$y][$m][$d] = $w;
			
			/* Permet l'incrémentation d'un jour dans la boucle pour faire tout les jours du calendrier*/
			$date->add(new DateInterval('P1D'));
		}
		return $r;
	}
	
}


/*fonction pour les événements personelle */
function ajouterEventPerso($date, $title, $mail, $time){
	global $bd;
	try{
		$reqID = $bd->prepare('select LAST_INSERT_ID() as id from events');
		$reqID->execute();
		$res = $reqID->fetch(PDO::FETCH_NUM);
		$req = $bd->prepare('insert into events (dateh, title, mailDest, heure) values ( :dateh, :title, :mail, :time)');
		$req->bindValue(':dateh', $date);
		$req->bindValue(':title', $title);
		$req->bindValue(':time', $time);
		$req->bindValue(':mail', $mail);
		$req->execute();
		
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}


function ajouterEventAutre($date, $title, $time,$mailProf, $mailEtu){
	global $bd;
	try{
		$reqID = $bd->prepare('select LAST_INSERT_ID() as id from events');
		$reqID->execute();
		$res = $reqID->fetch(PDO::FETCH_NUM);
		$req = $bd->prepare('insert into events (dateh, title, heure, mailDest, mailExpe) values ( :dateh, :title, :time, :mailDest, :mailExpe)');
		$req->bindValue(':dateh', $date);
		$req->bindValue(':title', $title);
		$req->bindValue(':time', $heure);
		$req->bindValue(':mailDest', $mailEtu);
		$req->bindValue(':mailExpe', $mailProf);
		$req->execute();
		
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}


function genereListeEtudiant($forma){
	global $bd;
	try{
		$req = $bd->prepare('select distinct mail from membres where categorie ="etudiant" and formation = :formation');
		$req->bindValue(':formation', $forma);
		$req->execute();
		echo '<select name="selection">';
		$i=0;
		do{
			$res = $req->fetch(PDO::FETCH_NUM);
			
			if($res[0] != '')
				echo '<option value='. $res[0] .'> '. $res[0] . '</option>';
			
		}while($res != false);
		echo '</select>';
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}

function genereListeProfesseur($forma){
	global $bd;
	try{
		$req = $bd->prepare('select distinct mail from membres where categorie ="professeur" and formation = :formation');
		$req->bindValue(':formation', $forma);
		$req->execute();
		echo '<select name="selection">';
		$i=0;
		do{
			$res = $req->fetch(PDO::FETCH_NUM);
			
			if($res[0] != '')
				echo '<option value='. $res[0] .'> '. $res[0] . '</option>';
			
		}while($res != false);
		echo '</select>';
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}



function notificationMeeting(){
	global $bd;
	try{
		$req = $bd->prepare('select * from events');
		$req->execute();
		while($res = $req->fetch(PDO::FETCH_ASSOC) != false){
			if(time() - 3600*24*3 >= strtotime($res['DateH']) && $res['sent'] == 0){
				$query = $bd->prepare('select nom, prenom from membres where mail = :mail');
				$query->bindValue(':mail', $res['mailExpe']);
				$query->execute();
				$t = $query->fetch(PDO::FETCH_ASSOC);
				
				
				$mail = $res['mailDest']; // Déclaration de l'adresse de destination.
				if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
				{
					$passage_ligne = "\r\n";
				}
				else
				{
					$passage_ligne = "\n";
				}
				//=====Déclaration des messages au format texte.
				$message_txt = 'Bonjour,
								Prière de se présenter le '. $res['DateH'] .' au bureau de '. $t['nom'] .'.

								Message généré le '. date('d-m-Y') .' par le système de rendez-vous.';
				//==========
				 
				//=====Création de la boundary
				$boundary = "-----=".md5(rand());
				//==========
				 
				//=====Définition du sujet.
				$sujet = "Rappel: 3 jours restants";
				//=========
				 
				//=====Création du header de l'e-mail.
				$header = 'From: \"'. $t['nom'] . ' ' . $t['prenom'] .'\" <'.$res['mailExpe'].'>'.$passage_ligne;
				$header.= 'Reply-to: \"'. $t['nom'] . ' ' .  $t['prenom'] .'\" <'.$res['mailExpe'].'>'.$passage_ligne;
				$header.= "MIME-Version: 1.0".$passage_ligne;
				$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
				//==========
				 
				//=====Création du message.
				$message = $passage_ligne."--".$boundary.$passage_ligne;
				//=====Ajout du message au format texte.
				$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
				$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
				$message.= $passage_ligne.$message_txt.$passage_ligne;
				//==========
				$message.= $passage_ligne."--".$boundary.$passage_ligne;
				//=====Envoi de l'e-mail.
				mail($mail,$sujet,$message,$header);
				//==========
				

				



				
				
				
				$update = $bd->prepare('update events set sent = 1 where id = :id');
				$update->bindValue(':id', $res['id']);
				$update->execute();
			}
		}
		
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}


function code($texte)
{
	//Smileys
	$texte = str_replace(':D ', '<img src="./image/smileys/heureux.gif" title="heureux" alt="heureux" />', $texte);
	$texte = str_replace(':lol: ', '<img src="./image/smileys/lol.gif" title="lol" alt="lol" />', $texte);
	$texte = str_replace(':triste:', '<img src="./image/smileys/triste.gif" title="triste" alt="triste" />', $texte);
	$texte = str_replace(':frime:', '<img src="./image/smileys/cool.gif" title="cool" alt="cool" />', $texte);
	$texte = str_replace(':rire:', '<img src="./image/smileys/rire.gif" title="rire" alt="rire" />', $texte);
	$texte = str_replace(':s', '<img src="./image/smileys/confus.gif" title="confus" alt="confus" />', $texte);
	$texte = str_replace(':O', '<img src="./image/smileys/choc.gif" title="choc" alt="choc" />', $texte);
	$texte = str_replace(':question:', '<img src="./image/smileys/question.gif" title="?" alt="?" />', $texte);
	$texte = str_replace(':exclamation:', '<img src="./image/smileys/exclamation.gif" title="!" alt="!" />', $texte);

	//Mise en forme du texte
	//gras
	$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte); 

	//italique
	$texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $texte);

	//souligné
	$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);

	//lien
	$texte = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $texte);
		
	//citer quelqu'un.
	$texte = preg_replace('`\[quote auteur=([a-z0-9A-Z._-]+) \](.+)\[/quote\]`isU', '<div id="quote">Auteur : $1 </ br> $2 </div>', $texte);

	

	//On retourne la variable texte
	return $texte;
}


function verif_auth($auth_necessaire)
{
	$level=(isset($_SESSION['level']))?$_SESSION['level']:1;
	return ($auth_necessaire <= intval($level));
}


function erreur($err='')
{
   $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
   exit('<p>'.$mess.'</p>
   <p>Cliquez <a href="./Accueil.php">ici</a> pour revenir à la page d\'accueil</p></body></html>');
}

//Fonction listant les pages
function get_list_page($page, $nb_page, $link, $nb = 2){
	$list_page = array();
	for ($i=1; $i <= $nb_page; $i++){
		if (($i < $nb) OR ($i > $nb_page - $nb) OR (($i < $page + $nb) AND ($i > $page -$nb)))
			$list_page[] = ($i==$page)?'<strong>'.$i.'</strong>':'<a href="'.$link.'&amp;page='.$i.'">'.$i.'</a>'; 
		else{
			if ($i >= $nb AND $i <= $page - $nb)
				$i = $page - $nb;
			elseif ($i >= $page + $nb AND $i <= $nb_page - $nb)
				$i = $nb_page - $nb;
		$list_page[] = '...';
		}
	}
	$print= implode('-', $list_page);
	return $print;
}

function move_avatar($avatar)
{
    $extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
    $name = time();
    $nomavatar = str_replace(' ','',$name).".".$extension_upload;
    $name = "./images/avatars/".str_replace(' ','',$name).".".$extension_upload;
    move_uploaded_file($avatar['tmp_name'],$name);
    return $nomavatar;
}
?>






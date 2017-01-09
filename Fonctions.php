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
	
	function getAll($year){
		$r = array();
		/*$date = strtotime($year.'-01-01');
		
		while(date('Y', $date) <= $year){
			$y = date('Y', $date);
			$m = date('n', $date);
			$d = date('j', $date);
			$w = str_replace('0', '7',date('w', $date));
			$r[$y][$m][$d] = $w;
			$date =  strtotime(date('Y-m-d', $date).' +1 Day');
			*/
		
		$date = new DateTime($year.'-01-01');
		
		while($date->format('Y') <= $year){
			$y = $date->format('Y');
			$m = $date->format('n');
			$d = $date->format('j');
			$w = str_replace('0', '7',$date->format('w'));
			$r[$y][$m][$d] = $w;
			$date->add(new DateInterval('P1D'));
		}
		return $r;
	}
	
}


/*fonction pour les événements d'étudiants */
function ajouterEventPerso($date, $title, $mail){
	global $bd;
	try{
		$reqID = $bd->prepare('select LAST_INSERT_ID() as id from events');
		$reqID->execute();
		$res = $reqID->fetch(PDO::FETCH_NUM);
		echo $res[0];
		$id = $res[0]+1;
		$req = $bd->prepare('insert into events (dateh, title, mailDest) values ( :dateh, :title, :mail)');
		$req->bindValue(':dateh', $date);
		$req->bindValue(':title', $title);
		$req->bindValue(':mail', $mail);
		$req->execute();
		
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}


function ajouterEventProfesseur($date, $title, $mailProf, $mailEtu){
	global $bd;
	try{
		$reqID = $bd->prepare('select LAST_INSERT_ID() as id from events');
		$reqID->execute();
		$res = $reqID->fetch(PDO::FETCH_NUM);
		$req = $bd->prepare('insert into events (dateh, title, mailDest, mailExpe) values ( :dateh, :title, :mailDest, :mailExpe)');
		$req->bindValue(':dateh', $date);
		$req->bindValue(':title', $title);
		$req->bindValue(':mailDest', $mailEtu);
		$req->bindValue(':mailExpe', $mailProf);
		$req->execute();
		
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}


function genereListeEtudiant(){
	global $bd;
	try{
		$req = $bd->prepare('select distinct mail from membres where categorie ="etudiant"');
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
	//etc., etc.
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
?>






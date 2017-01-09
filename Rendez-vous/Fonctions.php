<?php

require('../co.php');

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

?>

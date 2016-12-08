<?php

require('connexion.php');

class Date{
	
	var $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	var $months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novemebre', 'Décembre');
	
	function getEvents($year){
		global $bd;
		$req = $bd->prepare('select id, title, date from events where year(date)= :year');
		$req->bindValue(':year', $year);
		$req->execute();
		$r = array();
		while($d = $req->fetch(PDO::FETCH_OBJ)){
			$r[strtotime($d->date)][$d->id] = $d->title;
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

function ajouterEvent($date, $title){
	global $bd;
	try{
		$reqID = $bd->prepare('select LAST_INSERT_ID() as id from events');
		$reqID->execute();
		$res = $reqID->fetch(PDO::FETCH_NUM);
		echo $res[0];
		$id = $res[0]+1;
		$req = $bd->prepare('insert into events (date, title) values ( :date, :title)');
		$req->bindValue(':date', $date);
		$req->bindValue(':title', $title);
		$req->execute();
		
	}
	catch(PDOException $e){
		die('<p> La connexion a échoué. Erreur['.$e->getCode().'] : '.$e->getMessage().'</p>');
	}
}




?>
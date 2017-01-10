<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">

<!--
*************************************************************************************************************************************************
		HEAD
*************************************************************************************************************************************************
-->
	<head>
	<!-- Utilisation UTF-8 -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Nom des onglets -->
		<title>Rendez-vous</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/bootstrap.js"></script>

		<script type="text/javascript">
			jQuery(function($){
				$('.month').hide();
				$('.month:first').show();
				$('.months a:first').addClass('active');
				var current =1;
				$('.months a').click(function(){
					var month = $(this).attr('id').replace('linkMonth','');
					if(month != current){
						$('#month'+current).slideUp();
						$('#month'+month).slideDown();
						$('.months a').removeClass('active');
						$('.months a#linkMonth'+month).addClass('active');
						current = month;
						
					}
					return false;
				});
			});
			
			function radioclick (b) {
				document.getElementById('div1').style.display = (b=="vous"? 'none':'block');
				document.getElementById('div2').style.display = (b=="gEtudiant"? 'none':'block');

			}	
		</script>
<?php 
	require('body.php');

	if(!isset($_SESSION['connexion']))
	{
		echo"<script>
			document.location.href=\"../Connexion.php\"
		</script>";
	}

	require('../co.php');
	require('Fonctions.php');

	$date = new Date();
	$year = date('Y');
	//$eventsDest = $date->getEventsDest($year, $_SESSION['mail']);
	//$eventsExpe = $date->getEventsExpe($year, $_SESSION['mail']);
	echo '<p> '. print_r($eventsDest) .' </p>';
	echo '<p> '. print_r($eventsExpe) .' </p>';
	$dates = $date->getAll($year);
	print_r($_SESSION);

?>
	
	<div class="row">
	<div class="col-md-3 formEvent" style="padding-top: 100px; padding-left:40px;">
	
	<h3>Ajouter un événement</h3>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<p >
				
				Date <br> <input type="text" id="date" name="date" readonly ><br>
				Heure <input type="time" name="time" style="margin-top: 8px;"></br>
				
				<?php /* Les possibilités*/
					if($_SESSION['categorie'] == 'professeur'){
				?>
				<p style="padding-top: 8px; padding-left: 8px;">
				Evenement pour: <br>
				vous <input type="radio" name="categ" value="vous" onclick="radioclick(this.value);" /><br>
				etudiant <input type="radio" name="categ" value="gEtudiant" onclick="radioclick(this.value);" /><br>
				</p>
				<p  id="div1" style="display: none; margin-left: 30px;">
					<?php genereListeEtudiant(); ?>
				</p>
				
				<?php } ?>
				nom de l'événement <br> <input type="text" name="event"><br>
				<input type="submit" value="soumettre">
				
				
			</p>
		</form>
	</div>
	
		<div class="col-md-9">
			<!--		a rajouter: le fait de pouvoir changer de mois en appuyant sur une fleche -->
			<div class="year">
				<?php echo $year; ?>
			</div>
			
					<div class="months">
						<ul >
						<?php 
							foreach($date->months as $id =>$m){?>
							<li ><a href="#" id="linkMonth<?php echo $id+1; ?>"><?php echo utf8_encode(substr(utf8_decode($m),0,3));?></a></li>
						<?php } ?>
						</ul>
					</div>
			<div class="clear"></div>
				<?php foreach($dates[$year] as $m => $days){ ?>
					<div class="month" id="month<?php echo $m; ?>">
						<table class="calendrier">
						<!-- affiche les 3 premieres lettres de chaque jour dans l'entete --> 
							<tr>
								<?php foreach($date->days as $d){ ?>
									<th class="nomJour"><?php echo substr($d,0,3); ?></th>
								<?php } ?>
							</tr>
						<!-- permet de mettre le premier jour au bonne endroit sur le calendrier -->
							<tr>
								<?php $end = end($days); foreach($days as $d => $w){ ?>
									<?php $time = strtotime("$year-$m-$d"); ?>
									<?php if($d ==1){ ?>
										<td class="padding case" colspan="<?php echo $w-1; ?>"></td>
									<?php }?>
									
						<!-- affiche le jour et permet ensuite de sauter une ligne quand on arrive a dimanche -->
									<td class="case">
										<div class="relative">
										<div class="day">
										<a onclick="document.getElementById('date').value='<?php echo $year ."-" .$m. "-".$d; ?>';" >
										<?php echo $d; ?>
										</div>
										</div>
										<div class="daytitle">
											<?php echo $date->days[$w-1]; ?> <?php echo $d; ?> <?php echo $date->months[$m-1]; ?>
										</div>
											<ul class="events" style="color: brown;" >
												<?php if(isset($eventsDest[$time])){
													foreach($eventsDest[$time] as $e){ ?>
														<li class="destination listeJour"><?php echo $e; ?></li>
												<?php }
												} ?>
											</ul>
											<ul class="events" style="color: green;">
												<?php if(isset($eventsExpe[$time])){
													foreach($eventsExpe[$time] as $e){ ?>
														<li class="expedition listeJour"><?php echo $e; ?></li>
												<?php }
												} ?>
											</ul>
										</a>
										
									</td>
									
								<?php if($w == 7) echo '</tr><tr>'; } ?>
								
								<?php 
								if($end != 7){ ?>
									<td class="padding" colspan="<?php echo 7-$end; ?>"></td>
								<?php } ?>
							</tr>
						</table>
					</div>
				<?php } ?>
		
			
		</div>
	
	</div>
	
<?php
print_r($_POST);
	
	if(isset($_POST['date']) && isset($_POST['event']) && isset($_POST['categ']) && isset($_POST['selection']) && $_POST['time']){
		if(trim($_POST['date']) != '' && trim($_POST['event']) != ''){
			$title = htmlspecialchars($_POST['event'], ENT_NOQUOTES);
			$mailUtilisateur = htmlspecialchars($_SESSION['email'], ENT_NOQUOTES);
			
			if($_SESSION['categorie'] =="etudiant" || $_POST['categ'] =="vous"){
				ajouterEventPerso($_POST['date'], $title, $mailUtilisateur);
			}
			else if(trim($_POST['categ']) != '' && trim($_POST['selection']) != ''){
				ajouterEventProfesseur($_POST['date'], $title, $mailUtilisateur, $_POST['selection']);
			}
			
		}
	}
	
	


	
	
/*	echo '<p> '. print_r($events) .'</p>';
	echo '<p> '. print_r($dates) .'</p>';
	*/
	/*
		39:47
	*/


?>

<?php require('footer.php');?>

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
				document.getElementById('div3').style.display = (b? 'none':'block');
				document.getElementById('div4').style.display = (b? 'none':'block');
			}	
		</script>
<?php require('body.php');?>

<?php			
	if(!isset($_SESSION['connexion']))
	{
		echo"<script>
			document.location.href=\"../Connexion.php\"
		</script>";
	}
?>

<?php
require('../co.php');
require('Fonctions.php');

	$date = new Date();
	$year = date('Y');
	$events = $date->getEvents($year);
	$dates = $date->getAll($year);
?>
	
	<div class="row">
		<div class="col-md-3 formEvent" style="padding-top: 200px; padding-left:40px;">
	
			<h3>Ajouter un événement</h3>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<p>Date<br />
				<input type="text" id="date" name="date" readonly ><br />
				Heure<br /> 
				<input type="time" name="time" style="margin-top: 8px;"><br />				
				<?php /* Les possibilités*/
					if($_SESSION['categorie'] == 'professeur'){
				?>
				<p style="padding-top: 8px; padding-left: 8px;">
				Evenement pour: <br />
				vous 
				<input type="radio" name="categ" value="vous" onclick="radioclick(this.value);" /><br />
				etudiant 
				<input type="radio" name="categ" value="gEtudiant" onclick="radioclick(this.value);" /><br />
				</p>
				<p id="div1" style="display: none; padding-left: 30px;">
				un seul étudiant 
				<input type="radio" name="pOm" value="seul" onclick="radioclick(true);" /><br />
				en groupe		 
				<input type="radio" name="pOm" value="groupe" onclick="radioclick(true);"/><br />
				</p>
				<select id="div3" name="seul" style="display: none;">
					<option value="1"> value1 </option>
				</select>
				<select id="div4" name="groupe" style="display: none;">
					<option value="1"> value2 </option>
				</select>				
				<?php } ?>
				Nom de l'événement<br />
				<input type="text" name="event"><br />
				<input type="submit" class="btn btn-default" value="Soumettre">				
				</p>
			</form>
		</div>
	
		<div class="col-md-6">
			<!-- a rajouter: le fait de pouvoir changer de mois en appuyant sur une fleche -->
			<div class="year">
				<?php echo $year; ?>
			</div>			
			<div class="months">
				<ul>
				<?php 
					foreach($date->months as $id =>$m){?>
						<li>
							<a href="#" id="linkMonth<?php echo $id+1; ?>"><?php echo utf8_encode(substr(utf8_decode($m),0,3));?></a>
						</li>
				<?php } ?>
				</ul>
			</div>
			<div class="clear"></div>
				<?php foreach($dates[$year] as $m => $days){ ?>
					<div class="month" id="month<?php echo $m; ?>">
						<table>
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
										<td class="padding" colspan="<?php echo $w-1; ?>"></td>
									<?php }?>
									
						<!-- affiche le jour et permet ensuite de sauter une ligne quand on arrive a dimanche -->
									<td class="day">
										<div class="relative">
											<a onclick="document.getElementById('date').value='<?php echo $year ."-" .$m. "-".$d; ?>';" >
											<?php echo $d; ?>
												<ul class="events">
													<?php if(isset($events[$time])){
														foreach($events[$time] as $e){ ?>
															<li><?php echo $e; ?></li>
													<?php }
													} ?>
												</ul>
											</a>
										</div>
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
		<div class="col-md-3 "></div>
	</div>
	
<?php
	if(isset($_POST['date']) && isset($_POST['event'])){
		if(trim($_POST['date']) != false && trim($_POST['event']) != false)
				ajouterEvent($_POST['date'], $_POST['event']);
	}
/*	echo '<p> '. print_r($events) .'</p>';
	echo '<p> '. print_r($dates) .'</p>';
	*/
	/*
		39:47
	*/
?>

<?php require('footer.php');?>

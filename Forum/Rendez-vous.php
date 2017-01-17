<?php session_start(); ?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Rendez-vous </title>
		<link rel="stylesheet" href="../css/provisoire.css"/>
		<link rel="stylesheet" href="../css/bootstrap.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<script type="text/javascript">
		/* Fonction permettant de faire défiler les mois du calendrier*/
			jQuery(function($){
				/* permet de cacher tout les tableaux */
				$('.month').hide();
				
				/*	affiche le tableau correspondant au premier mois et de demarquer sa couleur des autres */
				$('.month:first').show();
				$('.months a:first').addClass('active');
				
				/* stocke le mois courrant */
				var current =1;
				
				/*	Fonction qui va permettre en cliquant sur un mois de l'afficher */
				$('.months a').click(function(){
					/* On recupère le mois sur lequel on a cliqué  */
					var month = $(this).attr('id').replace('linkMonth','');
					/* si on a cliqué... */
					if(month != current){
						/* masque le mois actuellement visible */
						$('#month'+current).slideUp();
						
						/* affiche le mois sur lequel on a cliqué */
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
	</head>
	<body>

<?php
require('cnx.php');
require('Fonctions.php');

	$date = new Date();
	$year = date('Y');
	$eventsDest = $date->getEventsDest($year, $_SESSION['email']);
	$eventsExpe = $date->getEventsExpe($year, $_SESSION['email']);
	$dates = $date->getAll($year);
	
?>
	<?php require('Navigation.php'); ?>
	
	<div class="row">
	<div class="col-md-3 formEvent" style="padding-top: 100px; padding-left:40px;">
	
	<h3>Ajouter un événement</h3>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<p >
				
				Date <br> <input type="text" id="date" name="date" readonly ><br>
				Heure <input type="time" id="heure" name="heure" style="margin-top: 8px;"></br>
				
				
				<p style="padding-top: 8px; padding-left: 8px;">
				Evenement pour: <br>
				vous <input type="radio" name="categ" value="vous" onclick="radioclick(this.value);" /><br>
				<?php /* Les possibilités*/
					if($_SESSION['categorie'] == 'professeur'){
				?>
				Etudiant <input type="radio" name="categ" value="gEtudiant" onclick="radioclick(this.value);" /><br>
				</p>
				<p  id="div1" style="display: none; margin-left: 30px;">
					<?php genereListeEtudiant($_SESSION['formation']); ?>
				</p>
				
				<?php } 
					else if($_SESSION['categorie'] == "etudiant"){
						?>
				Professeur<input type="radio" name="categ" value="gEtudiant" onclick="radioclick(this.value);" /><br>
				</p>
				<p  id="div1" style="display: none; margin-left: 30px;">
					<?php genereListeProfesseur($_SESSION['formation']); ?>
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
									
									<?php if($d ==1 && $w-1 != 0){ ?>
										<td class="padding case" colspan="<?php echo $w-1; ?>"></td>
									<?php }
										  elseif($d==1){?>
										<td class="padding case" ></td>
										  <?php } ?>
									
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
											<ul class="events" >
												<?php if(isset($eventsDest[$time])){
													foreach($eventsDest[$time] as $e){ ?>
														<li class="destination listeJour"><?php echo $e; ?></li>
												<?php }
												} ?>
											</ul>
											<ul class="events" >
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
	if(isset($_POST['date']) && isset($_POST['event']) && isset($_POST['categ']) && isset($_POST['heure'])){
		if(trim($_POST['date']) != '' && trim($_POST['event']) != ''){
			if(trim($_POST['heure']) != '')
				$heure = $_POST['heure'] . ':0';
			else
				$heure = null;
			
			$title = htmlspecialchars($_POST['event'], ENT_NOQUOTES);
			$mailUtilisateur = htmlspecialchars($_SESSION['email'], ENT_NOQUOTES);
			
			if($_POST['categ'] =="vous"){
				if(time() <= strtotime($_POST['date']))
					ajouterEventPerso($_POST['date'], $title, $mailUtilisateur, $heure);
				else{
					echo "Veuillez ajouter un événement a une date ultérieur a celle d'aujourd'hui";
				}
			}
			else if(trim($_POST['categ']) != '' && trim($_POST['selection']) != ''){
				
				$query = $bd->prepare('select COUNT(*) as nbr, heure, date from events where mailDest = :mDest, date = :date ');
				$query->bindValue(':mDest', $_POST['selection']);
				$query->bindValue(':mDest', $_POST['date']);
				$query->execute();
				$evenement = $query->fetch(PDO::FETCH_ASSOC);
				if($evenement['nbr'] > 0 ){
					echo '<p> Veuillez choisir un jour plus tôt ou plus tard car cette personne est déjà prise </p>'; 
				}
				else{
					if(time() < strtotime($_POST['date'])){
						ajouterEventAutre($_POST['date'], $title, $heure, $mailUtilisateur, $_POST['selection']);
						/*notifRendez_vous($_POST['date'], $heure, $title, $mailUtilisateur, $_POST['selection']);*/
					}
					else
						echo "Veuillez ajouter un événement a une date ultérieur a celle d'aujourd'hui";
				}
				
			}
			
		}
	}

?>

</body>
</html>

<?php require('Footer.php'); ?>

<?php session_start(); 

?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Calendrier </title>
		<link rel="stylesheet" href="Projet.css"/>
		<link rel="stylesheet" href="provisoire.css"/>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
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

			}	
		</script>
	</head>
	<body>

<?php
require('cnx.php');
require('Fonctions.php');

	$date = new Date();
	$year = date('Y');
	$events = $date->getEvents($year);
	$dates = $date->getAll($year);
	
?>
	<?php require('Navigation.php'); ?>
	
	<div class="row">
	<div class="col-md-3 formEvent" style="padding-top: 100px; padding-left:40px;">
	
	<h3>Ajouter un événement</h3>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<p >
				
				Date <br> <input type="text" id="date" name="date" readonly ><br>
				
				
				<?php /* Les possibilités*/
					if($_SESSION['categorie'] == 'professeur'){
				?>
				<p style="padding-top: 8px; padding-left: 8px;">
				Evenement pour: <br>
				vous <input type="radio" name="categ" value="vous" onclick="radioclick(this.value);" /><br>
				etudiant <input type="radio" name="categ" value="gEtudiant" onclick="radioclick(this.value);" /><br>
				</p>
				<select id="div1" name="selection" style="display: none; margin-left: 30px;">
					<option value="grp1"> groupe 1 </option>
					<option value="grp2"> groupe 2 </option>
					<option value="etd1"> etudiant 1 </option>
					<option value="..."> ... </option>
				</select>
				
				
				<?php } ?>
				nom de l'événement <br> <input type="text" name="event"><br>
				<input type="submit" value="soumettre">
				
				
			</p>
		</form>
	</div>
	
		<div class="col-md-9">
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
									<td >
										<div class="relative">
										<div class="day">
										<a onclick="document.getElementById('date').value='<?php echo $year ."-" .$m. "-".$d; ?>';" >
										<?php echo $d; ?>
										</div>
										</div>
										<div class="daytitle">
											<?php echo $date->days[$w-1]; ?> <?php echo $d; ?> <?php echo $date->months[$m-1]; ?>
										</div>
											<ul class="events">
												<?php if(isset($events[$time])){
													foreach($events[$time] as $e){ ?>
														<li><?php echo $e; ?></li>
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
	
	if(isset($_POST['date']) && isset($_POST['event'])){
		if(trim($_POST['date']) != false && trim($_POST['event']) != false){
			
				ajouterEvent($_POST['date'], $_POST['event']);
			
		}
	}


	
	
/*	echo '<p> '. print_r($events) .'</p>';
	echo '<p> '. print_r($dates) .'</p>';
	*/
	/*
		39:47
	*/


?>

</body>
</html>

<?php require('Footer.php'); ?>

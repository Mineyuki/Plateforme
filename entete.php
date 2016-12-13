<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
		
		<title>Diplôme	Interuniversitaire</title>
		<link href="provisoire.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet" >
		<link rel="shortcut icon" href="image/Logo_IUT_Villetaneuse.png"/>

		<script src="bootstrap/js/bootstrap-dropdown.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootstrap/js/bootstrap.js"></script>
		<link href="Projet.css" rel="stylesheet" >
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
	</head>
	<body>
		
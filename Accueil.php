<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Diplôme	Interuniversitaire</title>
<?php require('body.php');?>

		<!--start slider-->
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					
					<!--Indicators dot nov-->
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="2"></li>
					</ol>
						
					<!--wrapper for slides -->
					<div class="carousel-inner">
						
						<div class="item active">
							<img class="img-responsive" src="image/formation-continue-financement.jpg" alt="Financement" />
								
							<div class="carousel-caption">
								<h1>Actualité</h1>
								<a class="btn btn-large btn-primary" href="#">
								Lire la suite...
								</a>
							</div>
						
						</div>
						
						<div class="item">
							<img class="img-responsive" src="image/statut-stagiaire-fc.jpg" alt="Stagiaire" />
							
							<div class="carousel-caption">
								<h1>Actualité</h1>
								<a class="btn btn-large btn-primary" href="#">
								Lire la suite...
								</a>
							</div>
						
						</div>
						
						<div class="item">
							<img class="img-responsive" src="image/entreprise-page.jpg" alt="Entreprise" />
							
							<div class="carousel-caption">
								<h1>Actualité</h1>
								<a class="btn btn-large btn-primary" href="#">
								Lire la suite...
								</a>
							</div>
						
						</div>
				
					</div>
					
					<!--Controls-->
					<a class="left carousel-control" href="#myCarousel" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
					<a class="right carousel-control" href="#myCarousel" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
									
				</div>
				
			</div>
				
		</div>
		
		<!--end of slider-->

		<!--start main-->
	
		<div class="container">
			
			<div class='row'>

				<div>
					<h1 class='text-success'>Les Formations</h1>
					<hr>

					<!-- Utilisation méthode Flexbox -->
					<div id="conteneur">
						<a href="Formation/DIU.php" class="element">Modulaire et Diplômante Interuniversitaire</a>
						<a href="Formation/GCRHM/GCRHM.php" class="element">Gestion, Comptabilité, Ressources Humaines et Management</a>
						<a href="Formation/CJ.php" class="element">Juridique, Notariat et Finance</a>
						<a href="Formation/INFO.php" class="element">Informatique, Systèmes et Logiciels</a>
						<a href="Formation/RT.php" class="element">Réseaux et Télécommunications</a>
						<a href="Formation/GEII.php" class="element">Electronique, Electricité, Informatique Industrielle et Nanotechnologies</a>
					</div>
				</div>
  
			</div>
       
		</div>
<?php require('footer.php'); ?>

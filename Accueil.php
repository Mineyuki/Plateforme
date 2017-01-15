<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Diplôme	Interuniversitaire</title>
<?php require('body.php');?>

		<!--start slider-->
		
		<div class="row">
			
			<div class="col-md-12">
				
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					
					<!--Indicators dot nov-->
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="3"></li>
						<li data-target="#myCarousel" data-slide-to="4"></li>
						<li data-target="#myCarousel" data-slide-to="5"></li>
					</ol>
						
					<!--wrapper for slides -->
					<div class="carousel-inner">
					<?php
						require('co.php');
						$request = 'SELECT id_article, titre, image, corps FROM Article WHERE validation=1 ORDER BY id_article DESC LIMIT 5 OFFSET 0';
						$requete = $bd->prepare($request);
						$requete->execute();
						$article = $requete->fetch(PDO::FETCH_ASSOC);
						$article['image']= preg_replace('#((\[img])|(\[/img]))#','',$article['image']);
						$test = $article['image'];
						$verification = getimagesize($article['image']);
						$test = $verification;
						if(($test['mime']!='image/jpeg') and ($test['mime']!='image/jpg') and ($test['mime']!='image/png'))
							$article['image']="image/formation-continue-financement.jpg";
						echo '<div class="item active">
							<center><img class="img-responsive" src="'.$article['image'].'" style="min-height : 400px; max-height: 550px;"/></center>
							<div class="carousel-caption">
								<h1 style="text-shadow: 2px 2px 4px black;">'.$article['titre'].'</h1>';
								$article['corps']= preg_replace('#((\[img]).*(\[/img]))#','',$article['corps']);
								$article['corps']= preg_replace('#\[.*?\]|\[/.*?\]#','',$article['corps']);
								echo '<p class="text-justify" style="text-shadow: 2px 2px 4px black; font-weight: bold;">'.substr($article['corps'],0,300);
								if(strlen($article['corps'])>302)
									echo '[...]</p>';
								else
									echo '</p>';
								echo '<a class="btn btn-default" href="Actualite/Article.php?id='.$article['id_article'].'">
								Lire la suite...
								</a>
							</div>
						
						</div>';
						
						while($article = $requete->fetch(PDO::FETCH_ASSOC)){
							$article['image']= preg_replace('#((\[img])|(\[/img]))#','',$article['image']);
							$test = $article['image'];
							$verification = getimagesize($article['image']);
							$test = $verification;
							if(($test['mime']!='image/jpeg') and ($test['mime']!='image/jpg') and ($test['mime']!='image/png'))
								$article['image']="image/formation-continue-financement.jpg";
							echo '<div class="item">
								<center><img class="img-responsive" src="'.$article['image'].'" style="min-height : 400px; max-height: 550px;"/></center>
								<div class="carousel-caption">
									<h1 style="text-shadow: 2px 2px 4px black;">'.$article['titre'].'</h1>';
									$article['corps']= preg_replace('#((\[img]).*(\[/img]))#','',$article['corps']);
									$article['corps']= preg_replace('#\[.*?\]|\[/.*?\]#','',$article['corps']);
									echo '<p class="text-justify" style="text-shadow: 2px 2px 4px black; font-weight: bold;">'.substr($article['corps'],0,300);
									if(strlen($article['corps'])>302)
										echo '[...]</p>';
									else
										echo '</p>';
									echo '<a class="btn btn-default" href="Actualite/Article.php?id='.$article['id_article'].'">
									Lire la suite...
									</a>
								</div>
						
							</div>';
						}
					?>
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

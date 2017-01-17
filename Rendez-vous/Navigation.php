
<nav id="navi" class="navbar navbar-default "> 
				
			<div class="container-fluid"> 
				
				<ul class="nav navbar-nav">
					<?php if($_SESSION['categorie']){ ?>
					<li><a href="Accueil.php"><img id="logo" src="../image/Logo_IUT_Villetaneuse.png" alt="Accueil"></a></li>
					<?php } ?>
					<li class="dropdown navigation">
						<a href="Formation.html" class="dropdown-toogle">Formation</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="Formation/GEA.html">Formations en Gestion, Comptabilité, Ressources Humaines, Management</a></li>
							<li><a href="Formation/CJ.html">Formations en Juridique, Notariat, Finance</a></li>
							<li><a href="Formation/INFO.html">Formations en Informatique, Systèmes, Logiciels</a></li>
							<li><a href="Formation/RT.html">Formations en Réseaux, Télécommunications</a></li>
							<li><a href="Formation/GEII.html">Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies</a></li>
						</ul>
					</li>			
					<li class="navigation"><a href="Ressource.php">Ressource</a></li>
					<li class="navigation"><a href="Travail.php">Espace de travail</a></li>
					<li class="navigation"><a href="Rendez-vous.php">Rendez-vous</a></li>
					<li class="navigation"><a href="Actualite.php">Actualites</a></li>
					<li class="navigation"><a href="<?php echo 'Forum.php?f=1'; ?>">Forum</a></li>
					<?php if(!isset($_SESSION['categorie'])){ ?>
					<li class="navigation"><a href="Connexion.php"><span class="glyphicon glyphicon-user"></span> Connexion</a></li>
					<?php }else{ ?>
					<li class="dropdown navigation">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $_SESSION['prenom']; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="Profil.php">profil</a></li>
							<li><a href="Deconnexion.php">deconnexion</a></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
					
			<!--	<div id="recherche">
					<form class="navbar-form navbar-left" role="search">
							
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Rechercher">
						</div>
								
						<button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></button>
					</form>
				</div> -->
					
			</div>
					
		</nav>
		

<!-- Bootstrap -->
	
		<link href="../../../css/provisoire.css" rel="stylesheet">
		<link href="../../../css/bootstrap.css" rel="stylesheet" >
		<?php
			if(isset($_SESSION['connexion']))
				echo "<link rel=\"shortcut icon\" href=\"../../../image/Logo_Formation_Continue.png\"/>";
			else
				echo "<link rel=\"shortcut icon\" href=\"../../../image/Logo_IUT_Villetaneuse.png\"/>";
		?>
	</head>
		
	<body>

		<nav id="navi" class="navbar navbar-default navbar-fixed-top"> 
				
			<div class="container-fluid"> 
				
				<ul class="nav navbar-nav">
					<li>
						<a href="../../../Accueil.php">
						<?php
							if(isset($_SESSION['connexion']))						
								echo "<img id=\"logo\" src=\"../../../image/Logo_Formation_Continue.png\" alt=\"Accueil\">";
							else
								echo "<img id=\"logo\" src=\"../../../image/Logo_IUT_Villetaneuse.png\" alt=\"Accueil\">";
						?>
						</a>
					</li>
					<li class="dropdown navigation">
						<a href="../../Formation.php" class="dropdown-toogle">Formation</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="../../DIU.php">Formation Modulaire et Diplômante Interuniversitaire</a></li>
							<li><a href="../GCRHM.php">Formations en Gestion, Comptabilité, Ressources Humaines, Management</a></li>
							<li><a href="../../CJ.php">Formations en Juridique, Notariat, Finance</a></li>
							<li><a href="../../INFO.php">Formations en Informatique, Systèmes, Logiciels</a></li>
							<li><a href="../../RT.php">Formations en Réseaux, Télécommunications</a></li>
							<li><a href="../../GEII.php">Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies</a></li>
						</ul>
					</li>			
					<li class="navigation"><a href="../../../Ressource.php">Ressource</a></li>
					<li class="navigation"><a href="../../../Travail.php">Espace de travail</a></li>
					<li class="navigation"><a href="../../../Rendez-vous.php">Rendez-vous</a></li>
					<li class="navigation"><a href="../../../Actualite/Actualite.php">Actualites</a></li>
					<li class="navigation"><a href="../../../Forum.php">Forum</a></li>   
					<li class="navigation">
						<?php
							if(isset($_SESSION['connexion'])){						
								echo "<a href=\"../../../Profil.php\">";
								echo "<span class=\"glyphicon glyphicon-user\"></span>";
								echo " Profil";
							}
							else{
								echo "<a href=\"../../../Connexion.php\">";
								echo "<span class=\"glyphicon glyphicon-user\"></span>";
								echo " Connexion";
							}
						?>
						</a>
					</li>
				</ul>
					
				<div id="recherche">
					<form class="navbar-form navbar-left" role="search">
							
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Rechercher">
						</div>
								
						<button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></button>
					</form>
				</div>
					
			</div>
					
		</nav>

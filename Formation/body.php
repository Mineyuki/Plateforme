<!-- Bootstrap -->
		<!-- Utilisation CSS personnel -->
		<link href="../css/provisoire.css" rel="stylesheet">
		<!-- Utilisation CSS Bootstrap -->
		<link href="../css/bootstrap.css" rel="stylesheet" >
		<!-- Utilisation logo sur l'onglet -->
		<?php
			if(isset($_SESSION['connexion']))
				echo "<link rel=\"shortcut icon\" href=\"../image/Logo_Formation_Continue.png\"/>";
			else
				echo "<link rel=\"shortcut icon\" href=\"../image/Logo_IUT_Villetaneuse.png\"/>";
		?>
	</head>

<!--
*************************************************************************************************************************************************
		BODY
*************************************************************************************************************************************************
-->
	
	<body>

	<!-- Barre de navigation -->
	<div id="navigation">
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="glyphicon glyphicon-menu-hamburger"></span>
					</button>
					<a class="navbar-brand" href="../Accueil.php">
						<?php
							if(isset($_SESSION['connexion']))						
								echo "<img id=\"logo\" src=\"../image/Logo_Formation_Continue.png\" alt=\"Accueil\">";
							else
								echo "<img id=\"logo\" src=\"../image/Logo_IUT_Villetaneuse.png\" alt=\"Accueil\">";
						?>
					</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-search"></span></a>
							<ul class="dropdown-menu">
								<li>
									<form method="post" class="navbar-form" role="search">
										<div class="form-group">
										<input type="text" class="form-control" placeholder="Rechercher" size="90">
										</div>
										<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
									</form>
								</li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Formations</a>
							<ul class="dropdown-menu">
								<li><a href="DIU.php">Formation Modulaire et Diplômante Interuniversitaire</a></li>
								<li><a href="GCRHM/GCRHM.php">Formations en Gestion, Comptabilité, Ressources Humaines, Management</a></li>
								<li><a href="CJ.php">Formations en Juridique, Notariat, Finance</a></li>
								<li><a href="INFO.php">Formations en Informatique, Systèmes, Logiciels</a></li>
								<li><a href="RT.php">Formations en Réseaux, Télécommunications</a></li>
								<li><a href="GEII.php">Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies</a></li>
							</ul>
						</li>
						<!-- Onglet Ressource -->			
						<li><a href="../Ressources/Ressource.php">Ressources</a></li>
						<!-- Onglet Espace de travail -->
						<li><a href="../Travail.php">Espace de travail</a></li>
						<!-- Onglet Rendez-vous -->
						<li><a href="../Rendez-vous/Rendez-vous.php">Rendez-vous</a></li>
						<!-- Onglet Actualites -->
						<li><a href="../Actualite/Actualite.php">Actualités</a></li>
						<!-- Onglet Forum -->
						<li><a href="../Forum/Forum.php?f=1">Forum</a></li>
						<!-- Onglet Connexion -->
						<li>
							<?php
								if(isset($_SESSION['connexion'])){						
									echo "<a href=\"../Profil.php\">
										<span class=\"glyphicon glyphicon-user\"></span>
										Profil</a></li>";
									echo "<li><a href=\"../Connexion.php\" title=\"Déconnexion\">
										<span class=\"glyphicon glyphicon-off\"></span>";
								}
								else{
									echo "<a href=\"../Connexion.php\">
										<span class=\"glyphicon glyphicon-user\"></span>
										Connexion";
								}
							?>
							</a>
						</li>
					</ul>

				</div><!-- /.navbar-collapse -->

			</div><!-- /.container-->

		</nav>

	</div>

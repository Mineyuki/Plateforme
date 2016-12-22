<!-- Bootstrap -->
		<!-- Utilisation CSS personnel -->
		<link href="css/provisoire.css" rel="stylesheet">
		<!-- Utilisation CSS Bootstrap -->
		<link href="css/bootstrap.css" rel="stylesheet" >
		<!-- Utilisation logo sur l'onglet -->
		<link rel="shortcut icon" href="image/Logo_IUT_Villetaneuse.png"/>
		<!-- Utilisation JavaScript Bootstrap -->
		<script src="js/bootstrap-dropdown.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</head>

<!--
*************************************************************************************************************************************************
		BODY
*************************************************************************************************************************************************
-->
	
	<body>

	<!-- Barre de navigation -->
	<div>
		<nav id="navi" class="navbar navbar-default navbar-fixed-top"> 
				
			<div class="container-fluid"> 
				<!-- Barre de navigation = Liste -->
				<ul class="nav navbar-nav">
					<!-- Logo -->
					<li><a href="Accueil.php"><img id="logo" src="image/Logo_IUT_Villetaneuse.png" alt="Accueil"></a></li>
					<!-- Onglet formation -->
					<li class="dropdown navigation">
						<a href="Formation.php" class="dropdown-toogle">Formation</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="Formation/DIU.php">Formation Modulaire et Diplômante Interuniversitaire</a></li>
							<li><a href="Formation/GEA.php">Formations en Gestion, Comptabilité, Ressources Humaines, Management</a></li>
							<li><a href="Formation/CJ.php">Formations en Juridique, Notariat, Finance</a></li>
							<li><a href="Formation/INFO.php">Formations en Informatique, Systèmes, Logiciels</a></li>
							<li><a href="Formation/RT.php">Formations en Réseaux, Télécommunications</a></li>
							<li><a href="Formation/GEII.php">Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies</a></li>
						</ul>
					</li>
					<!-- Onglet Ressource -->			
					<li class="navigation"><a href="Ressource.php">Ressource</a></li>
					<!-- Onglet Espace de travail -->
					<li class="navigation"><a href="Travail.php">Espace de travail</a></li>
					<!-- Onglet Rendez-vous -->
					<li class="navigation"><a href="Rendez-vous.php">Rendez-vous</a></li>
					<!-- Onglet Actualites -->
					<li class="navigation"><a href="Actualite.php">Actualites</a></li>
					<!-- Onglet Forum -->
					<li class="navigation"><a href="Forum.php">Forum</a></li>
					<!-- Onglet Connexion -->
					<li class="navigation"><a href="Connexion.php"><span class="glyphicon glyphicon-user"></span> Connexion</a></li>
				</ul>
				
				<!-- Barre de recherche -->
				<div id="recherche">
					<form class="navbar-form navbar-left" role="search">
							
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Rechercher">
						</div>
								
						<button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></button>
					</form>
				</div>
				<!-- Fin portion Barre de recherche -->
	
			</div>
			<!-- Fin contenaire -->
					
		</nav>
	</div>

<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php require('body.php');?>

		<ol class="breadcrumb">
			<li><a href="../Accueil.php">Accueil</a></li>
			<li class="active">Actualité</li>
		</ol>

		<div class="container">
			<h1 class="text-center">Actualité</h1>
			<hr>
		</div>

		<div class="container">
			<section class="row">
				<?php
					require('../co.php');

					if($_SESSION['ecriture_article']==1)
						echo "<a href=\"Article.php\">Ecrire un article</a>";

					/*$req = "SELECT * from Article";
					$requete->prepare($req);
					$requete->execute();*/
				?>
			</section>
		</div>

<?php require('footer.php');?>

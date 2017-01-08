<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php 
	require('body.php');
	require('../jBBCode-1.3.0/JBBCode/Parser.php');
?>

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
						echo "<a href=\"Ecriture_Article.php\">Ecrire un article</a>";
										
					$parser = new JBBCode\Parser();
					$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

					$req = "SELECT * FROM Article ORDER BY id_article DESC";
					$requete = $bd->prepare($req);
					$requete->execute();
					while($article = $requete->fetch(PDO::FETCH_ASSOC)){
						echo "<hr>";
						echo "<h2>".$article['titre']."</h2>";
						echo "<p>".$article['jour'].' - '.$article['auteur']."</p>";
						$parser->parse($article['corps']);
						echo "<p>".substr($parser->getAsHtml(),0,100)."...</p>";
					}
					?>
			</section>
		</div>

<?php require('footer.php');?>

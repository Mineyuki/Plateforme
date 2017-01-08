<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php 
	require('body.php');
	require('../co.php');
	/*require('../jBBCode-1.3.0/JBBCode/Parser.php');*/

	$request = "SELECT count(*) FROM Article";
	$requete = $bd->prepare($request);
	$requete->execute();
	$nombre = $requete->fetch(PDO::FETCH_NUM);
		$limite_page = $nombre[0];
	$limite_page = (int) (($limite_page/10)-0.1);
	if(isset($_GET['page']) and trim($_GET['page'])!='')
		$limiter = $_GET['page']*10;
	else
		$limiter = 0;
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

				<nav aria-label="pagination">
					<ul class="pager">
						<?php
							if($_GET['page']>0){
								echo "<li class=\"previous\"><a href=\"";
								echo htmlentities($_SERVER['PHP_SELF']).'?page='.($_GET['page']-1);
								echo "\"><span aria-hidden=\"true\">&larr;</span> Précédent</a></li>";
							}
							if($_GET['page']<$limite_page){
								echo "<li class=\"next\"><a href=\"";
								echo htmlentities($_SERVER['PHP_SELF']).'?page='.($_GET['page']+1);
								echo "\">Suivant <span aria-hidden=\"true\">&rarr;</span></a></li>";
							}
							if($_GET['page']<0 or $_GET['page']>$limite_page){
								echo"<script>
									document.location.href=\"Actualite.php\"
								</script>";
							}
						?>
					</ul>
				</nav>

				<?php
					if($_SESSION['ecriture_article']==1)
						echo "<a href=\"Ecriture_Article.php\">Ecrire un article</a>";
										
					/*$parser = new JBBCode\Parser();
					$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());*/

					$req = "SELECT * FROM Article ORDER BY id_article DESC LIMIT $limiter, 10";
					$requete = $bd->prepare($req);
					$requete->execute();
					while($article = $requete->fetch(PDO::FETCH_ASSOC)){
						echo "<hr>";
						echo "<h2><a href=\"Article.php?id=".$article['id_article']."\">".$article['titre']."</a></h2>";
						echo "<p>".$article['jour'].' - '.$article['auteur']."</p>";
						/*$parser->parse($article['corps']);
						echo "<p>".substr($parser->getAsHtml(),0,600)."...</p>";*/
						echo "<p>".substr(preg_replace('#\[.*?\]|\[/.*?\]#',' ',$article['corps']),0,600);
						if(strlen($article['corps'])>=600)
							echo "...</p>";
						else
							echo "</p>";
					}
				?>

				<nav aria-label="pagination">
					<ul class="pager">
						<?php 
							if($_GET['page']>0){
								echo "<li class=\"previous\"><a href=\"";
								echo htmlentities($_SERVER['PHP_SELF']).'?page='.($_GET['page']-1);
								echo "\"><span aria-hidden=\"true\">&larr;</span> Précédent</a></li>";
							}
							if($_GET['page']<$limite_page){
								echo "<li class=\"next\"><a href=\"";
								echo htmlentities($_SERVER['PHP_SELF']).'?page='.($_GET['page']+1);
								echo "\">Suivant <span aria-hidden=\"true\">&rarr;</span></a></li>";
							}
							if($_GET['page']<0 or $_GET['page']>$limite_page){
								echo"<script>
									document.location.href=\"Actualite.php\"
								</script>";
							}
						?>
					</ul>
				</nav>

			</section>
		</div>

<?php require('footer.php');?>

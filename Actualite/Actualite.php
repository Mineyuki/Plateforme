<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php 
	require('body.php');
	require('../co.php');

/*
 * On va compter le nombre d'article pour délimiter le nombre de "pagination"
 */

	$request = 'SELECT count(*) FROM Article'; /* La requête SQL */
	$requete = $bd->prepare($request); /* On prépare la requête */
	$requete->execute(); /* On exécute la requête */
	$nombre = $requete->fetch(PDO::FETCH_NUM); /* On va chercher la première ligne où le nombre d'article est indiqué */
		$limite_page = $nombre[0]; /* On récupère dans la variable $limite_page le nombre d'article */
	$limite_page = (int) (($limite_page/10)-0.1); 
/*
 * On va mettre que 10 articles par page donc on divise par 10.
 * Astuce : Vu comment la page est codée, faire -0.1 permet de ne pas afficher le bouton pour la page suivante !
 * Exemple : Au lieu d'avoir 1, on obtient 0 ! Puisqu'on cast et on prend seulement l'entier.
 *
 * On multiplie la limite par 10 pour limiter la requête SQL.
 * C'est-à-dire : On veut seulement que 10 articles s'affichent à partir d'un nombre.
 * Exemple : On veut afficher les 10 premiers, la requête plus bas prendra si limiter = 0, les 10 premiers articles.
 * Autre exemple : Si on veut afficher les 10 suivants, $_GET['page'] sera égale à 1. On multiplie par 10 pour que la requête commence à 11 jusqu'à 20.
 * On vérifie au moins que ce qui est en paramètre est un integer aussi !
 */

	if(isset($_GET['page']) and trim($_GET['page'])!='' and is_int($_GET['page']))
		$limiter = (int) $_GET['page']*10;
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

/*
 * On affichera le bouton pour accéder à la précédente seulement si $_GET['page'] est supérieur à 0
 * On n'affichera pas si $_GET['page'] est négatif ou supérieur à la limite des articles disponibles.
 * Exemple : On ne peut pas afficher quand on est à la page -5 ou 10000 car on n'a déjà pas 1000 articles et les pages ne sont pas négatifs.
 */
							if($_GET['page']>0){
								echo "<li class=\"previous\"><a href=\"";
								echo htmlentities($_SERVER['PHP_SELF']).'?page='.($_GET['page']-1);
								echo "\"><span aria-hidden=\"true\">&larr;</span> Précédent</a></li>";
							}
/*
 * On affichera le bouton pour accéder à la page suivante lorsqu'il sera possible de voir les suivantes.
 * On n'affichera pas le bouton lorsqu'on est au-delà de la limite des articles disponibles.
 */
							if($_GET['page']<$limite_page){
								echo "<li class=\"next\"><a href=\"";
								echo htmlentities($_SERVER['PHP_SELF']).'?page='.($_GET['page']+1);
								echo "\">Suivant <span aria-hidden=\"true\">&rarr;</span></a></li>";
							}
/*
 * Les cas étranges de où l'on ne pourrait pas, on retournerait à la première page d'actualité
 */
							if($_GET['page']<0 or $_GET['page']>$limite_page){
								echo"<script>
									document.location.href=\"Actualite.php\"
								</script>";
							}
						?>
					</ul>
				</nav>

				<?php
/*
 * Seuls les personnes pouvant écrire pourront accéder au lien et verront le lien
 */
					if($_SESSION['ecriture_article']==1)
						echo "<a href=\"Ecriture_Article.php\">Ecrire un article</a>";
/*
 * On veut seulement les articles dans l'ordre décroissant (du plus ancien au plus récent) dans la limite de 10 par page
 */
					$req = "SELECT * FROM Article ORDER BY id_article DESC LIMIT $limiter, 10";
					$requete = $bd->prepare($req);
					$requete->execute();
					while($article = $requete->fetch(PDO::FETCH_ASSOC)){
						echo "<hr>";
/*
 * On envoit sur une page où on pourra consulter plus amplement.
 * Le paramètre sera vérifié ! Si le paramètre existe et que l'utilisateur veut jouer, à son aise !
 * Exemple : Si existe l'id_article 100, il se rendra à l'article 100.
 */ 
						echo "<h2><a href=\"Article.php?id=".$article['id_article']."\">".$article['titre']."</a></h2>";
						echo "<p>".$article['jour'].' - '.$article['auteur']."</p>";
/*
 * On affichera seulement un début d'article sans utiliser le format BBCode.
 * On supprime le format BBCode pour avoir seulement le corps de l'article.
 * On supprime aussi le lien du contenu des images.
 */
						$article['corps']=preg_replace('#((\[img]).*(\[/img]))#','',$article['corps']);
						$article['corps']=preg_replace('#\[.*?\]|\[/.*?\]#','',$article['corps']);
						echo "<p>".substr($article['corps'],0,600);
/*
 * Pour une question de présentation, on mettra les points de suspentions pour indiquer que l'article continue.
 */
						if(strlen($article['corps'])>=600)
							echo "[...]</p>";
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

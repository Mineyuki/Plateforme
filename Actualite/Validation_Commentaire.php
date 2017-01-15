<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Validation des commentaires</title>
<?php 
	require('body.php');

/*
 * $page : Permet de "paginer" les pages. Cette variable doit être vérifier.
 * On transforme le paramètre passé en paramètre en entier
 * Si on donne en paramètre une chaine de caractère (comme attaque XXS), $page prendra la valeur 0
 */

	$page = intval($_GET['page']);

	if($_SESSION['categorie']!='moderateur' or $page<0) // On vérifie si l'utilisateur est un modérateur ou si le paramètre est négatif
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<br />
		<h1 class="text-center">Vous devez être connecté(e) pour accéder à cette page.</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	else{ // La fermeture se trouve en fin code

	require('../co.php'); // Connexion à la base de donnée
	require('../jBBCode-1.3.0/JBBCode/Parser.php'); // Parser le BBCode (convertir en html)

/*
 * Ces lignes permettront de parser le BBCode.
 * C'est de la programmation orienté objet.
 */

	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
	require('../jBBCode-1.3.0/JBBCode/new_JBBCode.php');

	$request = 'SELECT count(*) FROM Commentaire WHERE validation=0'; // On compte le nombre de commentaire n'étant pas validé
	$requete = $bd->prepare($request); // On prépare la requête
	$requete->execute(); // On exécute la requête
	$nombre = $requete->fetch(PDO::FETCH_NUM); // On va chercher la première ligne où le nombre de commentaire est indiqué
		$limite_page = $nombre[0]; // On récupère dans la variable $limite_page le nombre de commentaire 

// Astuce : Dans le cas où on aurait une limite_page = 1, on aurait finalement une $limite_page de 0.

	$limite_page = (int) (($limite_page/10)-0.1);

	if(($page+1)>1) // $page existe déjà. Si il y a une attaque, $page sera égale à 0
		$limiter = (int) $page*10;
	else
		$limiter = 0;
?>

	<ol class="breadcrumb">
		<li><a href="../Accueil.php">Accueil</a></li>
		<li><a href="Actualite.php">Actualités</a></li>
		<li class="active">Validation des commentaires</li>
	</ol>

	<div class="container">
		<h1 class="text-center">Validation des commentaires</h1>
		<hr>
	</div>

	<div class="container">
		<section class="row">
			<nav aria-label="pagination">
				<ul class="pager">
				<?php
					if($page>$limite_page){ // Dans le cas où on dépasse la limite calculée au début, on rejete 
						echo '<script>
							document.location.href="Validation_Commentaire.php"
						</script>
						<h1 class="text-center">Veuillez activer JavaScript</h1>';
					}
					else{ // La fin se trouve à la fin du code
					if($page>0 and $page==$limite_page) // On va à la page précédente tant qu'on est dans la limite et qu'on est supérieur à la première page
						echo '<li class="previous">
							<a href="'.htmlentities($_SERVER['PHP_SELF']).'?page='.($page-1).'">
								<span aria-hidden="true">&larr;</span> Précédent
							</a></li>';
					if($page<$limite_page) // On affiche la page suivant lorsqu'on est en dessous de la limite définie
						echo '<li class="next">
							<a href="'.htmlentities($_SERVER['PHP_SELF']).'?page='.($page+1).'">
								Suivant <span aria-hidden="true">&rarr;</span>
							</a></li>';
				?>
				</ul>
			</nav>
		</section>
		<section class="row">
			<div class="col-md-3">
				<a href="Ecriture_Article.php?page=<?php echo $page;?>">
					<span class ="glyphicon glyphicon-pencil"></span>
					Ecrire un article
				</a>
			</div>
			<div class="col-md-3 col-md-offset-6">
				<a href="Validation_Article.php">
					<span class="glyphicon glyphicon-ok"></span>
					<strong>Validation des articles</strong>
				</a>
			</div>
		</section>
		<section class="row">
		<?php
// On affiche tous les commentaires dans le cadre de la limite
			$req = "SELECT a.id_article, a.id_commentaire, DATE_FORMAT(a.jour,'%d %b %Y %T'), a.pseudo, a.commente, b.titre
				FROM Commentaire AS a JOIN Article AS b
				ON a.id_article=b.id_article
				WHERE a.validation = 0 ORDER BY id_commentaire DESC LIMIT 10 OFFSET $limiter";
			$requete = $bd->prepare($req);
			$requete->execute();
			echo '<form method="POST" action="'.htmlentities($_SERVER['PHP_SELF']).'">';
			while($commentaire = $requete->fetch(PDO::FETCH_ASSOC)){
				echo '<hr>
				<h2>
					<a href="Article.php?id='.$commentaire['id_article'].'&page='.$page.'&validation_commentaire=1">'.
						$commentaire['titre'].
					'</a>
				</h2>
				<input type="checkbox" name="choix[]" value="'.$commentaire['id_commentaire'].'"> '.
				$commentaire['DATE_FORMAT(a.jour,\'%d %b %Y %T\')'].' - '.$commentaire['pseudo'].'</p>';
				$parser->parse($commentaire['commente']);
				echo $parser->getAsHtml().'</p>';
			}
		?>
				<hr>
				<div class="col-md-3">
					<input class="btn btn-default" type="submit" name="valider" value="Valider les commentaires">
				</div>
				<div class="col-md-3 col-md-offset-6">
					<input class="btn btn-default" type="submit" name="supprimer" value="Supprimer les commentaires">
				</div>
			</form>
		</section>
		<section class="row">
			<nav aria-label="pagination">
				<ul class="pager">
				<?php
					if($page>0 and $page==$limite_page) // On va à la page précédente tant qu'on est dans la limite et qu'on est supérieur à la première page
						echo '<li class="previous">
							<a href="'.htmlentities($_SERVER['PHP_SELF']).'?page='.($page-1).'">
								<span aria-hidden="true">&larr;</span> Précédent
							</a></li>';
					if($page<$limite_page) // On affiche la page suivant lorsqu'on est en dessous de la limite définie
						echo '<li class="next">
							<a href="'.htmlentities($_SERVER['PHP_SELF']).'?page='.($page+1).'">
								Suivant <span aria-hidden="true">&rarr;</span>
							</a></li>';
				?>
				</ul>
			</nav>
		</section>
	</div>

<?php 
	require('footer.php');

/*
 ***************************************************************************************************
 *		VALIDER LES COMMENTAIRES
 ***************************************************************************************************
 */

	$valider = htmlspecialchars($_POST['valider']);
	if(isset($valider) and $valider==="Valider les commentaires"){
		foreach($_POST['choix'] as $id_commentaire){
			$id_commentaire = htmlspecialchars($id_commentaire);

			$request = 'UPDATE Commentaire SET validation =1 WHERE id_commentaire = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id_commentaire);
			$requete->execute();
			echo '<script>
				document.location.href="Validation_Commentaire.php"
			</script>
			<br />
			<h1 class="text-center">Commentaires validées !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}
	}

/*
 ***************************************************************************************************
 *		SUPPRIMER LES COMMENTAIRES
 ***************************************************************************************************
 */
	
	$supprimer = htmlspecialchars($_POST['supprimer']);
	
	if(isset($supprimer) and $supprimer==="Supprimer les commentaires"){
		foreach($_POST['choix'] as $id_commentaire){
			$id_commentaire = htmlspecialchars($id_commentaire);

			$request = 'DELETE FROM Commentaire WHERE id_commentaire = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id_commentaire);
			$requete->execute();
			echo '<script>
				document.location.href="Validation_Commentaire.php"
			</script>
			<br />
			<h1 class="text-center">Commentaires supprimés !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}
	}

// Fin des accolades pour les problèmes de JavaScript
	}
	}
?>

<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Article</title>
<?php 
	require('body.php');
	
	if(empty($_POST)){
		$id = intval($_GET['id']);
		$page = intval($_GET['page']);
	}

/*
 * Dans le cas où l'utilisateur n'a pas rempli le champs titre et/ou contenu du commentaire
 */	

	if(isset($_POST['contenu']) and trim($_POST['contenu'])==''){
		$article = ["id_article" => $_POST['id'],];
		$id = intval($_POST['id']);
		$contenu = $_POST['contenu'];
	}

	if($id>0 and $page<0 and ($validation!=1 or $validation!=0))
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<br />
		<h1 class="text-center">Veuillez activer le JavaScript</h1>';
	else{ // La fin de l'accolade se trouve en fin de code

	require('../co.php');
	require('../jBBCode-1.3.0/JBBCode/Parser.php'); // Parser le BBCode (convertir en html)

/*
 * Ces lignes permettront de parser le BBCode.
 * C'est de la programmation orienté objet.
 */

	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
	require('../jBBCode-1.3.0/JBBCode/new_JBBCode.php');

/*
 ***************************************************************************************************
 *		RECHERCHE DE L'ARTICLE POUR AFFICHER
 ***************************************************************************************************
 */

	$req = 'SELECT id_article, titre, DATE_FORMAT(jour,\'%d %b %Y %T\'), auteur, corps, validation FROM Article where id_article = :id';
	$requete = $bd->prepare($req); // Préparation de la requête
	$requete->bindValue(':id',$id);  // Vérification attaque par injection
	$requete->execute(); // Exécution de la requête
	$article = $requete->fetch(PDO::FETCH_ASSOC); // Prend la première ligne du résultat SQL

/*
 * Si l'id n'existe pas, on retourne sur la page d'Actualité.
 * L'utilisateur a surement touché à l'URL. Ligne de prévention d'attaque ou autre.
 */
	if($article['id_article']!=$id)
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<br />
		<h1 class="text-center">Veuillez activer le JavaScript</h1>';
	else{ // La fin de l'accolade se trouve en fin de code
?>

	<ol class="breadcrumb">
		<li><a href="../Accueil.php">Accueil</a></li>
		<li><a href="Actualite.php">Actualités</a></li>
		<?php
			if($article['validation']==0)
				echo '<li>
					<a href="Validation_Article.php">Validation Articles</a>
					</li>';
		?>
		<li class="active"><?php echo $article['titre'];?></li>
	</ol>

	<div class="container">
		<section class="row">
		<?php
			echo '<h1 class="text-center">'.$article['titre'].'</h1>';

			if($_GET['validation_commentaire']==1)
				echo '<a href="Validation_Commentaire.php?page='.$page.'">';
/*
 * Si on arrive sur la page en passant par la page Validation Article, en appuyant sur précédent,
 * on doit retourner à la page de Validation Article.
 */

			elseif($article['validation']==0)
				echo '<a href="Validation_Article.php?page='.$page.'">';


/*
 * Si on arrive sur la page en passant par un moyen différent, en appuyant sur précédent,
 * on doit retourner sur la première page d'Actualité
 */

			else
				echo '<a href="Actualite.php?page='.$page.'">';
		?>
			<span class="glyphicon glyphicon-arrow-left"></span>
			Précédent
			</a>
			<hr>
		</section>
	</div>
	
	<div class="container">
		<section class="row">
			<div class="col-md-4">
		<?php

			if($article['auteur']==$_SESSION['nom']) // Seul l'auteur de l'article peut modifier l'article.
				echo '<a href="Ecriture_Article.php?id='.$article['id_article'].'">
					<span class="glyphicon glyphicon-cog"></span>					
					Modifier l\'article / Supprimer l\'article
				</a>';
			echo '</div>';
			if($_SESSION['categorie']=='moderateur') // Le modérateur peut supprimer l'article.
				echo '<div class="col-md-3 col-md-offset-5">
				<a href="'.htmlentities($_SERVER['PHP_SELF']).'?valider=1&id_article='.$article['id_article'].'">
					<span class="glyphicon glyphicon-remove"></span>
					<strong>Supprimer l\'article</strong>
				</a>
			</div>';
		?>
		</section>

		<section class="row">
			<p><br/><?php echo $article['DATE_FORMAT(jour,\'%d %b %Y %T\')'].' - '.$article['auteur'];?></p>
		</section>

		<section class="row">
		<?php

/*
 * L'article s'affichera (contenu). En général, il ne devrait pas comporter d'erreur MAIS des erreurs peuvent se produire dû à la traduction
 * BBCode en html. Il faudra modifier le fichier s'occupant du parsage.
 */

			$parser->parse($article['corps']);
			echo $parser->getAsHtml();
		?>
		</section>

		<section class="row">
		<?php
			if($article['validation']==0 and $_SESSION['categorie']=='moderateur') // Bouton valider l'article
				echo '<center><a class="btn btn-default" href="'.htmlentities($_SERVER['PHP_SELF']).'?valider=2&id_article='.$article['id_article'].'">Valider l\'article</a></center>';
		?>
		</section>
	</div>

	<?php
		if(isset($_SESSION['connexion'])){ // Poster commentaire, connexion oblige 
	?>
		
	<div class="container">
		<hr>
		<?php
			$envoye = intval($_GET['envoye']);
			if($envoye==1)
				echo '<p><strong>Votre commentaire est envoyé ! Il est en cours de validation.</strong></p>';
		?>
		<form method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']);?>">
			<label>Votre commentaire</label>
			<?php if(isset($_POST['contenu']) and trim($_POST['contenu'])=='') echo '<p>Veuillez donner votre commentaire</p>';?>
			<textarea class="minime" name="contenu" maxlength="60 000"><?php echo $contenu;?></textarea><br/>
			<input value="<?php echo $article['id_article'];?>" name="id" type="hidden">
			<input value="<?php echo $page;?>" name="page" type="hidden">
			<p>Nombre de caractères autorisés : 60 000</p>
			<input type="submit" class="btn btn-default" value="Envoyer">
		</form>
	</div>

	<?php 
	
	}

/*
 ***************************************************************************************************
 *		AFFICHAGE DES COMMENTAIRES
 ***************************************************************************************************
 */

		$req = "SELECT id_article, id_commentaire, DATE_FORMAT(jour,'%d %b %Y %T'), pseudo, commente, validation FROM Commentaire WHERE id_article = :id_article and validation=1 ORDER BY id_commentaire";
		$requete = $bd->prepare($req);
		$requete->bindValue(':id_article', $id); // Vérification attaque par injection
		$requete->execute();
		$nombre = 0;
		while($commentaire = $requete->fetch(PDO::FETCH_ASSOC)){
			if($temporaire==0)
				echo '<div class="container">
					<hr>
					<h2>Commentaires</h2>
				</div>';
			$temporaire++;
			echo '<div class="container">
				<section class="row">
				<div class="col-md-9">
				<p><strong>'.$commentaire['pseudo'].'</strong> - '.$commentaire['DATE_FORMAT(jour,\'%d %b %Y %T\')'].
				'</div>';
			if($_SESSION['categorie']=='moderateur' or $_SESSION['nom']==$commentaire['pseudo'])
				echo '<div class="col-md-3">
					<a href="'.htmlentities($_SERVER['PHP_SELF']).'?valider=3&id_commentaire='.$commentaire['id_commentaire'].'&auteur='.$_commentaire['pseudo'].'&id_article='.$commentaire['id_article'].'&page='.$page.'">
						<span class="glyphicon glyphicon-remove"></span> Supprimer le commentaire</span>
					</a>
				</div>
				</section>';
			echo '</p><p>';
			$parser->parse($commentaire['commente']);
			echo $parser->getAsHtml().'</p>
				<hr>
				</div>';

		}
			
/*
 ***************************************************************************************************
 *		ENVOIE DU COMMENTAIRE
 ***************************************************************************************************
 *
 * Variable nécessaires :
 * $_POST['contenu'] : Contenu du commentaire - Vérification obligaoire -
 * $today : Date de l'écriture du commentaire
 * $_POST['id'] : ID article - Vérification obligatoire -
 * $_POST['page'] : Page de l'actualité où on s'était arrêté - Vérification obligatoire -
 */

		$contenu = htmlspecialchars($_POST['contenu']); // Vérification faille XXS
		$today = date("Y-m-d H:i:s"); // Vérification faille XXS
		$id = intval($_POST['id']);
		$page = intval($_POST['page']);

		if(trim($contenu)!='' and !empty($_SESSION['nom']) and $id>0){
			$req = 'INSERT INTO Commentaire (id_article, jour, pseudo, commente) VALUES (:id_article, :jour, :nom, :commentaire)';
			$requete = $bd->prepare($req);
			$requete->bindValue(':id_article',$id); // Vérification attaque par injection
			$requete->bindValue(':jour',$today); // Vérification attaque par injection
			$requete->bindValue(':nom', $_SESSION['nom']); // Vérification attaque par injection
			$requete->bindValue(':commentaire', $contenu); // Vérification attaque par injection
			$requete->execute();
			echo '<script>
				document.location.href="Article.php?id='.$id.'&page='.$page.'&envoye=1"
			</script>
			<h1 class="text-center">Commentaire envoyé !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}

/*
 * $_GET['valider'] : Possède 3 options qui doivent passer une vérification:
 * 	- 1 : Valider l'article
 *	- 2 : Supprimer un article
 *	- 3 : Supprimer un commentaire
 *
 ***************************************************************************************************
 *		VALIDATION ARTICLE
 ***************************************************************************************************
 *
 * Variable nécessaires :
 * $_GET['valider']
 * $_GET['id_article'] : id de l'article - Vérification obligaoire -
 */

		$valider = intval($_GET['valider']);
		$id_article = intval($_GET['id_article']);

		if($id_article>0 and $valider==2 and $_SESSION['categorie']=='moderateur'){
			$request = 'UPDATE Article SET validation = 1 WHERE id_article = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id_article);
			$requete->execute();
			echo '<script>
				document.location.href="Validation_Article.php"
			</script>
			<h1 class="text-center">Article Validé !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}

/*
 ***************************************************************************************************
 *		SUPPRESSION ARTICLE
 ***************************************************************************************************
 *
 * Si on souhaite supprimer un article, il faudra d'abord supprimer TOUS ses commentaire pour enfin
 * supprimer l'article
 */
 
		if($id_article>0 and $valider==1 and $_SESSION['categorie']=='moderateur'){
			$request = 'DELETE FROM Commentaire WHERE id_article = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id_article); // Vérification attaque par injection
			$requete->execute();
			$request = 'DELETE FROM Article WHERE id_article = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id_article); // Vérification attaque par injection
			$requete->execute();
			echo '<script>
				document.location.href="Validation_Article.php"
			</script>
			<h1 class="text-center">Article Supprimé !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}

/*
 ***************************************************************************************************
 *		SUPPRESION COMMENTAIRE
 ***************************************************************************************************
 *
 * Variable nécessaire :
 * $_GET['valider']
 * $_GET['id_article'] : id de l'article - Vérification obligaoire -
 * $_GET['id_commentaire'] : id du commentaire - Vérification obligatoire -
 * $_GET['auteur'] : auteur du commentaire - Vérification obligatoire -
 * $_GET['page'] : page de l'actualité où on s'était arrêté - Vérification obligatoire -
 */

		$id_commentaire = intval($_GET['id_commentaire']);
		$auteur = htmlspecialchars($_GET['auteur']);
		$page = intval($_GET['page']);

		if($id_commentaire>0 and $valider==3 and ($_SESSION['categorie']=='moderateur' or $_SESSION['nom']==$auteur)){
				$request = 'DELETE FROM Commentaire WHERE id_commentaire = :id';
				$requete = $bd->prepare($request);
				$requete->bindValue(':id', $id_commentaire); // Vérification attaque par injection
				$requete->execute();
				echo '<script>
					document.location.href="Article.php?id='.$id_article.'&page='.$page.'"
				</script>
				<h1 class="text-center">Commentaire Supprimé !</h1>
				<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}

/*
 * Fermeture des différents else par rapport aux désactivation JavaScript
 */
	
	}
	}

	require('footer.php');
?>

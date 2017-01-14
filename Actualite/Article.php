<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php 
	require('body.php');
	
	if(empty($_POST)){
		$id = intval($_GET['id']);
		$page = intval($_GET['page']);
	}

/*
 * Dans le cas où l'utilisateur n'a pas rempli le champs titre et/ou contenu du commentaire
 */	

	if(isset($_POST['titre']) and isset($_POST['contenu']) and (trim($_POST['titre'])=='' or trim($_POST['contenu'])=='')){
		$article = ["id_article" => $_POST['id'],];
		$id = intval($_POST['id']);
		$titre = $_POST['titre'];
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
/*
 * Si on arrive sur la page en passant par la page Validation Article, en appuyant sur précédent,
 * on doit retourner à la page de Validation Article.
 */

			if($article['validation']==0)
				echo '<a href="Validation_Article.php?id='.$id.'">';


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
				echo '<a class="btn btn-default" href="'.htmlentities($_SERVER['PHP_SELF']).'?valider=2&id_article='.$article['id_article'].'">Valider l\'article</a>';
		?>
		</section>
	</div>

	<?php 
		if(isset($_SESSION['connexion'])){ // Poster commentaire, connexion oblige 
	?>
		
	<div class="container">
		<hr>
		<form method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']);?>">
			<label>Titre du commentaire</label><br/>
			<div class="form-group">
				<?php if(isset($_POST['titre']) and trim($_POST['titre'])=='') echo '<p>Veuillez donner un titre à votre commentaire</p>';?>
				<input class="form-control" type="text" name="titre" maxlength="255" value="<?php echo $titre;?>">
			</div>
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

		$req = "SELECT id_article, id_commentaire, DATE_FORMAT(jour,'%d %b %Y %T'), pseudo, commente FROM Commentaire WHERE id_article = :id_article ORDER BY id_commentaire";
		$requete = $bd->prepare($req);
		$requete->bindValue(':id_article', $id); // Vérification attaque par injection
		$requete->execute();
		$nombre = 0;
		while($commentaire = $requete->fetch(PDO::FETCH_ASSOC)){
			if($nombre==0)
				echo '<div class="container">
					<hr>
					<h2>Commentaires</h2>
				</div>';
			$nombre++;
			echo '<div class="container">
				<p><strong>'.$commentaire['pseudo'].'</strong> - '.$commentaire['DATE_FORMAT(jour,\'%d %b %Y %T\')'].'</p>
				<p>';
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
 * $_POST['titre'] : Titre du commentaire - Vérification obligatoire -
 * $_POST['contenu'] : Contenu du commentaire - Vérification obligaoire -
 * $today : Date de l'écriture du commentaire
 * $_POST['id'] : ID article - Vérification obligatoire -
 * $_POST['page'] : Page de l'article - Vérification obligatoire -
 */

		$titre = htmlspecialchars($_POST['titre']); // Vérification faille XXS
		$contenu = htmlspecialchars($_POST['contenu']); // Vérification faille XXS
		$today = date("Y-m-d H:i:s"); // Vérification faille XXS
		$id = intval($_POST['id']);
		$page = intval($_POST['page']);

		if(trim($titre)!='' and trim($contenu)!='' and !empty($_SESSION['nom']) and $id>0){
			$req = 'INSERT INTO Commentaire (id_article, jour, pseudo, commente) VALUES (:id_article, :jour, :nom, :commentaire)';
			$requete = $bd->prepare($req);
			$requete->bindValue(':id_article',$id); // Vérification attaque par injection
			$requete->bindValue(':jour',$today); // Vérification attaque par injection
			$requete->bindValue(':nom', $_SESSION['nom']); // Vérification attaque par injection
			$requete->bindValue(':commentaire', $contenu); // Vérification attaque par injection
			$requete->execute();
			echo '<script>
				document.location.href="Article.php?id='.$id.'&page='.$page.'"
			</script>
			<h1 class="text-center">Commentaire envoyé !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}

		$valider = intval($_GET['valider']);
		$id = intval($_GET['id_article']);

		if($id>0 and $valider==2 and $_SESSION['categorie']=='moderateur'){
			$request = 'UPDATE Article SET validation = 1 WHERE id_article = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id);
			$requete->execute();
			echo '<script>
				document.location.href="Validation_Article.php"
			</script>
			<h1 class="text-center">Article Validé !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}
		if($id>0 and $valider==1 and $_SESSION['categorie']=='moderateur'){
			$request = 'DELETE FROM Commentaire WHERE id_article = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id); // Vérification attaque par injection
			$requete->execute();
			$request = 'DELETE FROM Article WHERE id_article = :id';
			$requete = $bd->prepare($request);
			$requete->bindValue(':id', $id); // Vérification attaque par injection
			$requete->execute();
			echo '<script>
				document.location.href="Validation_Article.php"
			</script>
			<h1 class="text-center">Article Supprimé !</h1>
			<h2 class="text-center">Veuillez activer le JavaScript</h2>';
		}

/*
 * Fermeture des différents else par rapport aux désactivation JavaScript
 */
	
	}
	}

	require('footer.php');
?>

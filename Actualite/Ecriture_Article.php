<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Ecrire un article</title>
<?php 
	require('body.php');

	$page = intval($_GET['page']); // Vérification faille XXS

/*
 * Dans le cas où la personne n'a pas complété le titre et le contenu
 */

	if(isset($_POST['titre']) and isset($_POST['contenu']) and (trim($_POST['titre'])=='' or trim($_POST['contenu'])=='')){
		$article = [
			"titre" => $_POST['titre'],
			"auteur" => $_POST['auteur'],
			"corps" => $_POST['contenu'],
			];
		$id = intval($_POST['id']);
	}
/*
 ***************************************************************************************************
 *			ACCES A LA PAGE
 ***************************************************************************************************
 *
 * Si la personne n'est pas connecté, il est renvoyé sur la page de connexion
 */

	if(empty($_SESSION['connexion']))
		echo '<script>
			document.location.href="../Connexion.php"
		</script>
		<br />
		<h1 class="text-center">Vous devez être connecté(e) pour accéder à cette page.</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	else{ // La fin de l'accolade se trouve en fin de code

	require('../co.php');

/*
 ***************************************************************************************************
 *			RECHERCHE ARTICLE SI BESOIN
 ***************************************************************************************************
 *
 * On cherche l'article correspondant à son id si la personne souhaite modifier ou supprimer l'article
 */

	$id = intval($_GET['id']);

	if(isset($_GET['id']) and $id > 0){ // On recherche l'article correspondant à l'id passé en paramètre
		$req = 'SELECT * FROM Article where id_article = :id'; // Requête SQL
		$requete = $bd->prepare($req); // Préparation de la requête
		$requete->bindValue(':id',$id); // Vérification de l'id si attaque par injection
		$requete->execute(); // Exécution de la requête
		$article = $requete->fetch(PDO::FETCH_ASSOC); //Recherche la première ligne de réponse de la requête SQL
	}

/*
 * Si ce n'est pas l'auteur de l'article, on renvoit la personne sur la page d'Actualité
 * Si aucun article pour l'id correspondant n'existe, on renvoit sur la page d'Actualité
 */

	if(isset($_GET['id']) and ($article['auteur']!=$_SESSION['nom'] or count($article)==0))
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<h1 class="text-center">Veuillez activer JavaScript</h1>';
	else{ // La fin de l'accolade se trouve en fin de code

?>

	<ol class="breadcrumb">
		<li><a href="../Accueil.php">Accueil</a></li>
		<li><a href="Actualite.php">Actualités</a></li>
		<?php
			if($_SESSION['categorie']=='moderateur' and $article['validation']==0) // Dans le cas où l'article n'est pas validé
				echo '<li>
					<a href="Validation_Article.php">Validation Articles</a>
					</li>';
				
		?>
		<li class="active">Ecrire un article</li>
	</ol>

	<div class="container">
		<h1 class="text-center">Ecrire un article</h1>
		<div class="row">
			<div class="col-md-2">
			<?php

/*
 * Si on arrive sur la page en passant par la page d'Article, en appuyant sur précédent,
 * on doit retourner à la page de l'article.
 */

				if(isset($_GET['id']) and $id>0)
					echo '<a href="Article.php?id='.$id.'">';

/*
 * Si on arrive sur la page en passant par un moyen différent, en appuyant sur précédent,
 * on doit retourner sur la première page d'Actualité
 */

				else
					echo '<a href="Actualite.php?id='.$page.'">';
			?>
					
				<span class="glyphicon glyphicon-arrow-left"></span>
					Précédent
				</a>
			</div>
			<?php

/*
 * On va pouvoir supprimer un article EXISTANT dans le cas où :
 * 	- On est l'auteur de l'article
 *	- On est modérateur
 */

				if(!empty($article) and ($article['auteur']==$_SESSION['nom'] or $_SESSION['categorie']=='moderateur'))
					echo '<div class="col-md-2 col-md-offset-8">
						<a href="'.htmlentities($_SERVER['PHP_SELF']).'?suppression=1&id='.$id.'">
							<span class="glyphicon glyphicon-remove"></span>
							Supprimer l\'article
						</a>
					</div>';
			?>
		</div>
		<hr>
	</div>

	<div class="container">
		<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
			<label>Titre de l'article</label><br/>
			<div class="form-group">
			<?php if(isset($_POST['titre']) and trim($_POST['titre'])=='') echo '<p>Veuillez mettre un titre à votre article</p>';?>
			<input class="form-control" type="text" name="titre" maxlength="255" value="<?php echo $article['titre'];?>">
			</div>
			<input type="hidden" name="id" value="<?php echo $id;?>">
			<input type="hidden" name="auteur" value="<?php echo $article['auteur'];?>">
			<label>Votre contenu d'article</label>
			<?php if(isset($_POST['contenu']) and trim($_POST['contenu'])=='') echo '<p>Veuillez mettre un contenu à votre article</p>';?>
			<textarea class="wysibb" name="contenu"><?php echo $article['corps'];?></textarea><br/>
			<?php
/*
 * Dans le cas où l'on souhaite modifier l'article EXISTANT, on aura un bouton pour modifier 
 * sinon bouton normal. 
 */

				if(!empty($article))
					echo '<input type="submit" class="btn btn-default" name="modifier" value="Modifier">';
				else
					echo '<input type="submit" class="btn btn-default" name="envoyer" value="Envoyer">';
			?>
		</form>
	</div>

<?php 
	require('footer.php');

/*
 * On va écrire sur l'article dans la base de données
 * Attribut nécessaire :
 * $_POST['titre'] = Titre de l'article - Vérification obligatoire -
 * $today = date("Y-m-d H:i:s"); pour la date d'écriture - Format MySQL DATETIME -
 * $_SESSION['nom'] = nom de la personne connecté - Vérification obligatoire -
 * $_POST['contenu'] = Contenu de l'article - Vérification obligatoire -
 */

	$today = date("Y-m-d H:i:s");
	$titre = htmlspecialchars($_POST['titre']);
	$contenu = htmlspecialchars($_POST['contenu']);

/*
 * Pour toute action sur l'article déjà existant
 * Attribut nécessaire :
 * $_POST['id'] pour avoir id article pour la modification - Vérification obligatoire -
 * $_GET['suppression'] pour une suppression - Vérification obligatoire -
 * $_GET['id'] pour avoir id article pour la suppression - Vérification obligatoire -
 */
	$auteur = htmlspecialchars($_POST['auteur']);
	$id = intval($_POST['id']);
	$suppression = intval($_GET['suppression']);
	$id_suppresion = intval($_GET['id']);

/*
 ***************************************************************************************************
 *		MODIFICATION ARTICLE
 ***************************************************************************************************
 *
 * Seul l'auteur de l'article peut le modifier
 */

	if(trim($titre)!='' and trim($contenu)!='' and $auteur==$_SESSION['nom'] and isset($_POST['modifier'])){
		$req = 'UPDATE Article SET titre = :title, corps = :body, validation = 0 WHERE id_article = :id';echo 'ici2';
		$requete = $bd->prepare($req);echo 'ici1';
		$requete->bindValue(':id', $id); // Vérification attaque par injection
		$requete->bindValue(':title', $titre); // Vérification attaque par injection
		$requete->bindValue(':body', $contenu); // Vérification attaque par injection
		$requete->execute();
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<br />
		<h1 class="text-center">Article modifié !</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	}

/*
 ***************************************************************************************************
 *		ECRITURE ARTICLE
 ***************************************************************************************************
 */

	elseif(trim($titre)!='' and trim($contenu)!='' and !empty($_SESSION['nom']) and isset($_POST['envoyer'])){
		$req = "INSERT INTO Article (titre, jour, auteur, corps)
			VALUES (:title, :day, :author, :body)";
		$requete = $bd->prepare($req);
		$requete->bindValue(':title', $titre); // Vérification attaque par injection
		$requete->bindValue(':day', $today); // Vérification attaque par injection
		$requete->bindValue(':author', $_SESSION['nom']); // Vérification attaque par injection
		$requete->bindValue(':body', $contenu); // Vérification attaque par injection
		$requete->execute();
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<br />
		<h1 class="text-center">Article envoyé !</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	}

/*
 * Suppression de l'article avec ses commentaires
 */

	elseif(($article['auteur']==$_SESSION['nom'] or $_SESSION['categorie']=='moderateur') and $suppression==1){
		$req = 'DELETE FROM Commentaire WHERE id_article = :id';
		$requete = $bd->prepare($req);
		$requete->bindValue(':id', $id_suppresion); // Vérification attaque par injection
		$requete->execute();
		$req = 'DELETE FROM Article WHERE id_article = :id';
		$requete = $bd->prepare($req);
		$requete->bindValue(':id', $id_suppresion); // Vérification attaque par injection
		$requete->execute();
		echo '<script>
			document.location.href="Actualite.php"
		</script>
		<br />
		<h1 class="text-center">Article supprimé !</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	}

/*
 * Fermeture des différents else par rapport aux désactivation JavaScript
 */
	}
	}
?>

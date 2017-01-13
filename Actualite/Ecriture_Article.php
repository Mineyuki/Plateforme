<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Ecrire un article</title>
<?php 
	require('body.php');

/*
 * Si la personne n'est pas connecté, il est renvoyé sur la page de connexion
 */

	if(empty($_SESSION['connexion']))
		echo "<script>
			document.location.href=\"../Connexion.php\"
		</script>";

/*
 * Stagiaire et Professeur peuvent publier des articles.
 * On aura besoin de la base de donnée pour accéder à l'article pour le modifier
 */

	require('../co.php');

/*
 * On cherche l'article correspondant à son id si la personne souhaite modifier ou supprimer l'article
 */

	if(isset($_GET['id']) and trim($_GET['id'])!=''){

/*
 * Vérification de l'id si faille XSS
 */

		$id = htmlspecialchars($_GET['id']);

/*
 * On recherche l'article correspondant à l'id passé en paramètre
 */

		$req = 'SELECT * FROM Article where id_article = :id';
		$requete = $bd->prepare($req);

/*
 * Vérification de l'id si attaque par injection
 */

		$requete->bindValue(':id',$id);
		$requete->execute();

/*
 * Recherche la première ligne de réponse de la requête SQL
 */
		$article = $requete->fetch(PDO::FETCH_ASSOC);

/*
 * Si ce n'est pas l'auteur de l'article, on renvoit la personne sur la page d'Actualité
 * Si aucun article pour l'id correspondant n'existe, on renvoit sur la page d'Actualité
 */

		if($article['auteur']!=$_SESSION['nom'] or count($article)==0)
			echo "<script>
				document.location.href=\"Actualite.php\"
			</script>";
	}
?>

		<ol class="breadcrumb">
			<li><a href="../Accueil.php">Accueil</a></li>
			<li><a href="Actualite.php">Actualité</a></li>
			<li class="active">Ecrire un article</li>
		</ol>

		<div class="container">
			<h1 class="text-center">Ecrire un article</h1>
			<div class="row">
				<div class="col-md-2">
					<?php

/*
 * Si on arrive sur la page en passant par la page d'Actualité, en appuyant sur précédent, 
 * on doit retourner à la dernière page (exemple : page 1) de l'Actualité.
 */

						if(isset($_GET['page']) and trim($_GET['page'])!=''){

/*
 * Vérification faille XSS pour le paramètre du numéro de page d'Actualité
 */

							$page = htmlspecialchars($_GET['page']);
							echo "<a href=\"Actualite.php?page=$page\">";
						}

/*
 * Si on arrive sur la page en passant par la page d'Article, en appuyant sur précédent,
 * on doit retourner à la page de l'article.
 */

						elseif(isset($_GET['id']) and trim($_GET['id'])!='')
							echo "<a href=\"Article.php?id=$id\">";

/*
 * Si on arrive sur la page en passant par un moyen différent, en appuyant sur précédent,
 * on doit retourner sur la première page d'Actualité
 */
						else
							echo "<a href=\"Actualite.php\">";
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

					if(!empty($article) 
						and ($article['auteur']==$_SESSION['nom'] or $_SESSION['categorie']=='moderateur')){
						echo "<div class=\"col-md-2 col-md-offset-8\">
							<a href=\"".htmlentities($_SERVER['PHP_SELF'])."?suppression=1\">
								<span class=\"glyphicon glyphicon-remove\"></span>
								Supprimer l'article
							</a>
						</div>";
					}
				?>
			</div>
			<hr>
		</div>

		<div class="container">
			<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
				<label>Titre de l'article</label><br/>
				<div class="form-group">
				<input class="form-control" type="text" name="titre" maxlength="255" value="<?php echo $article['titre'];?>">
				</div>
				<textarea class="wysibb" name="contenu"><?php echo $article['corps'];?></textarea><br/>
				<?php
/*
 * Dans le cas où l'on souhaite modifier l'article EXISTANT, on aura un bouton pour modifier 
 * sinon bouton normal. 
 */
					if(!empty($article))
						echo "<button type=\"submit\" class=\"btn btn-default\" name=\"modifier\" value=\"$id\">Modifier</button>";
					else
						echo "<button type=\"submit\" class=\"btn btn-default\" name=\"envoyer\" value=\"envoyer\">Envoyer</button>";
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
 * $_POST['modifier'] pour modification
 * $_GET['suppression'] pour une suppression
 */

	$id = htmlspecialchars($_POST['modifier']);
	$suppression = htmlspecialchars($_GET['suppression']);

/*
 * Modification de l'article
 * Seul l'auteur de l'article peut le modifier
 */

	if(!empty($titre) 
		and trim($contenu)!='' 
		and $article['auteur']==$_SESSION['nom'] 
		and isset($_POST['modifier'])){
		$req = 'UPDATE Article SET titre = :title, corps = :body, validation = 0 WHERE id_article = :id';
		$requete = $bd->prepare($req);

/*
 * Vérification attaque par injection
 */

		$requete->bindValue(':id', $id);
		$requete->bindValue(':title', $titre);
		$requete->bindValue(':body', $contenu);

		$requete->execute();
		echo "<script>
			document.location.href=\"Actualite.php\"
		</script>";
	}

/*
 * Envoie de l'article
 */

	if(!empty($titre) and isset($_SESSION['nom']) 
		and trim($_SESSION['nom'])!='' 
		and isset($_POST['envoyer'])){
		$req = "INSERT INTO Article (titre, jour, auteur, corps)
			VALUES (:title, :day, :author, :body)";
		$requete = $bd->prepare($req);

/*
 * Vérification attaque par injection
 */

		$requete->bindValue(':title', $titre);
		$requete->bindValue(':day', $today);
		$requete->bindValue(':author', $_SESSION['nom']);
		$requete->bindValue(':body', $contenu);

		$requete->execute();
		echo "<script>
			document.location.href=\"Actualite.php\"
		</script>";
	}

/*
 * Suppression de l'article avec ses commentaires
 */

	if(($article['auteur']==$_SESSION['nom'] or $_SESSION['categorie']=='moderateur')
		and $suppression==1){
		$req = 'DELETE FROM Commentaire. WHERE id_article = :id';
		$requete = $bd->prepare($req);
		$requete->bindValue(':id', $id);
		$requete->execute();
		echo "<script>
			document.location.href=\"Actualite.php\"
		</script>";
	}
?>

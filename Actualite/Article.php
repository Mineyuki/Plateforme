<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Ecrire un article</title>
<?php require('body.php');

/*
 * Seules les personnes autorisées peuvent accéder à cette page.
 * Il faudra faire une vérification au niveau de l'autorisation d'écriture d'article
 * Pour le moment, on considère que tous le monde peut écrire des articles.
 * Il faudra par la suite établir un attribut dans la base SQL pour l'autorisation
 */
	if(!isset($_SESSION['connexion']))
	{
		header("Location:Actualite.php");
		exit();
	}
?>

		<ol class="breadcrumb">
			<li><a href="../Accueil.php">Accueil</a></li>
			<li><a href="Actualite.php">Actualité</a></li>
			<li class="active">Ecrire un article</li>
		</ol>

		<div class="container">
			<h1 class="text-center">Ecrire un article</h1>
			<hr>
		</div>

		<div class="container">
			<form method="GET" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
				<label>Titre de l'article</label>
				<input type="text" name="titre" size="100" maxlength="255"><br/><br/>
				<textarea class="ckeditor" name="editor"></textarea><br/>
				<input type="submit" value="Envoyer">
			</form>
		</div>

		<script src="../ckeditor/ckeditor.js"></script>
		<script>
		    CKEDITOR.replace( 'editor' );
		</script>

<?php require('footer.php');?>

<?php

/*
 * On va écrire sur l'article dans la base de données
 * Attribut nécessaire :
 * $_GET['titre'] = Titre de l'article - Vérification obligatoire -
 * $today = date("Y-m-d H:i:s"); pour la date d'écriture - Format MySQL DATETIME -
 * $_SESSION['nom'] = nom de la personne connecté - Vérification obligatoire -
 * $_GET['editor'] = Contenu de l'article - Problème de sécurité par la suite. On considérera que la personne en charge d'écrire l'article n'est pas censé nuire au site.
 */
	require('../co.php');

	$today = date("Y-m-d H:i:s"); 

	if(isset($_GET['titre']) and trim($_GET['editor'])!="" and isset($_SESSION['nom']) and trim($_SESSION['nom'])){		
		$req = "INSERT INTO Article (titre, jour, auteur, corps)
			VALUES (:title, :day, :author, :body)";
		$requete = $bd->prepare($req);
		$requete->bindValue(':title', $_GET['titre']);
		$requete->bindValue(':day', $today);
		$requete->bindValue(':author', $_SESSION['nom']);
		$requete->bindValue(':body', $_GET['editor']);
		$requete->execute();
		header("Location:Actualite.php");
		exit();
	}
?>

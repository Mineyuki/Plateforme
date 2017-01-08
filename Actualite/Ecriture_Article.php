<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Ecrire un article</title>
<?php 
	require('body.php');
/*
 * Seules les personnes autorisées peuvent accéder à cette page.
 * Dans la table membres, les personnes autorisées sont ceux dont la valeur d'écriture_article est égale à 1
 */
	if($_SESSION['ecriture_article']!=1)
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
			<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
				<label>Titre de l'article</label><br/>
				<div class="form-group">
				<input class="form-control" type="text" name="titre" maxlength="255">
				</div>
				<textarea class="wysibb" name="contenu"></textarea><br/>
				<button type="submit" class="btn btn-default">Envoyer</button>
			</form>
		</div>

<?php require('footer.php');?>

<?php

/*
 * On va écrire sur l'article dans la base de données
 * Attribut nécessaire :
 * $_POST['titre'] = Titre de l'article - Vérification obligatoire -
 * $today = date("Y-m-d H:i:s"); pour la date d'écriture - Format MySQL DATETIME -
 * $_SESSION['nom'] = nom de la personne connecté - Vérification obligatoire -
 * $_POST['contenu'] = Contenu de l'article - Vérification obligatoire -
 * $_SESSION['ecriture_article'] == 1 - Vérification optionnelle -
 */
	require('../co.php');

	$today = date("Y-m-d H:i:s");
	$titre = htmlspecialchars($_POST['titre']);
	$contenu = htmlspecialchars($_POST['contenu']);
	if(isset($titre) and trim($contenu)!="" and isset($_SESSION['nom']) and trim($_SESSION['nom'])!="" and $_SESSION['ecriture_article']==1 ){
		$req = "INSERT INTO Article (titre, jour, auteur, corps)
			VALUES (:title, :day, :author, :body)";
		$requete = $bd->prepare($req);
		$requete->bindValue(':title', $titre);
		$requete->bindValue(':day', $today);
		$requete->bindValue(':author', $_SESSION['nom']);
		$requete->bindValue(':body', $contenu);
		$requete->execute();
		echo"<script>
			document.location.href=\"Actualite.php\"
		</script>";
	}
?>

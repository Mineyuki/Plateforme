<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php 
	require('body.php');
/*
 * Connexion à la base de donnée
 */
	require('../co.php');
/*
 * On aura besoin de ce document pour parser le BBCode (convertir en html)
 */
	require('../jBBCode-1.3.0/JBBCode/Parser.php');

/*
 * L'id passé en paramètre doit être vérifié de toute faille XXS
 */
	$id = htmlspecialchars($_GET['id']);

/*
 * Ces lignes permettront de parser le BBCode.
 * C'est de la programmation orienté objet.
 */	
	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
	require('../jBBCode-1.3.0/JBBCode/new_JBBCode.php');

/*
 * Requête SQL classique où l'ont demande d'avoir l'article selon l'id.
 * Attention : vérification des attaques d'injections SQL !
 */

	$req = 'SELECT * FROM Article where id_article = :id';

/*
 * Préparation de la requête
 */

	$requete = $bd->prepare($req);

/*
 * Vérification des attaques injections SQL sur l'id
 */
	$requete->bindValue(':id',$id);

/*
 * Exécution de la requête
 */
	$requete->execute();

/*
 * On demande seulement la ligne où contient l'article.
 * Il n'existe pas d'article avec deux id identique.
 */
	$article = $requete->fetch(PDO::FETCH_ASSOC);

/*
 * Si l'id n'existe pas, on retourne sur la page d'Actualité.
 * L'utilisateur a surement touché à l'URL. Ligne de prévention d'attaque ou autre.
 */
	if($article['id_article']!=$id)
		echo "<script>document.location.href=\"Actualite.php\"</script>";
?>
	<ol class="breadcrumb">
		<li><a href="../Accueil.php">Accueil</a></li>
		<li><a href="Actualite.php">Actualité</a></li>
		<li class="active"><?php echo $article['titre'];?></li>
	</ol>

	<div class="container">
		<h1 class="text-center"><?php echo $article['titre'];?></h1>
		<hr>
	</div>
	
	<div class="container">
		<?php
/*
 * Seul l'auteur de l'article peut modifier l'article.
 * Il serait malheureux si il ne le pouvait pas !
 */
			if($_SESSION['ecriture_article']==1 and $article['auteur']==$_SESSION['nom'])
				echo "<p><a href=\"Ecriture_Article.php?id=".$article['id_article']."\">Modifier l'article</a></p>";
			echo "<p>".$article['jour']." - ".$article['auteur']."</p>";

/*
 * L'article s'affichera (contenu). En général, il ne devrait pas comporter d'erreur MAIS des erreurs peuvent se produire dû à la traduction
 * BBCode en html. Il faudra modifier le fichier s'occupant du parsage.
 */

			$parser->parse($article['corps']);
			echo $parser->getAsHtml();
		?>
	</div>

<?php require('footer.php');?>

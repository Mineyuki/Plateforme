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
 * L'id et le numéro de page passé en paramètre doit être vérifié de toute faille XXS
 */

	$id = htmlspecialchars($_GET['id']);
	$page = htmlspecialchars($_GET['page']);

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

	$req = 'SELECT id_article, titre, DATE_FORMAT(jour,\'%d %b %Y %T\'), auteur, corps FROM Article where id_article = :id';

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
		<a href="Actualite.php?page=<?php echo $page;?>">
			<span class="glyphicon glyphicon-arrow-left"></span>
			Précédent
		</a>
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
			echo "<p>".$article['DATE_FORMAT(jour,\'%d %b %Y %T\')']." - ".$article['auteur']."</p>";

/*
 * L'article s'affichera (contenu). En général, il ne devrait pas comporter d'erreur MAIS des erreurs peuvent se produire dû à la traduction
 * BBCode en html. Il faudra modifier le fichier s'occupant du parsage.
 */

			$parser->parse($article['corps']);
			echo $parser->getAsHtml();
		?>
	</div>
	
	<?php

/*
 * Il est obligatoire de se connecter pour pouvoir poster un commentaire
 */

		if(isset($_SESSION['connexion'])){
			echo "<div class=\"container\">
				<hr>
					<form method=\"POST\" action=\"".htmlentities($_SERVER['PHP_SELF'])."\">
						<label>Titre du commentaire</label><br/>
						<div class=\"form-group\">
						<input class=\"form-control\" type=\"text\" name=\"titre\" maxlength=\"255\">
						</div>
						<textarea class=\"minime\" name=\"contenu\"></textarea><br/>
						<input value=\"$id\" name=\"cache\" type=\"hidden\">
						<input value=\"$page\" name=\"cache2\" type=\"hidden\">
						<button type=\"submit\" class=\"btn btn-default\">Envoyer</button>
					</form>
			</div>";
		}

/*
 * Envoie du commentaire
 */
		$titre = htmlspecialchars($_POST['titre']);
		$contenu = htmlspecialchars($_POST['contenu']);
		$today = date("Y-m-d H:i:s");
		$cache = htmlspecialchars($_POST['cache']);
		$cache2 = htmlspecialchars($_POST['cache2']);

		if(isset($titre) and trim($contenu)!='' and isset($_SESSION['nom']) and trim($_SESSION['nom']!='')){
			$req = 'INSERT INTO Commentaire (id_article, jour, pseudo, commente) VALUES (:id_article, :jour, :pseudo, :commentaire)';
			$requete = $bd->prepare($req);
			$requete->bindValue(':id_article',$cache);
			$requete->bindValue(':jour',$today);
			$requete->bindValue(':pseudo', $_SESSION['nom']);
			$requete->bindValue(':commentaire', $contenu);
			$requete->execute();
			echo "<script>";
			echo "document.location.href=\"Article.php?id=".$cache."&page=".$cache2."\"";
			echo "</script>";
		}

/*
 * Recherche dans la base de donnée la liste de commentaire
 */

		$req = "SELECT id_article, id_commentaire, DATE_FORMAT(jour,'%d %b %Y %T'), pseudo, commente FROM Commentaire WHERE id_article = :id_article ORDER BY id_commentaire";
		$requete = $bd->prepare($req);
		$requete->bindValue(':id_article', $id);
		$requete->execute();
		echo "<div class=\"container\">
			<hr>
			<h2>Commentaire</h2>
			</div>";
		while($commentaire = $requete->fetch(PDO::FETCH_ASSOC)){
			echo "<div class=\"container\">
				<p><strong>".$commentaire['pseudo']."</strong> - ".$commentaire['DATE_FORMAT(jour,\'%d %b %Y %T\')']."</p>
				<p>";
			$parser->parse($commentaire['commente']);
			echo $parser->getAsHtml()."</p>
				<hr>
				</div>";
		}
	?>



<?php require('footer.php');?>

<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Actualité</title>
<?php 
	require('body.php');
	require('../co.php');
/*
 * On aura besoin de ce document pour parser le BBCode (convertir en html)
 */
	require('../jBBCode-1.3.0/JBBCode/Parser.php');
?>

<?php
	$id = htmlspecialchars($_GET['id']);
	
	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

	$req = 'SELECT * FROM Article where id_article = :id';
	$requete = $bd->prepare($req);
	$requete->bindValue(':id',$id);
	$requete->execute();

	$article = $requete->fetch(PDO::FETCH_ASSOC);
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
		<p><?php echo $article['jour'];?> - <?php echo $article['auteur'];?></p>
		<?php
			$parser->parse($article['corps']);
			echo $parser->getAsHtml();
		?>
	</div>

<?php require('footer.php');?>

<?php require('../head.php');?>
	<!-- Nom des onglets -->
		<title>Ecrire un article</title>
<?php require('body.php');
	
	/*if(!isset($_SESSION['connexion']))
	{
		header("Location:../Connexion.php");
		exit();
	}*/
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
				<input type="text" name="titre" size="100"maxlength="255"><br/><br/>
				<textarea class="ckeditor" name="editor"></textarea><br/>
				<input type="submit" value="Envoyer">
			</form>
		</div>

		<script src="../ckeditor/ckeditor.js"></script>
		<script>
		    CKEDITOR.replace( 'editor' );
		</script>

<?php require('footer.php');?>

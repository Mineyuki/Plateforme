<?php 
	
	require('head.php'); //En-tête html
	
?>

<?php
			//On le supprime
			try
			{
			   
	$fileName = $_GET['url'].$_GET['name'];

	unlink($fileName);

	header('Location:'.$_GET['formation'].'');
			}
			catch(PDOException $e)
			{
				die('<div> Erreur : ' . $e->getMessage() . '</div></body></html>');
			}

?>


</div>

<?php require('footer.php'); ?>
<?php
/*if(!isset($_SESSION['connexion']))
	{
		header("Location:../Connexion.php");
		exit();
	}*/
// Si la personne n'est pas connecté, il est renvoyé sur la page de connexion

if(empty($_SESSION['connexion']))
		echo '<script>
			document.location.href="../Connexion.php"
		</script>
		<br />
		<h1 class="text-center">Vous devez être connecté(e) pour accéder à cette page.</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	else{
	
	// Il y a que les enseignant peuvent d'acceder aux tous les ressources de touts les formations
	elseif ($_SESSION['categorie']!='stagiaire'){

		echo '<div class="col-md-4 sidebar ">
            <ul class="nav nav-default nav-stacked">
               <li><a href="rMDI.php">MDI</a></li>
               <li><a href="rGCRHM.php">GCRHM</a></li>
               <li><a href="rJHF.php">JNF</a></li>
               <li><a href="rISL.php">ISL</a></li>
               <li><a href="rRT.php">RT</a></li>
               <li><a href="rEEIIN.php">EEIIN</a></li>
              
                 </ul>	
          </div><br>';
        echo '</a><a href="Ressource.php"><span class="glyphicon glyphicon-plus"></span> AJOUTER<br>';

}
?>

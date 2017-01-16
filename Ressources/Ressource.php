
<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Ressource</title>
<?php require('body.php');?>
<div class="container" style="margin : 100px">
		
<?php /*
	if(!isset($_SESSION['connexion']))
	{
		header("Location:../Connexion.php");
		exit();
	}*/
 //Si la personne n'est pas connecté, il est renvoyé sur la page de connexion

if(empty($_SESSION['connexion']))
		echo '<script>
			document.location.href="../Connexion.php"
		</script>
		<br />
		<h1 class="text-center">Vous devez être connecté(e) pour accéder à cette page.</h1>
		<h2 class="text-center">Veuillez activer le JavaScript</h2>';
	else{
	

	//droit d'acces selon leur formation et leur categorie
	if ($_SESSION['categorie']=='stagiaire'  ) {
	    if($_SESSION['formation']=='MDI'){
	       echo '<script>
			document.location.href="rMDI.php"
			</script>';
	    }
	    
	    elseif ($_SESSION['formation']=='GCRHM') {
	       echo '<script>
			document.location.href="rGCRHM.php"
			</script>';
	    }
	    
	    elseif ($_SESSION['formation']=='JHF') {
	        echo '<script>
			document.location.href="rJHF.php"
			</script>';
	    }
	    
	    elseif ($_SESSION['formation']=='ISL') {
	        echo '<script>
			document.location.href="rISL.php"
			</script>';
	    }
	    
	    elseif ($_SESSION['formation']=='RT') {
			echo '<script>
			document.location.href="rRT.php"
			</script>';
	    }
	    
	    elseif ($_SESSION['formation']=='EEIIN') {
	       echo '<script>
			document.location.href="rEEIIN.php"
			</script>';
	    }
	    
	}
	
	elseif ($_SESSION['categorie']=='enseignant') {
	    echo'<p>Vous êtes sur la formation '.$_SESSION['formation'].'. </p>';
	}

}

	
?>

<!--nav pour que les enseignants peuvent regarder les fichiers de touts les formations-->
          <div class="col-md-4 sidebar ">
            <ul class="nav nav-default nav-stacked">
               <li><a href="rMDI.php">MDI</a></li>
               <li><a href="rGCRHM.php">GCRHM</a></li>
               <li><a href="rJHF.php">JNF</a></li>
               <li><a href="rISL.php">ISL</a></li>
               <li><a href="rRT.php">RT</a></li>
               <li><a href="rEEIIN.php">EEIIN</a></li>
                
                </ul>	
          </div>


       <div>
           <!--la formulaire qui permet de deposer un fichier(value est la taille maxinum du fichier, on peut changer en fonction de la demande du client)-->
    <form  action="upload_file.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="100000000000" />
			<br>
     <input type="file" name="file" class="btn btn-info"/>
        <br>
      <input type="submit" class="btn btn-info"/>
    </form>
    
    

     </div>
 </div>

<?php require('footer.php');?>




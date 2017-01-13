
<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Ressource</title>
		
<div class="container" style="margin : 100px">
		
<?php			
    if(!isset($_SESSION['connexion']))
	{
		header("Location:./../Connexion.php");
		exit();
	}
	//droit d'acces pour les stagiaires selon leur formation
	elseif ($_SESSION['categorie']=='stagiaire'  ) {
	    if($_SESSION['formation']=='MDI'){
	       header("Location:rMDI.php"); 
	    }
	    
	    elseif ($_SESSION['formation']=='GCRHM') {
	        header("Location:rGCRHM.php");
	    }
	    
	    elseif ($_SESSION['formation']=='JHF') {
	        header("Location:rJHF.php");
	    }
	    
	    elseif ($_SESSION['formation']=='ISL') {
	        header("Location:rISL.php");
	    }
	    
	    elseif ($_SESSION['formation']=='RT') {
	        header("Location:rRT.php");
	    }
	    
	    elseif ($_SESSION['formation']=='EEIIN') {
	        header("Location:rEEIIN.php");
	    }
	    
	}
	
	elseif ($_SESSION['categorie']=='enseignant') {
	    echo'<p>Vous êtes sur la formation '.$_SESSION['formation'].'. </p>';
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
<?php require('body.php');?>
<?php require('footer.php');?>




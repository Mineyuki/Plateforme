<?php require('head.php');?>
	<!-- Nom des onglets -->
<<<<<<< HEAD
		<title>Ressource</title>

<div class="container" style="margin : 100px">

          <div class="col-md-2 sidebar ">
=======
		

<div class="container" style="margin : 100px">
    <h1>Formations en Informatique, Systèmes, Logiciels</h1>
    <?php			
	if(!isset($_SESSION['connexion']))
	{
		header("Location:./../Connexion.php");
		exit();
	}
	// Il y a que les enseignant peuvent d'acceder aux tous les ressources de touts les formations
	elseif ($_SESSION['categorie']=='enseignant' ) { 
	    echo '<div class="col-md-4 sidebar ">
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
            <ul class="nav nav-default nav-stacked">
               <li><a href="rMDI.php">MDI</a></li>
               <li><a href="rGCRHM.php">GCRHM</a></li>
               <li><a href="rJHF.php">JNF</a></li>
               <li><a href="rRT.php">RT</a></li>
               <li><a href="rEEIIN.php">EEIIN</a></li>
<<<<<<< HEAD
                <li><a href="ressource.php">Ajouter</a></li>
                </form>
                </ul>	
          </div>


  <ul style="list-style-type:square">
      
<?php
=======
              
                 </ul>	
          </div><br>';
        echo '</a><a href="Ressource.php">AJOUTER <span class="glyphicon glyphicon-plus"></span><br>'; 

	}
?>

          

  <ul style="list-style-type:none">
      
<?php
//la liste des fichiers
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
$nom="rISL.php";
$dir = 'upload/rISL/';
if(is_dir($dir)) {
        if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                if($file!="." && $file!="..") {
                echo "<li><a href='".$dir.$file."'>".$file."</a><a href='./supprimer.php?name={$file}&url={$dir}&formation={$nom}'><span class=\"glyphicon glyphicon-remove\"></span></a></li>";

            }
        }
        closedir($dh);
     }
}
?>
</ul>
 </div>
<?php require('body.php');?>
<?php require('footer.php');?>

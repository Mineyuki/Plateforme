<?php require('head.php');?>
	<!-- Nom des onglets -->
		

<div class="container" style="margin : 100px">
    <h1>Formations en Réseaux, Télécommunications</h1>
    <?php require('enseignant.php');?>

          
  <ul style="list-style-type:none">
      
<?php
//la liste des fichiers
$nom='rRT.php';
$dir = 'upload/rRT/';
if(is_dir($dir)) {
        if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                if($file!="." && $file!="..") {
					if($_SESSION['categorie']=='stagiaire')
                		echo '<li><a href="'.$dir.$file.'">'.$file.'</a>';
					else	//avec le bouton supprimer
						echo '<li>
							<a href="supprimer.php?name='.$file.'&url='.$dir.'&formation='.$nom.'"> 
							<span class="glyphicon glyphicon-remove"></span>
							</a>
							<a href="'.$dir.$file.'">'.$file.'</a>														
							</li>';
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

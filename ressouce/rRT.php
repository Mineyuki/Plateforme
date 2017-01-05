<?php require('head.php');?>
	<!-- Nom des onglets -->
		

<div class="container" style="margin : 100px">

          <div class="col-md-2 sidebar ">
            <ul class="nav nav-default nav-stacked">
               <li><a href="rMDI.php">MDI</a></li>
               <li><a href="rGCRHM.php">GCRHM</a></li>
               <li><a href="rJHF.php">JNF</a></li>
               <li><a href="rRT.php">RT</a></li>
               <li><a href="rEEIIN.php">EEIIN</a></li>
               <li><a href="ressource.php">Ajouter</a></li> 
               
                </ul>	
          </div>


  <ul style="list-style-type:square">
      
<?php
$dir = 'upload/rRT/';
if(is_dir($dir)) {
        if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
                if($file!="." && $file!="..") {
               echo "<li><a href='".$dir.$file."'>".$file."</a><a href='supprimer.php?name={$file}&url={$dir}'><span class=\"glyphicon glyphicon-remove\"></span></a></li>";
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

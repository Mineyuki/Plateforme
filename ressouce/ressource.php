


<?php require('head.php');?>
	<!-- Nom des onglets -->
		<title>Ressource</title>

<div class="container" style="margin : 100px">

          <div class="col-md-2 sidebar ">
            <ul class="nav nav-default nav-stacked">
               <li><a href="rMDI.php">MDI</a></li>
               <li><a href="rGCRHM.php">GCRHM</a></li>
               <li><a href="rJHF.php">JNF</a></li>
               <li><a href="rISL.php">ISL</a></li>
               <li><a href="rRT.php">RT</a></li>
               <li><a href="rEEIIN.php">EEIIN</a></li>
               
               <li><a href="ressource.php">Ajouter</a></li> 
                
                </ul>	
          </div>


       <div>
    <form  action="upload_file.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
      	<p>  Les Formations : </p>
			<select name="formations">
				<option >Formation Modulaire et Diplômante Interuniversitaire </option>
				<option >Formations en Gestion, Comptabilité, Ressources Humaines, Management </option>
				<option >Formations en Juridique, Notariat, Finance </option>
				<option >Formations en Informatique, Systèmes, Logiciels </option>
				<option >Formations en Réseaux, Télécommunications </option>
				<option >Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies</option>
			
			</select>	
			<br>
     <input type="file" name="file" class="btn btn-info"/>
        <br>
      <input type="submit" class="btn btn-info"/>
    </form>
    
    

     </div>
 </div>
<?php require('body.php');?>
<?php require('footer.php');?>




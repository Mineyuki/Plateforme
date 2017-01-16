<?php require('head.php');?>
<?php
<<<<<<< HEAD

  if ($_FILES["file"]["error"] > 0)
  {
      echo "<script> {window.alert('Aucun fichier choisi');location.href='ressource.php'} </script>"; 
=======
try{
  if ($_FILES["file"]["error"] > 0)//verifier l'utilisateur avait choisi un fichier
  {
     echo "<script> {window.alert('Aucun fichier choisi');location.href='Ressource.php'} </script>"; 
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
  
  }
  else
  {

<<<<<<< HEAD
  
        $nomOrigine = $_FILES['file']['name'];
        $elementsChemin = pathinfo($nomOrigine);
        $extensionFichier = $elementsChemin['extension'];
       $extensionsAutorisees = array("pdf", "docx","odt", "zip","doc");


        if (!(in_array($extensionFichier, $extensionsAutorisees))) {
            
             echo "<script> {window.alert('Le fichier n\'a pas l\'extension attendue');location.href='ressource.php'} </script>"; 
           
            } else {    

         if($_POST["formations"]=="Formation Modulaire et Diplômante Interuniversitaire"){
             if (file_exists("upload/rMDI/" . $_FILES["file"]["name"]))
                {
                 echo "<script> {window.alert('Un fichier du même nom existe déjà');location.href='ressource.php'} </script>";    
=======
  $nomOrigine = $_FILES['file']['name'];
  $elementsChemin = pathinfo($nomOrigine);
  $extensionFichier = $elementsChemin['extension'];
  $extensionsAutorisees = array("pdf", "docx","odt", "zip","doc");


     //interdit les extensions non accordes
     if (!(in_array($extensionFichier, $extensionsAutorisees))) { 
            
        echo "<script> {window.alert('Le fichier n\'a pas l\'extension attendue');location.href='Ressource.php'} </script>"; 
           
      } else {    
          
          //enregistre les fichiers avec leur dossier correspendant
         if($_SESSION["formation"]=="Formation Modulaire et Diplômante Interuniversitaire"){
          //verifier que l'existence du fichier
             if (file_exists("upload/rMDI/" . $_FILES["file"]["name"]))
                {
                 echo "<script> {window.alert('Un fichier du même nom existe déjà');location.href='Ressource.php'} </script>";    
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
  
                }
            else
                 {
<<<<<<< HEAD
=======
                  //on enregistre
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
                 move_uploaded_file($_FILES["file"]["tmp_name"],
                        "upload/rMDI/" . $_FILES["file"]["name"]);
                    echo "Stored in: " . "upload/rMDI/" . $_FILES["file"]["name"];
 
                 }
            }
  
<<<<<<< HEAD
          elseif($_POST["formations"]=="Formations en Gestion, Comptabilité, Ressources Humaines, Management"){
  
		if (file_exists("upload/rGCRHM/" . $_FILES["file"]["name"])){
  			echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
=======
          elseif($_SESSION["formation"]=="Formations en Gestion, Comptabilité, Ressources Humaines, Management"){
  
		          if (file_exists("upload/rGCRHM/" . $_FILES["file"]["name"])){
  			echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='Ressource.php'} </script>";
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
 		 }

  		else{
			 move_uploaded_file($_FILES["file"]["tmp_name"], "upload/rGCRHM/" . $_FILES["file"]["name"]);
 			 echo "Stored in: " . "upload/rGCRHM/" . $_FILES["file"]["name"];
 
  		}
 	}

<<<<<<< HEAD
 	 elseif($_POST["formations"]=="Formations en Juridique, Notariat, Finance"){
 		
		if (file_exists("upload/rJHF/" . $_FILES["file"]["name"])){
 
			 echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
=======
 	 elseif($_SESSION["formation"]=="Formations en Juridique, Notariat, Finance"){
 		
		if (file_exists("upload/rJHF/" . $_FILES["file"]["name"])){
 
			 echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='Ressource.php'} </script>";
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
  		}

  		else{
			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/rJHF/" . $_FILES["file"]["name"]);
  			echo "Stored in: " . "upload/rJHF/" . $_FILES["file"]["name"];
 
  		}
  	}
  
<<<<<<< HEAD
 	elseif($_POST["formations"]=="Formations en Informatique, Systèmes, Logiciels"){
  		
		if (file_exists("upload/rISL/" . $_FILES["file"]["name"])){
 		
			 echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
=======
 	elseif($_SESSION["formation"]=="Formations en Informatique, Systèmes, Logiciels"){
  		
		if (file_exists("upload/rISL/" . $_FILES["file"]["name"])){
 		
			 echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='Ressource.php'} </script>";
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
 		 }
  
		else{

  			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/rISL/" . $_FILES["file"]["name"]);
 			echo "Stored in: " . "upload/rISL/" . $_FILES["file"]["name"];
 
  		}
 	 }
 	 
<<<<<<< HEAD
  	elseif($_POST["formations"]=="Formations en Réseaux, Télécommunications"){
  
		if (file_exists("upload/rRT/" . $_FILES["file"]["name"])){
  
			echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
=======
  	elseif($_SESSION["formation"]=="Formations en Réseaux, Télécommunications"){
  
		if (file_exists("upload/rRT/" . $_FILES["file"]["name"])){
  
			echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='Ressource.php'} </script>";
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
  		}
 
		 else{
  
			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/rRT/" . $_FILES["file"]["name"]);
  			echo "Stored in: " . "upload/rRT/" . $_FILES["file"]["name"];
 
 		 }
 	 }
  
<<<<<<< HEAD
 	 elseif($_POST["formations"]=="Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies"){
  
		if (file_exists("upload/rEEIIN/" . $_FILES["file"]["name"])){

  		echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
=======
 	 elseif($_SESSION["formation"]=="Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies"){
  
		if (file_exists("upload/rEEIIN/" . $_FILES["file"]["name"])){

  		echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='Ressource.php'} </script>";
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
  		
		}
 	
		else{
  
			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/rEEIIN/" . $_FILES["file"]["name"]);
 			echo "Stored in: " . "upload/rEEIIN/" . $_FILES["file"]["name"];
 
  			}
  	}
		
<<<<<<< HEAD
		echo "<script> {window.alert('Votre fichier est enregistré ');location.href='ressource.php'} </script>";
=======
		//annonce + retour à la page 
		echo "<script> {window.alert('Votre fichier est enregistré ');location.href='Ressource.php'} </script>";
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
  /*echo"<br />";
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
<<<<<<< HEAD
            echo "<center> <a href=ressource.php>continue</a></center>";*/
  	}         
  }
  //header('HTTP/1.1 301 Moved Permanently');
=======
            echo "<center> <a href=Ressource.php>continue</a></center>";*/
  	}         
  }
  }
			catch(PDOException $e)
			{
				die('<div> Erreur : ' . $e->getMessage() . '</div></body></html>');
			}

>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
 
?>
<?php require('body.php');?>
<?php require('footer.php');?>

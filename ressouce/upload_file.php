<?php require('head.php');?>
<?php
  if ($_FILES["file"]["error"] > 0)
  {
      echo "<script> {window.alert('Aucun fichier choisi');location.href='ressource.php'} </script>"; 
  //echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
  }
  else
  {

  
        $nomOrigine = $_FILES['file']['name'];
        $elementsChemin = pathinfo($nomOrigine);
        $extensionFichier = $elementsChemin['extension'];
        $extensionsAutorisees = array("pdf", "docx", "zip","doc");


        if (!(in_array($extensionFichier, $extensionsAutorisees))) {
            
             echo "<script> {window.alert('Le fichier n\'a pas l\'extension attendue');location.href='ressource.php'} </script>"; 
           
            } else {    

         if($_POST["formations"]=="Formation Modulaire et Diplômante Interuniversitaire"){
             if (file_exists("upload/rMDI/" . $_FILES["file"]["name"]))
                {
                 echo "<script> {window.alert('Un fichier du même nom existe déjà');location.href='ressource.php'} </script>";    
  
                }
            else
                 {
                 move_uploaded_file($_FILES["file"]["tmp_name"],
                        "upload/rMDI/" . $_FILES["file"]["name"]);
                    echo "Stored in: " . "upload/rMDI/" . $_FILES["file"]["name"];
 
                 }
            }
  
            elseif($_POST["formations"]=="Formations en Gestion, Comptabilité, Ressources Humaines, Management"){
  if (file_exists("upload/rGCRHM/" . $_FILES["file"]["name"]))
  {
  echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
  }
  else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"],
  "upload/rGCRHM/" . $_FILES["file"]["name"]);
  echo "Stored in: " . "upload/rGCRHM/" . $_FILES["file"]["name"];
 
  }
  }

  elseif($_POST["formations"]=="Formations en Juridique, Notariat, Finance"){
  if (file_exists("upload/rJHF/" . $_FILES["file"]["name"]))
  {
  echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
  }
  else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"],
  "upload/rJHF/" . $_FILES["file"]["name"]);
  echo "Stored in: " . "upload/rJHF/" . $_FILES["file"]["name"];
 
  }
  }
  
  elseif($_POST["formations"]=="Formations en Informatique, Systèmes, Logiciels"){
  if (file_exists("upload/rISL/" . $_FILES["file"]["name"]))
  {
  echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
  }
  else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"],
  "upload/rISL/" . $_FILES["file"]["name"]);
  echo "Stored in: " . "upload/rISL/" . $_FILES["file"]["name"];
 
  }
  }
  
  elseif($_POST["formations"]=="Formations en Réseaux, Télécommunications"){
  if (file_exists("upload/rRT/" . $_FILES["file"]["name"]))
  {
  echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
  }
  else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"],
  "upload/rRT/" . $_FILES["file"]["name"]);
  echo "Stored in: " . "upload/rRT/" . $_FILES["file"]["name"];
 
  }
  }
  
  elseif($_POST["formations"]=="Formations en Électronique, Électricité, Informatique Industrielle, Nanotechnologies"){
  if (file_exists("upload/rEEIIN/" . $_FILES["file"]["name"]))
  {
  echo "<script> {window.alert('Un fichier du même nom existe déjà ');location.href='ressource.php'} </script>";
  }
  else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"],
  "upload/rEEIIN/" . $_FILES["file"]["name"]);
  echo "Stored in: " . "upload/rEEIIN/" . $_FILES["file"]["name"];
 
  }
  }
  echo"<br />";
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "<center> <a href=ressource.php>continue</a></center>";
  }         
  }
  //header('HTTP/1.1 301 Moved Permanently');
 
?>
<?php require('body.php');?>
<?php require('footer.php');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>longue fiche du Film</title>
<link href="css/bluraycss.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
  <div class="header">
  <?php require "includes/entete.php"; //ajoute le menu de navigation ?>   
  </div>
  <div class="sidebar1">
  <?php require "includes/sidebar1.ssi"; //ajoute le menu de navigation ?>   
  </div> <!-- end .sidebar1 -->
  <div class="content">
    <h1>fiche détaillé du film N°<?php echo $_GET['ID']; ?>:</h1>

<?php
//pas besoin d'être connecté pour avoir accès à la page
if(isset($_GET['ID'])){
    require "includes/connectbdd.php";

    $filmid = $_GET['ID'];
    
    $query = "SELECT * FROM t_films WHERE ID_film=".$filmid.";";
    //On recupère l'entiereté des information de la table t_films
    $result = $connexion->query($query);
    $result = $result->fetch_assoc();
    //on met les valeurs dans un tableau 
    $champ = array_keys($result);
    //$champ represente le nom des champs de la base comme IDutilisateur par exemple et on créer un tableau qui affichera le resultat.
    echo "<table border=1px width=80%>";
    foreach(array_combine($champ, $result) as $value => $nom){
        echo "<tr><th>".$value."</th><td>".$nom."</td></tr>";  
    }
  echo "</table>";

}

else{
    header("location:index.php");
}


?>

    <!-- end .content --></div>
    <div class="footer">
    <?php require "includes/footer.ssi"; ?>  
    </div>
  <!-- end .container --></div>
</body>
</html>
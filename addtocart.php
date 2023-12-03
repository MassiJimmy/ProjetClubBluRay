<?php

if (isset($_GET['IDfilm'])){
    session_start();
    require "includes/connectbdd.php";
    $cible=$_SERVER['HTTP_REFERER'];
    $id  = $_SESSION["Authenticated"]["monID"];
    //faut être identifié
    $today = date("y-m-d");
    //On stock dans une variable la date de rendu en y ajoutant 15jours
    $date_rendu = date("y-m-d", strtotime($today."+15 days"));
    //On insert afin de mettre le film en emprunter avec les dates pris avant
    $query= "INSERT INTO emprunter VALUES (".$id.",".$_GET["IDfilm"].",'".$today."','".$date_rendu."',NULL);";
    $connexion->query($query) or die("echec de la requête");
    header("location: ".$cible."");
}
else{
    header("location:index.php");
}
?>
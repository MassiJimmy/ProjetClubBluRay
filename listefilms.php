<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>BluRayClub liste des BA</title>
<link href="css/bluraycss.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
  <div class="header">
  	<?php require "includes/entete.php"; //ajoute le menu d'authentification ?> 
  </div><!-- end .header -->
  <div class="sidebar1">
    <?php require "includes/sidebar1.ssi"; ?>   
  </div>
  <div class="content">
  <h1>Bande annonce du film N°<?php echo $_GET['ID']; ?>:</h1>
<?php
//pas besoin d'être connecté
require "includes/connectbdd.php";
$fiche=$_GET['ID'];
$query='SELECT Titre, URL_BA FROM t_films WHERE URL_BA !="" AND ID_film = "'.$fiche.'"';
// echo $query;
$result=$connexion->query($query) or die("echec de la requête");
$BA = $result->fetch_assoc();
$url = $BA;
echo '<h2>Trailer de '.$BA['Titre'].' </h2>';
//str replace remplace un élément d'une variable par une autre ce qui est pratique car pour mettre les vidéo en miniature il faut pour cela mettre le lien en embed
echo '<iframe width="1000" height="500"
src="'.str_replace("watch?v=","embed/",$BA['URL_BA']).'">
</iframe> '; 
//retour à la page d'avant
echo '<a href="fichefilm.php?ID='.$fiche.'"><h3>Retourner sur la fiche du film</h3></a>';

$connexion->close();
?>
	</div> <!-- end list des films -->
</div>    <!-- end .content -->
    <div class="footer">
    <?php require "includes/footer.ssi"; ?>  
    </div>
  <!-- end .container --></div>
</body>
</html>
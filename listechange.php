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
  <h1>Les échanges disponibles pour vous:</h1>
<?php
require "includes/connectbdd.php";
if(isset($_SESSION['Authenticated']) )
{ // l'utilisateur est cconnecté 
    $IDconnectedUser=$_SESSION['Authenticated']['monID'];
    $query1 = "SELECT t_films.ID_film , t_films.Titre , offrir.IDproprio FROM t_films INNER JOIN offrir ON t_films.ID_film = offrir.IDfilm WHERE offrir.echanger IS TRUE AND offrir.IDproprio != $IDconnectedUser ";
    $result1 = $connexion->query($query1) or die("échec de la requête des echanges");;
    
    echo '<table class="blueTable" style="text-align:center;">';
    echo "<tr><th>FilmID</th><th>Titre</th><th>IDproprio</th>
    <th>échanger</th></tr>";
    while ($value = mysqli_fetch_array($result1))
		{
		// on affiche les résultats dans des lignes de formulaire
		echo '<form action="listechange.php" method="post" ';
		echo '<tr>';
		// un td visible muni d'un champ caché pour transmetre ID_film sans pouvoir le modifier
		echo '<td>'.$value['ID_film'].'</td>';						
		echo '<td>'.$value['Titre'].'</td>';
        echo '<td>'.$value['IDproprio'].'</td>';
        echo '<td> <input type="checkbox" value="echanger" unchecked></td>';	
        echo '</tr>';
		echo '</form>';
        }
    echo '</table>';

    echo '<h1>Vos propositions:</h1>';
    $query2 = "SELECT t_films.ID_film , t_films.Titre , offrir.IDproprio FROM t_films INNER JOIN offrir ON t_films.ID_film = offrir.IDfilm WHERE offrir.echanger IS TRUE AND offrir.IDproprio = $IDconnectedUser ";
    $result2 = $connexion->query($query2) or die("échec de la requête des echanges");

    echo '<table class="blueTable" style="text-align:center;">';
    echo "<tr><th>FilmID</th><th>Titre</th></tr>";
    while ($value1 = mysqli_fetch_array($result2))
		{
		// on affiche les résultats dans des lignes de formulaire
		echo '<form action="listechange.php" method="post" ';
		echo '<tr>';
		// un td visible muni d'un champ caché pour transmetre ID_film sans pouvoir le modifier
		echo '<td>'.$value1['ID_film'].'</td>';						
		echo '<td>'.$value1['Titre'].'</td>';	
        echo '</tr>';
		echo '</form>';
        }
    echo '</table>';
}

else echo ' <h2 class="danger">  Il faut être identifié pour utiliser cette page !</h2>';
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
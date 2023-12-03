<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>BluRayClub Gestion des emprunts</title>
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
   <h1>Gestion des Retours des emprunts: </h1>
	<?php
	//Si il est connecté membre ou admin
    if(isset($_SESSION['Authenticated']))
    {
         // l'utilisateur connecté a les privilèges "Administrateur"
			require "includes/connectbdd.php";
            $IDconnectedUser=$_SESSION['Authenticated']['monID'];
			$today=date("Y-m-d");
			if(!empty($_GET['IDuser']) && !empty($_GET['IDfilm']) &&!empty($_GET['date_pris']))
				{	// appel avec paramètres dans le $_GET
					$query="UPDATE emprunter SET date_rendu = '".$today."'
					WHERE IDemprunteur = '".$_GET['IDuser']."' 
					AND IDfilm = '".$_GET['IDfilm']."'
					AND date_pris = '".$_GET['date_pris']."'
					;";
					// echo $query;
					$result=$connexion->query($query) or die("echec de la requête maj date retour");
					// rappeler la page sans paramètre pour que s'affiche la liste mise à jour
					header("location:gestretours.php");
				}
        else 
        {	// appel sans paramètre
                    $query='SELECT emprunter.*, t_films.Titre , t_membres.IDuser ,t_membres.IDuser
					FROM (emprunter INNER JOIN t_membres ON IDemprunteur=IDuser) INNER JOIN t_films ON IDfilm = ID_film
					WHERE t_membres.IDuser = '.$IDconnectedUser.' AND date_rendu  IS NULL
                    ORDER BY date_due ASC;';
					// echo $query;
					$result=$connexion->query($query) or die("échec de la requête liste des prêts en cours");
					// affichage de la liste des prêts en cours
					echo '<table class="blueTable" style="text-align:center">';
					echo "<TR><th>ID du film</th><th>Nom du film</th><th>Pris le </th>
					<th>Attendu le</th><th>? Retard ?</th>";
					while ($data = mysqli_fetch_array($result)) 
						{
						// calcul du retard
						$datedue=str_replace("-","",$data['date_due']);
						$datejour=date("Ymd");
						$retard=($datejour>$datedue)?($datejour-$datedue):(0);
						// on affiche les résultats
						echo '<tr><td>'.$data['IDfilm'].'</td><td>'.$data['Titre'].'</td>';
						echo '<td>'.$data['date_pris'].'</td>';
						echo "<td>".$data['date_due']."</td>";
						echo "<td>".$retard." Jours</td></tr>";
						}
					echo "</table>";
				}
			$connexion->close();
		}
		else // il n'a pas les privilèges admin
		{
			echo '<H2 class="danger"> Opération Interdite !</h2>';
			echo "Vous devez être inscrit pour pouvoir gérer vos emprunts";
		}
                
    
?>
</div>  <!-- end .content -->
<div class="footer">
  <?php require "includes/footer.ssi"; ?>  
 </div>
</div>  <!-- end .container -->
</body>
</html>
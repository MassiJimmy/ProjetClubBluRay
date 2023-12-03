<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>BluRayClub Gestion des Retours</title>
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
   <h1>Gestion des Retours de Prêts </h1>
	<?php
	//il verifie qu'il soit connecté et administrateur
	if(isset($_SESSION['Authenticated']) && $_SESSION['Authenticated']['mode']=="administrateur" )   //On regarde si c'est l'administrateur qui se connecte
		{ 
			require "includes/connectbdd.php";
			$today=date("Y-m-d");
			if(!empty($_GET['IDuser']) && !empty($_GET['IDfilm']) &&!empty($_GET['date_pris']))
				{	//On fait la requete qui fera en sorte que la personne a rendu le film
					$query="UPDATE emprunter SET date_rendu = '".$today."'
					WHERE IDemprunteur = '".$_GET['IDuser']."' 
					AND IDfilm = '".$_GET['IDfilm']."'
					AND date_pris = '".$_GET['date_pris']."'
					;";
					$result=$connexion->query($query) or die("echec de la requête maj date retour");
					header("location:gestretours.php");
				}
			else 
				{	//On fait la requete qui prend les membres qui ont emprunter un film
					$query='SELECT emprunter.*, t_membres.pseudo 
					FROM emprunter INNER JOIN t_membres ON IDemprunteur=IDuser
					WHERE date_rendu IS NULL 
					ORDER BY date_due ASC;';
					$result=$connexion->query($query) or die("échec de la requête liste des prês en cours");
					// On affiche ici les liste de prets
					echo '<table class="blueTable" style="text-align:center">';
					echo "<TR><th>Membre</th><th>Film</th><th>Pris le </th>
					<th>Attendu le</th><th>? Retard ?</th><th>Retours du ".$today." </th>";
					while ($data = mysqli_fetch_array($result)) 
						{
						// calcul du retard
						$datedue=str_replace("-","",$data['date_due']);
						$datejour=date("Ymd");
						$retard=($datejour>$datedue)?($datejour-$datedue):(0);
						// on affiche les résultats
						echo '<tr><td>'.$data['pseudo'].'</td>';
						echo '<td>'.$data['IDfilm'].'</td><td>'.$data['date_pris'].'</td>';
						echo "<td>".$data['date_due']."</td>";
						echo "<td>".$retard." Jours</td>";
						echo '<td><a href="gestretours.php?
						IDuser='.$data['IDemprunteur'].'&IDfilm='.$data['IDfilm'].'&date_pris='.$data['date_pris'].'"> Rendre Maintenant </a>
						</td></tr>';
						}
					echo "</table>";
				}
			$connexion->close();
		}
		else // Si il est pas admin
		{
			echo '<H2 class="danger"> Opération Interdite !</h2>';
			echo "Vous devez être administrateur pour pouvoir gérer les retours";
		}

	?>
  </div>  <!-- end .content -->
  <div class="footer">
    <?php require "includes/footer.ssi"; ?>  
   </div>
</div>  <!-- end .container -->
</body>
</html>
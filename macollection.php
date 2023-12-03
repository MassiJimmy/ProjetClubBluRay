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
   <h1>Gestion de ma collection de BluRay </h1>
	<?php
	if(isset($_SESSION['Authenticated']) )
		{ // l'utilisateur est cconnecté 
			$IDconnectedUser=$_SESSION['Authenticated']['monID'];
			$today=date("Y-m-d");
			// tester parametres reçus du formulaire
			if(!empty($_POST))
				{	// appel par le formulaire => On met à jour la BDD
					// test afficher le $post print_r($_POST);
					// traitement des données de formulaire
					$IDfilm=$_POST['FilmID'];
					$pret=isset($_POST['preter'])?1:0;
					$echanger=isset($_POST['echanger'])?1:0;
					if(!empty($_POST['prix'])) 
						{ $vendre = $_POST['prix'];} 
					else
						{ $vendre = "NULL";}
					// echo $vendre; 
					echo '<br>';
					// construction de la requête de mise à jour
					$sqlstr='UPDATE offrir SET 
					preter="'.$pret.'", 
					echanger="'.$echanger.'",
					vendre='.$vendre.'			
					WHERE 
					IDproprio="'.$IDconnectedUser.'"      
					AND IDfilm="'.$_POST['FilmID'].'"'				;
					// affichage de la requête
					// echo $sqlstr;
					require "includes/connectbdd.php";
					$result=$connexion->query($sqlstr) or die("échec de la requête de mise à jour");
					// print_r($result);
					$connexion->close();
					// rappeler la page sans données pour afficher le tableau mis à jour
					header("location: ".$server["HTTP_REFERER"]);
				}
			else 
				{	
					// appel sans paramètre  ==> On affiche la liste avec formulaire par ligne
					$query='select offrir.IDfilm, t_films.Titre, preter, echanger, vendre
					FROM offrir INNER JOIN t_films ON t_films.ID_film = offrir.IDfilm
					WHERE offrir.IDproprio = '.$IDconnectedUser.';';
					// echo $IDconnectedUser."<br>";
					// echo $query;
					require "includes/connectbdd.php";
					$result=$connexion->query($query) or die("échec de la requête collection des blurays");
					// affichage de la liste des films mis à disposition du club
					echo '<table class="blueTable" style="text-align:center">';
					echo "<TR><th>FilmID</th><th>Titre</th><th>Preter</th>
					<th>Echanger</th><th>Vendre</th><th>Modifier</th></TR>";
					while ($data = mysqli_fetch_array($result)) 
						{
						// on affiche les résultats dans des lignes de formulaire
						echo '<form action="macollection.php" method="post" ';
						echo '<tr>';
						// un td visible muni d'un champ caché pour transmetre ID_film sans pouvoir le modifier
						echo '<td>'.$data['IDfilm'].' <input type="hidden" name="FilmID" value="'.$data['IDfilm'].'" </td>';						
						echo '<td>'.$data['Titre'].'</td>';
						if($data['preter']==1)
							{	echo '<td><input type="checkbox" name="preter" checked ></td>';}
						else
							{	echo '<td><input type="checkbox" name="preter" unchecked ></td>';}
						if($data['echanger']==1)
							{	echo '<td><input type="checkbox" name="echanger" checked ></td>';}
						else
							{	echo '<td><input type="checkbox" name="echanger" unchecked ></td>';}						
						echo '<td><input type="number" step="0.01" min="0" max="99.99" name="prix" value="'.$data['vendre'].'"></td>';
						echo '<td> <input type="submit" value="Mettre à Jour"></td>';						
						echo '</tr>';
						echo '</form>';
						}						
					echo "</table>";
				$connexion->close();
			}
		}
	else echo ' <h2 class="danger">  Il faut être identifié pour utiliser cette page !</h2>';
	?>
  </div>  <!-- end .content -->
  <div class="footer">
    <?php require "includes/footer.ssi"; ?>  
   </div>
</div>  <!-- end .container -->
</body>
</html>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Recherche par nom</title>
<link href="css/bluraycss.css" rel="stylesheet" type="text/css">
</head>

<body>
<!-- Version améliorée de Exo4.php : un second formulaire permet d'entrer des critères de sélection le résultat est multilignes -->
<div class="container">
  <div class="header">  	
  	<?php require "includes/entete.php"; //ajoute le menu d'authentification ?> 
  </div> <!-- end of header -->
  <div class="sidebar1">
  <?php require "includes/sidebar1.ssi"; ?>   
  </div>
  <div class="content">
    
<?php
// On vérifie si la page a été appelée sans Paramètre
if ($_SERVER['QUERY_STRING']=='')
	{ // il faut afficher le formulaire pour obtenir l'ID du film ou des critères de sélection
	echo '<form action="Findbyname.php" method="get">';
	echo '<label>Saisissez le titre ou le debut du titre du film ci-après ...</label>';
	echo '<input name="nom" type="text" value="" size="6" maxlength="50"><br>';
	echo '<label>Saisissez le nombre de films maximum à afficher ...</label>';
	echo '<input name="limit" type="text" value="10" size="3" maxlength="3"><br>';
	echo '<input name="valider" type="submit" value="Voir les fiches de ces film">';
	echo '</form>';
		//  le formulaire rappelle la page en lui envoyant les parametres ID et Valider
	}
else		
	{
		$filmnom = $_SERVER['QUERY_STRING'];  // on stocke le parametre dans une variable    
		// verifier que le parametre est correct, protéger de l'injection SQL
		if (substr($filmnom,0,4) !== "nom=")
			{
				echo '<H2>'.'   '.$filmnom.'   '.' Parametres transmis Invalides !';	
			}
		else
			{
				// on traite la page avec un numéro de film valide.
				$filmnom = $_GET['nom'];
				$limlist = $_GET['limit'];  
				// On upper le titre pour faciliter la recherche également mise en majuscule sur la requête
                $FilmMaj = strtoupper($filmnom);
				require "includes/connectbdd.php";
				//on teste la recherche sur la requête ici LIKE $filmMaj % nous permet de trouver un film même si on utilise que le debut du terme sur le titre
				$query="SELECT ID_film, Titre, Réalisateur, Année, Miniature FROM t_films WHERE UPPER(Titre) LIKE '".$FilmMaj."%'  limit " .$limlist.";";
				// echo $query;
				$result=$connexion->query($query) or die('Echec de la requête, <a href="Findbyname.php"> Refaire un essai </a>');
		
					if ($result->num_rows > 0)
					{
						echo "<H1>Liste des ".$result->num_rows." films trouvés par la racherche sur '".$filmnom."'.</H1>";
						echo '<table class="blueTable"><TR><TH>ID</TH><TH>Titre</TH><TH>Réalisateur</TH><TH>Année</TH><TH>Miniature</TH></TR>';
						while ($fiche = $result->fetch_array())
							{
							// préparation pour insertion de la miniature
							$urlmini = str_replace('\\', '/', $fiche['Miniature']);
							//on affiche tout les films recherché sur le nom
							echo '<TR><TD><a href=fichefilm.php?ID='.$fiche['ID_film'].'> '.$fiche['ID_film'].'</TD>
							<TD>'.$fiche['Titre'].'</TD>
							<TD>'.$fiche['Réalisateur'].'</TD>
							<TD>'.$fiche['Année'].'</TD>
							<TD><img src="'.$urlmini.'" height="80px"></TD></TR>';
							}
							echo "</table><BR>";
					}
					else
					{
						echo 'aucun résultat, <a href="Findbyname.php" > Choisissez </a> un nouveau nom de film ...';
					}
		$connexion->close();
			}
			// fin du traitement de la page appelée avec paramètre
	}
?>
    <!-- end .content --></div>
    <div class="footer">
    <?php require "includes/footer.ssi"; ?>  
    </div>
  <!-- end .container --></div>
</body>
</html>
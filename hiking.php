<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new ThikingManager($db);
?>
<!DOCTYPE HTML>
<!--
	Massively by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Sport Tracker</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="icon" href="images/favicon.ico" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<a href="index.php" class="logo">Home</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li ><a href="index.php">Home</a></li>
							<li ><a href="Running.php">Running</a></li>
							<li ><a href="Cycling.php">Cycling</a></li>
							<li class="active"><a href="Hiking.php">Hiking</a></li>
							<li><a href="Swimming.php">Swimming</a></li>
							<li><a href="stats.php">Stats</a></li>
							<li><a href="elements.html">Elements</a></li>
						</ul>

						<!-- Liens vers les Réseaux Sociaux -->
						<?php include("icone_reseaux.php"); ?>
						
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Entête -->
								<header class="major">
									<h1>HIKING</h1>
									<div class="image main"><img src="images/baniere_hiking.jpg" alt="" /></div>
								

						<?php

					
					/* Si un track a été supprimé */ 
					if (isset($_GET['supprimer']))
					{
					  $manager->delete((int) $_GET['supprimer']);
					  $message = 'Le track a bien été supprimée !';
					}

					/* demande d'ajout de track  */
					if ( (isset($_GET['ajouter'])) && ($_GET['ajouter'] == -1))
					{
					  echo '</header>';
					  echo '<form method="post" action="hiking.php?ajouter=1">'; ?>
								<div class="fields">
									<div class="field">
										<label for="date_course">Date</label>
										<input type="date" name="date_course" />
									</div>
									<div class="field">
										<label for="id_circuit">Circuit</label>
										<input type="number" name ="id_circuit" min ="0" step="1"/>
									</div>
									<div class="field">
										<label for="km">Distance</label>
										<input type="number" name ="km" min ="0" max ="500" step="0.01"/>
									</div>
									<div class="field">
										<label for="temps_course">Temps</label>
										<input type="time" name ="temps_course" min="00:00:00" max="05:00:00" step ="1"/>
									</div>
									<div class="field">
										<label for="dennivele">Dénnivelé (+)</label>
										<input type="number" name ="dennivele" min ="0" max ="10000" step="5"/>
									</div>
									<div class="field">
										<label for="commentaire">Commentaire</label>
										<textarea rows="3" cols="60" name="commentaire"></textarea>
									</div>
								</div>
								<ul class="actions special">
									<li>
										<input type="submit" value="Ajouter" />
									</li>	
								</ul>
							</form>
					
					<?php			  
					} 

					
					
					/* Si un track a été ajouté */ 
					
					if ( isset($_GET['ajouter']) && ($_GET['ajouter'] == 1))
					{
						$tracks = new Thiking(
						    [
						      'date_course' => $_POST['date_course'],
						      'id_circuit' => $_POST['id_circuit'],
						      'km' => $_POST['km'],
						      'temps_course' => $_POST['temps_course'],
						      'dennivele' => $_POST['dennivele'],
						      'commentaire' => $_POST['commentaire']
						    ]
						  );
												
						if ($tracks->isValid())
						  {
						    $tracks->calc_vitesse_moy();
						    $tracks->calc_calories(65);

						    $manager->save_thiking($tracks);
						    
						    $message = $tracks->isNew() ? 'Le track a bien été ajoutée !' : 'Le track a bien été modifiée !';
						    unset($_GET['ajouter']);
						  }
						  else
						  {
						    $erreurs = $tracks->erreurs();
						  }
					} 

					/* Si un track a été modifié */ 
					if (isset($_GET['modifier']) && isset($_GET['id']))
					{
						$tracks = new Thiking(
						    [
						      'date_course' => $_POST['date_course'],
						      'id_circuit' => $_POST['id_circuit'],
						      'km' => $_POST['km'],
						      'temps_course' => $_POST['temps_course'],
						      'dennivele' => $_POST['dennivele'],
						      'commentaire' => $_POST['commentaire']
						    ]
						  );
						$tracks->setId($_GET['id']);
						
						if ($tracks->isValid())
						  {
						    $tracks->calc_vitesse_moy();
						    $tracks->calc_calories(65);

						    $manager->save_thiking($tracks);
						    
						    $message = $tracks->isNew() ? 'Le track a bien été ajoutée !' : 'Le track a bien été modifiée !';
						    unset($_GET['modifier'],$_GET['id'],$_GET['nb']);
						  }
						  else
						  {
						    $erreurs = $tracks->erreurs();
						  }
					}

					 /* Si les détails d'un track sont demandés, on peut le modifier par formulaire  */
						if (isset($_GET['id']))
							{
								$tracks = $manager->getUnique((int) $_GET['id']);
						  
						  echo '<form method="post" action="hiking.php?modifier=', $tracks->id(),'&id=',$_GET['id'],'&nb=',$_GET['nb'],'">'; ?>
								<div class="fields">
									<div class="field">
										<label for="date_course">Date</label>
										<input type="date" name="date_course" 
										value="<?php if (isset($tracks)) echo $tracks->date_course(); ?>" />
									</div>
									<div class="field">
										<label for="id_circuit">Circuit</label>
										<input type="number" name ="id_circuit" min ="0" step="1" 
										value="<?php if (isset($tracks)) echo $tracks->id_circuit(); ?>" />
									</div>
									<div class="field">
										<label for="km">Distance</label>
										<input type="number" name ="km" min ="0" max ="500" step="0.01" 
										value= "<?php if (isset($tracks)) echo $tracks->km(); ?>" />
									</div>
									<div class="field">
										<label for="temps_course">Temps</label>
										<input type="time" name ="temps_course" min="00:00:00" max="05:00:00" step ="1" 
										value="<?php if (isset($tracks)) echo $tracks->temps_course(); ?>" />
									</div>
									<div class="field">
										<label for="dennivele">Dénnivelé (+)</label>
										<input type="number" name ="dennivele" min ="0" max ="10000" step="5" 
										value= "<?php if (isset($tracks)) echo $tracks->dennivele(); ?>" />
									</div>
									<div class="field">
										<label for="commentaire">Commentaire</label>
										<textarea rows="3" cols="60" name="commentaire"><?php if (isset($tracks)) echo $tracks->commentaire(); ?></textarea>
									</div>
								</div>
								<ul class="actions special">
									<li>
										<?php
										echo '<a href="?modifier=', $tracks->id(), '">'; ?>
											<input type="submit" value="Modifier" />
										</a>
									</li>
									<li>
										<?php
										echo '<a href="?supprimer=', $tracks->id(),'" class ="button">'; ?>
										Supprimer</a>
									</li>
								</ul>
							</form>
				 <?php
						  	echo '</header>';
						  	echo '<article>';
						  	echo '<h4> Track '.$_GET['nb'].' </h4>';
						  	echo '<span class="date">'. $tracks->date_course_fr().'</span>';
						  	echo '<div class="table-wrapper">
										<table class="alt">
											<tbody>
												<tr>
													<td><h6>Distance</h6></td>
													<td>'.$tracks->km().' km</td>
												</tr>
												<tr>
													<td><h6>Temps</h6></td>
													<td>'.$tracks->temps_course().'</td>
												</tr>
												<tr>
													<td><h6>Vitesse moyenne</h6></td>
													<td>'.$tracks->vitesse_moy().' km/h</td>
												</tr>
												<tr>
													<td><h6>Calories</h6></td>
													<td>'.$tracks->Calories().' cal</td>
												</tr>
												<tr>
													<td><h6>Dénnivelé (+)</h6></td>
													<td>'.$tracks->dennivele().' m</td>
												</tr>
											</tbody>
										</table>
									</div>';
						      
						      echo '</article>';
						      echo '<article>';
						      echo '<h5>Commentaire:</h5><blockquote>'.$tracks->commentaire().'</blockquote><hr />';
						      echo '</article>';
						}

						else if(!isset($_GET['ajouter']))
						  /*  AFFICHAGE DE TOUS LES TRACKS  */
						{
						  /* ---- PAGINATION  ---- */
						  /* indiquer ici le nb de tracks par page */
						  $tracks_page = 3;

						  $nbtrack = $manager->count();
			              if($nbtrack == 0){
			                $nbpage = 1;
			              }
			              else{
			                $nbpage = ceil($nbtrack/$tracks_page);  
			              }
						  if (empty($_GET['page'])){
						  	$page = 1;
						  }
						  else{
						  	$page = $_GET['page'];
						  } 
						  $num = $nbtrack - ($page-1)*$tracks_page;
						  echo '<a href="?ajouter=-1" class ="button">Ajouter un Track</a><br/><br/>'; 
						  echo '<h3>Vos tracks</h3>';
						  echo '</header>'; 
						  echo '<section>';
						  foreach ($manager->getList($tracks_page*($page-1),$tracks_page) as $tracks)
						  {	
						  	echo '<article>';
						  	echo '<header><h2><a href="?id=', $tracks->id(),'&nb=',$num,'">Track '.$num.' </a></h2></header>';
						  	echo '<span class="date">'. $tracks->date_course_fr().'</span>';
						  	
						  	echo '<div class="table-wrapper">
										<table class="alt">
											<tbody>
												<tr>
													<td><h6>Distance</h6></td>
													<td>'.$tracks->km().' km</td>
												</tr>
												<tr>
													<td><h6>Temps</h6></td>
													<td>'.$tracks->temps_course().'</td>
												</tr>
												<tr>
													<td><h6>Vitesse moyenne</h6></td>
													<td>'.$tracks->vitesse_moy().' km/h</td>
												</tr>
												<tr>
													<td><h6>Dénnivelé (+)</h6></td>
													<td>'.$tracks->dennivele().' m</td>
												</tr>
												<tr>
													<td><h6>Calories</h6></td>
													<td>'.$tracks->Calories().'cal</td>
												</tr>
											</tbody>
										</table> 
									</div>'; 
						      
						      echo '</article>';
						      echo '<article>';
						      echo '<h5>Commentaire:</h5><blockquote>'.$tracks->commentaire_red(100).'</blockquote><hr />'; 
						     
						      echo '</article>';
						      $num -= 1;
						  }
						  echo '</section>';
						
								
					?>
					<!-- Footer -->
							<footer>
								<div class="pagination">
									<?php 
									for ($i=1;$i<=$nbpage;$i++){ 
										if ($i == $page){
											echo '<a href="?page='.$i.'" class="page active">'.$i.'</a>';
										}
										else{
											echo '<a href="?page='.$i.'" class="page">'.$i.'</a>';
										}
									} 
									$next = $page + 1;
									echo '<a href="?page='.$next.'"class="next">Next</a>';
									?>
									
								</div>
							</footer>
							 

					<?php } ?>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<section>
							<form method="post" action="#">
								<div class="fields">
									<div class="field">
										<label for="name">Name</label>
										<input type="text" name="name" id="name" />
									</div>
									<div class="field">
										<label for="email">Email</label>
										<input type="text" name="email" id="email" />
									</div>
									<div class="field">
										<label for="message">Message</label>
										<textarea name="message" id="message" rows="3"></textarea>
									</div>
								</div>
								<ul class="actions">
									<li><input type="submit" value="Send Message" /></li>
								</ul>
							</form>
						</section>
						<section class="split contact">
							<section class="alt">
								<h3>Address</h3>
								<p>1234 Somewhere Road #87257<br />
								Nashville, TN 00000-0000</p>
							</section>
							<section>
								<h3>Phone</h3>
								<p><a href="#">(000) 000-0000</a></p>
							</section>
							<section>
								<h3>Email</h3>
								<p><a href="#">info@untitled.tld</a></p>
							</section>
							<section>
								<h3>Social</h3>
								<ul class="icons alt">
									<li><a href="#" class="icon brands alt fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon brands alt fa-facebook-f"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon brands alt fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon brands alt fa-github"><span class="label">GitHub</span></a></li>
								</ul>
							</section>
						</section>
					</footer>

				<!-- Copyright -->
					<div id="copyright">
						<ul><li>&copy; Untitled</li><li>Design: <a href="https://html5up.net">HTML5 UP</a></li></ul>
					</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
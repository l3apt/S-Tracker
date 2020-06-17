<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new TcyclingManager($db);
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
							<li class="active"><a href="Cycling.php">Cycling</a></li>
							<li><a href="Hiking.php">Hiking</a></li>
							<li><a href="Swimming.php">Swimming</a></li>
							<li><a href="stats.php">Stats</a></li>
							<li><a href="elements.html">Elements</a></li>
						</ul>
						<ul class="icons">
							<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Entête -->
								<header class="major">
									<h1>CYCLING</h1>
									<div class="image main"><img src="images/baniere_cycling.jpg" alt="" /></div>
								

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
					  echo '<form method="post" action="cycling.php?ajouter=1">'; ?>
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
										<label for="vitesse_max">Vitesse max</label>
										<input type="number" name ="vitesse_max" min ="0" max ="100" step="0.01"/>
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
						$tcycling = new Tcycling(
						    [
						      'date_course' => $_POST['date_course'],
						      'id_circuit' => $_POST['id_circuit'],
						      'km' => $_POST['km'],
						      'temps_course' => $_POST['temps_course'],
						      'vitesse_max' => $_POST['vitesse_max'],
						      'dennivele' => $_POST['dennivele'],
						      'commentaire' => $_POST['commentaire']
						    ]
						  );
												
						if ($tcycling->isValid())
						  {
						    $tcycling->calc_vitesse_moy();
						    $tcycling->calc_calories(65);

						    $manager->save_tcycling($tcycling);
						    
						    $message = $tcycling->isNew() ? 'Le track a bien été ajoutée !' : 'Le track a bien été modifiée !';
						    unset($_GET['ajouter']);
						  }
						  else
						  {
						    $erreurs = $tcycling->erreurs();
						  }
					} 

					/* Si un track a été modifié */ 
					if (isset($_GET['modifier']) && isset($_GET['id']))
					{
						$tcycling = new Tcycling(
						    [
						      'date_course' => $_POST['date_course'],
						      'id_circuit' => $_POST['id_circuit'],
						      'km' => $_POST['km'],
						      'temps_course' => $_POST['temps_course'],
						      'vitesse_max' => $_POST['vitesse_max'],
						      'dennivele' => $_POST['dennivele'],
						      'commentaire' => $_POST['commentaire']
						    ]
						  );
						$tcycling->setId($_GET['id']);
						
						if ($tcycling->isValid())
						  {
						    $tcycling->calc_vitesse_moy();
						    $tcycling->calc_calories(65);

						    $manager->save_tcycling($tcycling);
						    
						    $message = $tcycling->isNew() ? 'Le track a bien été ajoutée !' : 'Le track a bien été modifiée !';
						    unset($_GET['modifier'],$_GET['id'],$_GET['nb']);
						  }
						  else
						  {
						    $erreurs = $tcycling->erreurs();
						  }
					}

					 /* Si les détails d'un track sont demandés, on peut le modifier par formulaire  */
						if (isset($_GET['id']))
							{
								$tcycling = $manager->getUnique((int) $_GET['id']);
						  
						  echo '<form method="post" action="cycling.php?modifier=', $tcycling->id(),'&id=',$_GET['id'],'&nb=',$_GET['nb'],'">'; ?>
								<div class="fields">
									<div class="field">
										<label for="date_course">Date</label>
										<input type="date" name="date_course" 
										value="<?php if (isset($tcycling)) echo $tcycling->date_course(); ?>" />
									</div>
									<div class="field">
										<label for="id_circuit">Circuit</label>
										<input type="number" name ="id_circuit" min ="0" step="1" 
										value="<?php if (isset($tcycling)) echo $tcycling->id_circuit(); ?>" />
									</div>
									<div class="field">
										<label for="km">Distance</label>
										<input type="number" name ="km" min ="0" max ="500" step="0.01" 
										value= "<?php if (isset($tcycling)) echo $tcycling->km(); ?>" />
									</div>
									<div class="field">
										<label for="temps_course">Temps</label>
										<input type="time" name ="temps_course" min="00:00:00" max="05:00:00" step ="1" 
										value="<?php if (isset($tcycling)) echo $tcycling->temps_course(); ?>" />
									</div>
									<div class="field">
										<label for="vitesse_max">Vitesse max</label>
										<input type="number" name ="vitesse_max" min ="0" max ="100" step="0.01" 
										value= "<?php if (isset($tcycling)) echo $tcycling->vitesse_max(); ?>" />
									</div>
									<div class="field">
										<label for="dennivele">Dénnivelé (+)</label>
										<input type="number" name ="dennivele" min ="0" max ="10000" step="5" 
										value= "<?php if (isset($tcycling)) echo $tcycling->dennivele(); ?>" />
									</div>
									<div class="field">
										<label for="commentaire">Commentaire</label>
										<textarea rows="3" cols="60" name="commentaire"><?php if (isset($tcycling)) echo $tcycling->commentaire(); ?></textarea>
									</div>
								</div>
								<ul class="actions special">
									<li>
										<?php
										echo '<a href="?modifier=', $tcycling->id(), '">'; ?>
											<input type="submit" value="Modifier" />
										</a>
									</li>
									<li>
										<?php
										echo '<a href="?supprimer=', $tcycling->id(),'" class ="button">'; ?>
										Supprimer</a>
									</li>
								</ul>
							</form>
				 <?php
						  	echo '</header>';
						  	echo '<article>';
						  	echo '<h4> Track '.$_GET['nb'].' </h4>';
						  	echo '<span class="date">'. $tcycling->date_course_fr().'</span>';
						  	echo '<div class="table-wrapper">
										<table class="alt">
											<tbody>
												<tr>
													<td><h6>Distance</h6></td>
													<td>'.$tcycling->km().' km</td>
												</tr>
												<tr>
													<td><h6>Temps</h6></td>
													<td>'.$tcycling->temps_course().'</td>
												</tr>
												<tr>
													<td><h6>Vitesse moyenne</h6></td>
													<td>'.$tcycling->vitesse_moy().' km/h</td>
												</tr>
												<tr>
													<td><h6>Vitesse max</h6></td>
													<td>'.$tcycling->vitesse_max().' km/h</td>
												</tr>
												<tr>
													<td><h6>Calories</h6></td>
													<td>'.$tcycling->Calories().' cal</td>
												</tr>
												<tr>
													<td><h6>Dénnivelé (+)</h6></td>
													<td>'.$tcycling->dennivele().' m</td>
												</tr>
											</tbody>
										</table>
									</div>';
						      
						      echo '</article>';
						      echo '<article>';
						      echo '<h5>Commentaire:</h5><blockquote>'.$tcycling->commentaire().'</blockquote><hr />';
						      echo '</article>';
						}

						else if(!isset($_GET['ajouter']))
						  /*  AFFICHAGE DE TOUS LES TRACKS  */
						{
						  /* ---- PAGINATION  ---- */
						  /* indiquer ici le nb de tracks par page */
						  $tracks_page = 3;

						  $nbtrack = $manager->count();
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
						  foreach ($manager->getList($tracks_page*($page-1),$tracks_page) as $tcycling)
						  {	
						  	echo '<article>';
						  	echo '<header><h2><a href="?id=', $tcycling->id(),'&nb=',$num,'">Track '.$num.' </a></h2></header>';
						  	echo '<span class="date">'. $tcycling->date_course_fr().'</span>';
						  	
						  	echo '<div class="table-wrapper">
										<table class="alt">
											<tbody>
												<tr>
													<td><h6>Distance</h6></td>
													<td>'.$tcycling->km().' km</td>
												</tr>
												<tr>
													<td><h6>Temps</h6></td>
													<td>'.$tcycling->temps_course().'</td>
												</tr>
												<tr>
													<td><h6>Vitesse moyenne</h6></td>
													<td>'.$tcycling->vitesse_moy().' km/h</td>
												</tr>
												<tr>
													<td><h6>Vitesse max</h6></td>
													<td>'.$tcycling->vitesse_max().' km/h</td>
												</tr>
												<tr>
													<td><h6>Dénnivelé (+)</h6></td>
													<td>'.$tcycling->dennivele().' m</td>
												</tr>
												<tr>
													<td><h6>Calories</h6></td>
													<td>'.$tcycling->Calories().'cal</td>
												</tr>
											</tbody>
										</table> 
									</div>'; 
						      
						      echo '</article>';
						      echo '<article>';
						      echo '<h5>Commentaire:</h5><blockquote>'.$tcycling->commentaire_red(100).'</blockquote><hr />'; 
						      $num -= 1;
						     
						      echo '</article>';
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
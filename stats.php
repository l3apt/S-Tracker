<?php 
		require 'lib/autoload.php';
		$bdd = DBFactory::getMysqlConnexionWithPDO();
		include("fonctions_stats.php"); 

		/* calcul nb km + vitesse moyenne des course dans l'année*/

			$req_annee = $bdd->query('SELECT sum(km) as tot_km, 
											 avg(vitesse_moy)as vitesse,
											 TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC( `temps_course` ) ) ), \'%Hh%imn\') as tot_tempsCourse,
											 TIME_FORMAT(max(temps_course),\'%Hh%imn\') as max_tempsCourse,
											 max(km) as max_km, 
											 max(vitesse_moy) as max_vitesseMoyenne  
									   FROM `tracks` WHERE year(date_course)= year(now())');

			/* calcul nb km + vitesse moyenne des course dans le mois*/

			$req_mois = $bdd->query('SELECT sum(km) as tot_km,
											avg(vitesse_moy)as vitesse,
											TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC( `temps_course` ) ) ), \'%Hh%imn\') as tot_tempsCourse,
											TIME_FORMAT(max(temps_course),\'%Hh%imn\') as max_tempsCourse,
											max(km) as max_km, 
											max(vitesse_moy) as max_vitesseMoyenne  
										FROM `tracks` WHERE month(date_course)= month(now())');
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
							<li><a href="index.php">Home</a></li>
							<li><a href="Running.php">Running</a></li>
							<li><a href="Cycling.php">Cycling</a></li>
							<li><a href="Hiking.php">Hiking</a></li>
							<li><a href="Swimming.php">Swimming</a></li>
							<li class="active"><a href="stats.php">Stats</a></li>
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
						<h1>Running</h1>
						<h2>Mois</h2>
						<?php 
							$donnees_mois = $req_mois->fetch();
						?>

						<table>
							<thead>
								<tr>
									<th></th>
									<th>Total</th>
									<th>Max</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Distance</td>
									<td><?php echo $donnees_mois['tot_km']; ?> km</td>
									<td><?php echo $donnees_mois['max_km']; ?> km</td>
								</tr>
									<tr>
									<td>Vitesse moyenne</td>
									<td><?php echo number_format($donnees_mois['vitesse'],2); ?> km/h</td>
									<td><?php echo $donnees_mois['max_vitesseMoyenne']; ?> km/h</td>
								</tr>
								<tr>
									<td>Temps</td>
									<td><?php echo $donnees_mois['tot_tempsCourse']; ?></td>
									<td><?php echo $donnees_mois['max_tempsCourse']; ?></td>
								</tr>
						</table>	

						<h2>Année</h2>
						<?php 
							$donnees_annee = $req_annee->fetch();
						?>

							<table>
											<thead>
												<tr>
													<th></th>
													<th>Total</th>
													<th>Max</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Distance</td>
													<td><?php echo $donnees_annee['tot_km']; ?> km</td>
													<td><?php echo $donnees_annee['max_km']; ?> km</td>
												</tr>
												<tr>
													<td>Vitesse moyenne</td>
													<td><?php echo number_format($donnees_annee['vitesse'],2); ?> km/h</td>
													<td><?php echo $donnees_annee['max_vitesseMoyenne']; ?> km/h</td>
												</tr>
												<tr>
													<td>Temps</td>
													<td><?php echo $donnees_annee['tot_tempsCourse']; ?></td>
													<td><?php echo $donnees_annee['max_tempsCourse']; ?></td>
												</tr>
										</table>			

						<?php objectif($donnees_annee['tot_km'],'500'); ?>	

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
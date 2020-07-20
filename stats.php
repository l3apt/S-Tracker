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
						<h1>Baptiste, 25 ans</h1>
						<h2>STATS</h2>
						<?php 
							$donnees_mois = $req_mois->fetch();

							//données du mois en cours
							$temps_mois = calc_temps($bdd,"all","mois");
							$nbAct_mois = calc_nbActivites($bdd,"all","mois");
							$cal_mois = calc_calories($bdd,"all","mois");
							$denniv_mois = calc_denniv($bdd,"all","mois");

							// données de l'année en cours
							$temps_annee = calc_temps($bdd,"all","annee");
							$nbAct_annee = calc_nbActivites($bdd,"all","annee");
							$cal_annee = calc_calories($bdd,"all","annee");
							$denniv_annee = calc_denniv($bdd,"all","annee");

							// données totale
							$temps_tot = calc_temps($bdd,"all","all");
							$nbAct_tot = calc_nbActivites($bdd,"all","all");
							$cal_tot = calc_calories($bdd,"all","all");
							$denniv_tot = calc_denniv($bdd,"all","all");

							// temps par sport
							$tempsParSport[0] = calc_temps($bdd,"tracks","all");
							$tempsParSport[1] = calc_temps($bdd,"cycling","all");
							$tempsParSport[2] = calc_temps($bdd,"hiking","all");
							$tempsParSport[3] = calc_temps($bdd,"swimming","all");

						?>

						<table>
							<thead>
								<tr>
									<th></th>
									<th>Temps</th>
									<th>Nb d'activitées</th>
									<th>Calories</th>
									<th>D+</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Mois</td>
									<td><?php echo $temps_mois; ?> heures ce mois </td>
									<td><?php echo $nbAct_mois; ?> activitées ce mois</td>
									<td><?php echo $cal_mois; ?> calories brûlées ce mois</td>
									<td><?php echo $denniv_mois; ?> m positifs ce mois</td>
								</tr>
								<tr>
									<td>Année</td>
									<td><?php echo $temps_annee; ?> heures cette année </td>
									<td><?php echo $nbAct_annee; ?> activitées cette année</td>
									<td><?php echo $cal_annee; ?> calories brûlées cette année</td>
									<td><?php echo $denniv_annee; ?> m positifs cette année</td>
								</tr>
								<tr>
									<td>Total</td>
									<td><?php echo $temps_tot; ?> heures au total </td>
									<td><?php echo $nbAct_tot; ?> activitées au total</td>
									<td><?php echo $cal_tot; ?> calories brûlées au total</td>
									<td><?php echo $denniv_tot; ?> m positifs au total</td>
								</tr>
						</table>

						<!--TEST graphiques chart.js --> 

						<canvas id="myChart"></canvas>
						<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

						<script>
							var ctx = document.getElementById('myChart').getContext('2d');
							var chart = new Chart(ctx, {
							    // The type of chart we want to create
							    type: 'pie',

							    // The data for our dataset
							    data: {
							        labels: ['Running', 'Cycling', 'Hiking', 'Swimming'],
							        datasets: [{
							            label: 'My First dataset',
							            backgroundColor: ['rgb(255, 99, 132)','rgb(227, 255, 51)','rgb(51, 255, 85)','rgb(79, 51, 255)'],
							            borderColor: 'rgb(173, 173, 173)',
							            data: [<?php echo $tempsParSport[0]; ?>,
							            	 	<?php echo $tempsParSport[1]; ?>,
							            	 	<?php echo $tempsParSport[2]; ?>,
							            	 	<?php echo $tempsParSport[3]; ?>] 
							        }]
							    },

							    // Configuration options go here
							    options: {}
							});

						</script>


	

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
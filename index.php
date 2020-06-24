<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new TracksManager($db);
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
			<div id="wrapper" class="fade-in">

				<!-- Intro -->
					<div id="intro">
						<h1>Sport Tracker</h1>
						<p>Bienvenue</p>
						<ul class="actions">
							<li><a href="#header" class="button icon solid solo fa-arrow-down scrolly">Continue</a></li>
						</ul>
					</div>

				<!-- Header -->
					<header id="header">
						<a href="index.php" class="logo">Home</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li class="active"><a href="index.php">Home</a></li>
							<li><a href="Running.php">Running</a></li>
							<li><a href="Cycling.php">Cycling</a></li>
							<li><a href="Hiking.php">Hiking</a></li>
							<li><a href="Swimming.php">Swimming</a></li>
							<li><a href="stats.php">Stats</a></li>
							<li><a href="elements.html">Elements</a></li>
						</ul>
						
						<!-- Liens vers les Réseaux Sociaux -->
						<?php include("icone_reseaux.php"); ?>
						
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Featured Post 
							<article class="post featured">
								<header class="major">
									<span class="date"><?php echo date("d-m-Y"); ?></span>
									<h2>Sport Tracker</h2>
									<p>Votre profil Sport Tracker permet de suivre au mieux vos entraînements dans vos disciplines préférées</p>
								</header>
								<a href="#" class="image main"><img src="images/pic01.jpg" alt="" /></a>
								<ul class="actions special">
									<li><a href="#" class="button large">Full Story</a></li>
								</ul>
							</article> -->

						<!-- Posts -->
							<section class="posts">
								<article>
									<header>
										<h2><a href="Running.php">Running</a></h2>
									</header>
									<a href="Running.php" class="image fit"><img src="images/running.jpg" alt="" /></a>
									<ul class="actions special">
										<li><a href="Running.php" class="button">GO</a></li>
									</ul>
								</article>
								<article>
									<header>
										<h2><a href="Cycling.php">Cycling</a></h2>
									</header>
									<a href="Cycling.php" class="image fit"><img src="images/cycling.jpg" alt="" /></a>
									<ul class="actions special">
										<li><a href="Cycling.php" class="button">GO</a></li>
									</ul>
								</article>
								<article>
									<header>
										<h2><a href="Hiking.php">Hiking</a></h2>
									</header>
									<a href="Hiking.php" class="image fit"><img src="images/Hiking.jpg" alt="" /></a>
									<ul class="actions special">
										<li><a href="Hiking.php" class="button">GO</a></li>
									</ul>
								</article>
								<article>
									<header>
										<h2><a href="Swimming.php">Swimming</a></h2>
									</header>
									<a href="Swimming.php" class="image fit"><img src="images/Swimming.jpg" alt="" /></a>
									<ul class="actions special">
										<li><a href="Swimming.php" class="button">GO</a></li>
									</ul>
								</article>
							</section>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<section>
							<form method="post" action="#">
								<div class="fields">
									<div class="field">
										<label for="name">Name</label>
										<input type="text" name="name" id="name" placeholder ="Georges" />
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
<?php 

/* comparaison de la distance courrue avec l'objectif fixé ET comparaison avec distances caractéristiques*/
		function objectif($distance_realisee,$objectif_distance)
		{
			$pourcentage = ($distance_realisee/$objectif_distance)*100;
			/* distance caractéristique*/
			$distance_car= array('42','250','104','312','530','1223');
			$nom_car=array('d\'un Marathon','entre Lyon et Grenoble','entre Besac et Lyon','entre Lyon et Marseille','entre Lyon et Paris',' entre Brest et Nice');
			
			
			/* vérification objectif*/
			echo "<br/><strong>Votre Objectif</strong>: ".$objectif_distance."km<br/>";

		    if ($pourcentage < 100 && $pourcentage > 75){
		    	echo "Allez on ne se relache pas, objectif bientôt atteint!<br/>";
		    	echo "<strong>Avancement objectif :</strong>".number_format($pourcentage,2)."%<br/>";
		    }
		    if ($pourcentage < 50){
		    	echo "La route est encore longue, continuez!<br/>";
		    	echo "<strong>Avancement objectif :</strong>".number_format($pourcentage,2)."%<br/>";
		    }
		    if ($pourcentage >= 100){
		    	echo "Objectif atteint, bravo! <br/>Quel sera votre prochain objectif ?<br/>";
		    	echo "<strong>Avancement objectif :</strong>".number_format($pourcentage,2)."%<br/>";
		    }
		    


		    /* équivalence distance*/
			for ($i=0; $i<count($distance_car); $i++)
			{
				if ($distance_realisee>$distance_car[$i]){
					echo '<strong>Bravo vous avez déjà réalisé l\'équivalent de la distance '.$nom_car[$i].', soit '.$distance_car[$i].'km. </strong>';
				}
			}

		}

		/* calcul du temps réalisées en fonction du sport (all: tous sports) et de la durée ("mois" ou "annee", "all": de tous temps) */

		function calc_temps($bdd,$sport, $duree){
			
			$heure_sport_tot = 0;
			$sports = ["tracks", "cycling", "hiking", "swimming"];

			// tous les sports
			if ($sport == "all"){
				for ($i=0;$i<count($sports);$i++){
					//gestion de la requete par rapport à la durée demandée
					if ($duree == "mois")
						$sql = 'SELECT SUM( TIME_TO_SEC( `temps_course` ) ) FROM '.$sports[$i].' WHERE month(date_course)= month(now())';
					else if ($duree == "annee")
						$sql = 'SELECT SUM( TIME_TO_SEC( `temps_course` ) ) FROM '.$sports[$i].' WHERE year(date_course)= year(now())';
					else if ($duree == "all")
						$sql = 'SELECT SUM( TIME_TO_SEC( `temps_course` ) ) FROM '.$sports[$i].'  ';
					else
						return "-1";
					$requete = $bdd->prepare($sql);
					$requete->execute();

    				$heure_sport = $requete->fetch();

    				$heure_sport_tot += $heure_sport[0];
				}
				$heure = $heure_sport_tot/3600;

			}

			// un sport spécifique
			else{
					//gestion de la requete par rapport à la durée demandée
					if ($duree == "mois")
						$sql = 'SELECT SUM( TIME_TO_SEC( `temps_course` ) ) FROM '.$sport.' WHERE month(date_course)= month(now())';
					else if ($duree == "annee")
						$sql = 'SELECT SUM( TIME_TO_SEC( `temps_course` ) ) FROM '.$sport.' WHERE year(date_course)= year(now())';
					else if ($duree == "all")
						$sql = 'SELECT SUM( TIME_TO_SEC( `temps_course` ) ) FROM '.$sport.'  ';
					else
						return "-1";
				$requete = $bdd->prepare($sql);
				$requete->execute();

    			$heure_sport = $requete->fetch();

    			$heure = $heure_sport[0]/3600;

			}

			return round($heure,1);;

		}

		// calcul du nombre d'activités réalisées en fonction du sport (all: tous sports) et de la durée ("mois" ou "annee", "all": de tous temps)

		function calc_nbActivites($bdd, $sport,$duree){

			$nb = 0;
			$sports = ["tracks", "cycling", "hiking", "swimming"];

			
			//tous les sports
			if ($sport == "all"){
				for ($i=0;$i<count($sports);$i++){

					//gestion de la requete par rapport à la durée demandée
					if ($duree == "mois")
						$sql = 'SELECT COUNT(*) FROM '.$sports[$i].' WHERE month(date_course)= month(now())';
					else if ($duree == "annee")
						$sql = 'SELECT COUNT(*) FROM '.$sports[$i].' WHERE year(date_course)= year(now())';
					else if ($duree == "all")
						$sql = 'SELECT COUNT(*) FROM '.$sports[$i].'  ';
					else
						return "-1";

					$requete = $bdd->prepare($sql);
					$requete->execute();

    				$nb_sport = $requete->fetch();

    				$nb += $nb_sport[0];
				}
				return $nb;
			}

			//un sport spécifique
			else{

				//gestion de la requete par rapport à la durée demandée
				if ($duree == "mois")
					$sql = 'SELECT COUNT(*) FROM '.$sport.' WHERE month(date_course)= month(now())';
				else if ($duree == "annee")
					$sql = 'SELECT COUNT(*) FROM '.$sport.' WHERE year(date_course)= year(now())';
				else if ($duree == "all")
					$sql = 'SELECT COUNT(*) FROM '.$sport.'';
				else
					return "-1";

				$requete = $bdd->prepare($sql);
				$requete->execute();

    			$nb_sport = $requete->fetch();

    			return $nb_sport[0];
			}
		}


		// calcul du nombre de calories en fonction du sport (all: tous sports) et de la durée ("mois" ou "annee")

		function calc_calories($bdd, $sport,$duree){

			$cal_tot = 0;
			$sports = ["tracks", "cycling", "hiking", "swimming"];

			
			//tous les sports
			if ($sport == "all"){
				for ($i=0;$i<count($sports);$i++){

					//gestion de la requete par rapport à la durée demandée
					if ($duree == "mois")
						$sql = 'SELECT SUM(calories) FROM '.$sports[$i].' WHERE month(date_course)= month(now())';
					else if ($duree == "annee")
						$sql = 'SELECT SUM(calories) FROM '.$sports[$i].' WHERE year(date_course)= year(now())';
					else if ($duree == "all")
						$sql = 'SELECT SUM(calories) FROM '.$sports[$i].'  ';
					else
						return "-1";

					$requete = $bdd->prepare($sql);
					$requete->execute();

    				$cal = $requete->fetch();

    				$cal_tot += $cal[0];
				}
				return $cal_tot;
			}

			//un sport spécifique
			else{

				//gestion de la requete par rapport à la durée demandée
				if ($duree == "mois")
					$sql = 'SELECT SUM(calories) FROM '.$sport.' WHERE month(date_course)= month(now())';
				else if ($duree == "annee")
					$sql = 'SELECT SUM(calories) FROM '.$sport.' WHERE year(date_course)= year(now())';
				else if ($duree == "all")
					$sql = 'SELECT SUM(calories) FROM '.$sport.'';
				else
					return "-1";

				$requete = $bdd->prepare($sql);
				$requete->execute();

    			$cal = $requete->fetch();

    			return $cal[0];
			}
		}


		// calcul du dénnivelé positif en fonction du sport (all: tous sports) et de la durée ("mois" ou "annee")

		function calc_denniv($bdd, $sport,$duree){

			$denniv_tot = 0;
			$sports = ["cycling", "hiking"];

			
			//tous les sports
			if ($sport == "all"){
				for ($i=0;$i<count($sports);$i++){

					//gestion de la requete par rapport à la durée demandée
					if ($duree == "mois")
						$sql = 'SELECT SUM(dennivele) FROM '.$sports[$i].' WHERE month(date_course)= month(now())';
					else if ($duree == "annee")
						$sql = 'SELECT SUM(dennivele) FROM '.$sports[$i].' WHERE year(date_course)= year(now())';
					else if ($duree == "all")
						$sql = 'SELECT SUM(dennivele) FROM '.$sports[$i].'  ';
					else
						return "-1";

					$requete = $bdd->prepare($sql);
					$requete->execute();

    				$denniv = $requete->fetch();

    				$denniv_tot += $denniv[0];
				}
				return $denniv_tot;
			}

			//un sport spécifique
			else{

				//gestion de la requete par rapport à la durée demandée
				if ($duree == "mois")
					$sql = 'SELECT SUM(dennivele) FROM '.$sport.' WHERE month(date_course)= month(now())';
				else if ($duree == "annee")
					$sql = 'SELECT SUM(dennivele) FROM '.$sport.' WHERE year(date_course)= year(now())';
				else if ($duree == "all")
					$sql = 'SELECT SUM(dennivele) FROM '.$sport.'';
				else
					return "-1";

				$requete = $bdd->prepare($sql);
				$requete->execute();

    			$denniv = $requete->fetch();

    			return $denniv[0];
			}
		}

?>
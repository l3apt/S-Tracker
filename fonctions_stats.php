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

		/* calcul du temps toutes activitées confondues du mois en cours */ 

		function calc_temps_allSport_mois($bdd){
			// requete running
			$req_running = $bdd->query('SELECT SUM( TIME_TO_SEC( `temps_course` ) ) as tot_running											   
									   FROM `tracks` WHERE month(date_course)= month(now())');
			$sec_running = $req_running->fetch();

			// requete cycling

			$req_cycling = $bdd->query('SELECT SUM( TIME_TO_SEC( `temps_course` ) ) as tot_cycling											   
									   FROM `cycling` WHERE month(date_course)= month(now())');
			$sec_cycling = $req_cycling->fetch();

			// requete hiking

			$req_hiking = $bdd->query('SELECT SUM( TIME_TO_SEC( `temps_course` ) ) as tot_hiking											   
									   FROM `hiking` WHERE month(date_course)= month(now())');
			$sec_hiking = $req_hiking->fetch();

			// requete swinmming

			$req_swimming = $bdd->query('SELECT SUM( TIME_TO_SEC( `temps_course` ) ) as tot_swimming											   
									   FROM `swimming` WHERE month(date_course)= month(now())');
			$sec_swimming = $req_swimming->fetch();

			$secondes = $sec_running['tot_running'] + $sec_cycling['tot_cycling'] + $sec_hiking['tot_hiking'] + $sec_swimming['tot_swimming'];

			$heures = $secondes/3600;

			return round($heures,1);
		}

		function calc_activites_mois($bdd, $sport){

			$nb = 0;
			$sports = ["tracks", "cycling", "hiking", "swimming"];

			if ($sport == "all"){
				for ($i=0;$i<count($sports);$i++){
					$sql = 'SELECT COUNT(*) FROM '.$sports[$i].' WHERE month(date_course)= month(now())';
					$requete = $bdd->prepare($sql);
					$requete->execute();

    				$nb_sport = $requete->fetch();

    				$nb += $nb_sport[0];
				}
				return $nb;
			}

			else{

				$sql = 'SELECT COUNT(*) FROM '.$sport.' WHERE month(date_course)= month(now())';
				$requete = $bdd->prepare($sql);
				$requete->execute();

    			$nb_sport = $requete->fetch();

    			return $nb_sport[0];
			}
/*
			$sql = 'SELECT COUNT(*) FROM '.$sport.' WHERE month(date_course)= month(now())';
			$requete = $bdd->prepare($sql);
			$requete->execute();

    		$nb_sport = $requete->fetch();

			// requete running
			$req_running = $bdd->query('SELECT COUNT(*) 											   
									   FROM `tracks` WHERE month(date_course)= month(now())');
			$nb_running = $req_running->fetch();

			// requete cycling

			$req_cycling = $bdd->query('SELECT COUNT(*)											   
									   FROM `cycling` WHERE month(date_course)= month(now())');
			$nb_cycling = $req_cycling->fetch();

			// requete hiking

			$req_hiking = $bdd->query('SELECT COUNT(*)											   
									   FROM `hiking` WHERE month(date_course)= month(now())');
			$nb_hiking = $req_hiking->fetch();

			// requete swinmming

			$req_swimming = $bdd->query('SELECT COUNT(*)											   
									   FROM `swimming` WHERE month(date_course)= month(now())');
			$nb_swimming = $req_swimming->fetch();

			$nb = $nb_running[0] + $nb_cycling[0] + $nb_hiking[0] + $nb_swimming[0] ;

			return $nb_sport[0];
			*/

		}

?>
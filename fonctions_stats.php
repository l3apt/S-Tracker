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

		/* Calcul du nombre d'heures tous sports confondus sur le mois */
		function nbHeures_allSports_mois($bdd){

			$nb_heures_running = $bdd->query('SELECT sum(temps_course) as tot_runnning, 
											 avg(vitesse_moy)as vitesse,
											 TIME_FORMAT(SEC_TO_TIME( SUM( TIME_TO_SEC( `temps_course` ) ) ), \'%Hh%imn\') as tot_tempsCourse,
											 TIME_FORMAT(max(temps_course),\'%Hh%imn\') as max_tempsCourse,
											 max(km) as max_km, 
											 max(vitesse_moy) as max_vitesseMoyenne  
									   FROM `tracks` WHERE month(date_course)= month(now())');

			$nb_heures_running = $bdd->query('SELECT sum( TIME_TO_SEC(temps_course) )  as tot_runnning										 
									   FROM `tracks` WHERE month(date_course) = month(now())');

			$nb_heures_cycling = $bdd->query('SELECT sum(TIME_TO_SEC( temps_course)) as tot_cycling 											 
									   FROM `cycling` WHERE month(date_course)= month(now())');

			$nb_heures_hiking = $bdd->query('SELECT sum(TIME_TO_SEC( temps_course)) as tot_hiking 											 
									   FROM `hiking` WHERE month(date_course)= month(now())');

			$nb_heures_swimming = $bdd->query('SELECT sum(TIME_TO_SEC( temps_course)) as tot_swimming 											 
									   FROM `swimming` WHERE month(date_course)= month(now())');


			$heures_running = $nb_heures_running->fetch();
			$heures_cycling = $nb_heures_cycling->fetch();
			$heures_hiking = $nb_heures_hiking->fetch();
			$heures_swimming = $nb_heures_swimming->fetch();

			$secondes = $heures_running['tot_runnning'] +
						$heures_cycling['tot_cycling'] + 
						$heures_hiking['tot_hiking'] + 
						$heures_swimming['tot_swimming'];
			
			$heures = $secondes/3600;

			return round($heures,1);
		}

?>
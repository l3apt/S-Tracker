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

?>
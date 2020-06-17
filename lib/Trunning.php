<?php
/**
 * Classe représentant une sortie (this) créé pour le site running tracker
 * @author Baptiste G.
 * @version 2.0
 */
class Trunning extends tracks
{
  protected $erreurs = [],
            $id,
            $date_course,
            $id_circuit,
            $km,
            $temps_course,
            $vitesse_moy,
            $calories,
            $commentaire;
  
  /**
   * Constantes relatives aux erreurs possibles rencontrées lors de l'exécution de la méthode.
   */
  const DATECOURSE_INVALIDE = 1;
  const IDCIRCUIT_INVALIDE = 2;
  const KM_INVALIDE = 3;
  const TEMPSCOURSE_INVALIDE = 4;
  const CAL_INVALIDE = 5;
  const COMMENTAIRE_INVALIDE = 6;
  
  /**
   * Constructeur de la classe qui assigne les données spécifiées en paramètre aux attributs correspondants.
   * @param $valeurs array Les valeurs à assigner
   * @return void
   */
  public function __construct($valeurs = [])
  {
    if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet.
    {
      $this->hydrate($valeurs);
    }
  }
  
  /**
   * Méthode assignant les valeurs spécifiées aux attributs correspondant.
   * @param $donnees array Les données à assigner
   * @return void
   */
  public function hydrate($donnees)
  {
    foreach ($donnees as $attribut => $valeur)
    {
      $methode = 'set'.ucfirst($attribut);
      
      if (is_callable([$this, $methode]))
      {
        $this->$methode($valeur);
      }
    }
  }
  

  
  // SETTERS //
  
   
  // GETTERS //
  
  
  
/* --- FONCTIONS SPECIFIQUES --- */

public function calc_calories($poids)
  {
    list($hours,$minutes,$seconde) = explode(":", $this->temps_course());
    $temps = $hours + ($minutes/60) +($seconde/3600);
    
    $vitesse_moy = $this->vitesse_moy();

  
      /* poids entre 55 et 65 kg */
      if($poids > 55 && $poids <= 65 ){

        $calories = (56.842*$vitesse_moy) + 36.632;
        $calories = $calories * $temps;
      }

      /* poids entre 65 et 75 kg */
      if($poids > 65 && $poids < 75 ){

        $calories = (66.316*$vitesse_moy)+42.737;
        $calories = $calories * $temps;
      }

      /* poids entre 75 et 85 kg */
      if($poids > 75 && $poids < 85 ){

        $calories = (75.789*$vitesse_moy)+48.842;
        $calories = $calories * $temps;
      }

      /* poids entre 85 et 95 kg */
      if($poids > 85 && $poids < 95 ){

        $calories = (85.289*$vitesse_moy)+54.842;
        $calories = $calories * $temps;
      }

    $this->setCalories($calories); 
    return $calories;
  }

}
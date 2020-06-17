<?php
/**
 * Classe représentant une sortie (this) créé pour le site running tracker
 * @author Baptiste G.
 * @version 2.0
 */
class Tswimming extends tracks
{
  protected $erreurs = [],
            $id,
            $date_course,
            $id_circuit,
            $km,
            $temps_course,
            $calories,
            $vitesse_moy,
            $commentaire;
  
  /**
   * Constantes relatives aux erreurs possibles rencontrées lors de l'exécution de la méthode.
   */
  const DATECOURSE_INVALIDE = 1;
  const IDCIRCUIT_INVALIDE = 2;
  const KM_INVALIDE = 3;
  const TEMPSCOURSE_INVALIDE = 4;
  const CALO_INVALIDE = 7;
  const COMMENTAIRE_INVALIDE = 8;
  
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
  


  public function setCalories($calories)
  {
    if (empty($calories))
    {
      $this->erreurs[] = self::CALO_INVALIDE;
    }
    else
    {
      $this->calories = $calories;
    }
      
  }


   
  // GETTERS //


       public function calories()
  {
    return $this->calories;
  }

  
/* --- FONCTIONS SPECIFIQUES --- */

   public function calc_calories($poids)
  {
    list($hours,$minutes,$seconde) = explode(":", $this->temps_course());
    $temps = $hours + ($minutes/60) +($seconde/3600);
    $vitesse_moy = $this->vitesse_moy();

    $calories =0;
      
    
    $this->setCalories($calories); 
    return $calories;
  }

}

<?php
/**
 * Classe représentant une sortie (this) créé pour le site running tracker
 * @author Baptiste G.
 * @version 2.0
 */
class Tcycling extends tracks
{
  protected $erreurs = [],
            $id,
            $date_course,
            $id_circuit,
            $km,
            $temps_course,
            $vitesse_max,
            $dennivele,
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
  const VITMAX_INVALIDE = 5;
  const DENNIV_INVALIDE = 6;
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
  

   public function setVitesse_max($vitesse_max)
  {
    if (empty($vitesse_max))
    {
      $this->erreurs[] = self::VITMAX_INVALIDE;
    }
    else
    {
      $this->vitesse_max = $vitesse_max;
    }
      
  }

  public function setDennivele($dennivele)
  {
    if (empty($dennivele))
    {
      $this->erreurs[] = self::DENNIV_INVALIDE;
    }
    else
    {
      $this->dennivele = $dennivele;
    }
      
  }

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

 
   public function vitesse_max()
  {
    return $this->vitesse_max;
  }

     public function dennivele()
  {
    return $this->dennivele;
  }

       public function calories()
  {
    return $this->calories;
  }

  
/* --- FONCTIONS SPECIFIQUES --- */

   public function calc_calories($poids)
  {
    list($hours,$minutes,$seconde) = explode(":", $this->temps_course());
    $temps = $hours + ($minutes/60) +($seconde/3600);
    $dennivele = $this->dennivele();
    $vitesse_moy = $this->vitesse_moy();

    
      /* ---- sans montagne - D+ < 500m  ----*/
      /* allure faible */
      if(($vitesse_moy < 19) && ($dennivele < 500)){

        $calories = (6.5474*$poids)-37.895;
      }

      /* allure normale sans montagne - D+ < 500m */
      if(($vitesse_moy > 19) && ($vitesse_moy < 22) && ($dennivele < 500)){

        $calories = (8.7*$poids)-48.5;
        $calories = $calories * $temps;
      }

      /* allure rapide sans montagne - D+ < 500m */
      if(($vitesse_moy > 22) && ($vitesse_moy < 25) && ($dennivele < 500)){

        $calories = (10.895*$poids)-61.789;
        $calories = $calories * $temps;
      }

      /* allure très rapide sans montagne - D+ < 500m */
      if(($vitesse_moy > 25) && ($vitesse_moy < 30) && ($dennivele < 500)){

        $calories = (13.053*$poids)-73.105;
        $calories = $calories * $temps;
      }

      /* allure très très rapide sans montagne - D+ < 500m */
        if($vitesse_moy > 30){

          $calories = (11.053*$poids)+187.89;
          $calories = $calories * $temps;
        }

      /*---- AVEC MONTAGNE ----*/
      if ($dennivele > 500 ){
        /* calcul du temps de montee - à 900m/h*/
          $temps_grimpe = $dennivele/900;
          
          $calories_grimpe = (11.053*$poids)+187.89;
          $calories_grimpe = $calories_grimpe * $temps_grimpe;
          

          /* CALCUL CALORIES PARTIE AU PLAT

          /* allure faible (FORMULE A MODIFIER) */
        if($vitesse_moy < 19){

          $calories_HG = (8.7*$poids)-48.5;
          $calories_HG = $calories_HG * ($temps-$temps_grimpe);
          $calories = $calories_HG + $calories_grimpe;
        }

        /* allure normale */
        if(($vitesse_moy > 19) && ($vitesse_moy < 22)){

          $calories_HG = (8.7*$poids)-48.5;
          $calories_HG = $calories_HG * ($temps-$temps_grimpe);
          $calories = $calories_HG + $calories_grimpe;
        }

        /* allure rapide sans montagne - D+ < 500m */
        if(($vitesse_moy > 22) && ($vitesse_moy < 25)) {

          $calories_HG = (10.895*$poids)-61.789;
          $calories_HG = $calories_HG * ($temps-$temps_grimpe);
          $calories = $calories_HG + $calories_grimpe;
        }

        /* allure très rapide sans montagne - D+ < 500m */
        if(($vitesse_moy > 25) && ($vitesse_moy < 30)){

          $calories_HG = (13.053*$poids)-73.105;
          $calories_HG = $calories_HG * ($temps-$temps_grimpe);
          $calories = $calories_HG + $calories_grimpe;
        }
        /* allure très très rapide sans montagne - D+ < 500m */
        if($vitesse_moy > 30){

          $calories_HG = (11.053*$poids)+187.89;
          $calories_HG = $calories_HG * ($temps-$temps_grimpe);
          $calories = $calories_HG + $calories_grimpe;
        }
      }
    
    $this->setCalories($calories); 
    return $calories;
  }

}

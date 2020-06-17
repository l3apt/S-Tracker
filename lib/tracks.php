<?php
/**
 * Classe représentant une sortie (this) créé pour le site running tracker
 * @author Baptiste G.
 * @version 2.0
 */
class Tracks
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
  
  /**
   * Méthode permettant de savoir si le track est nouveau.
   * @return bool
   */
  public function isNew()
  {
    return empty($this->id);
  }
  
  /**
   * Méthode permettant de savoir si le track est valide.
   * @return bool
   */
  public function isValid()
  {
    return !(empty($this->date_course) || empty($this->km) || empty($this->temps_course) || empty($this->commentaire));
  }
  
  
  // SETTERS //
  
  public function setId($id)
  {
    $this->id = (int) $id;
  }
  
  public function setDate_course($date_course)
  {
    list($year,$month,$day) = explode("-", $date_course);
    if (empty($date_course) || (!checkdate($month, $day, $year)) )
    {

      $this->erreurs[] = self::DATECOURSE_INVALIDE;
    }
    else
    {
      $this->date_course = $date_course;
    }
  }
  
  public function setId_circuit($id_circuit)
  {
    if (!is_int($id_circuit) || empty($id_circuit))
    {
      $this->erreurs[] = self::IDCIRCUIT_INVALIDE;
    }
    else
    {
      $this->id_circuit = $id_circuit;
    }
  }
  
  public function setKm($km)
  {
    if (!is_numeric($km) || empty($km))
    {
      $this->erreurs[] = self::KM_INVALIDE;
    }
    else
    {
      $this->km = $km;
    }
  }
  
    public function setTemps_course($temps_course)
  {
    list($hours,$minutes,$seconde) = explode(":", $temps_course);
    if (empty($temps_course))
    {

      $this->erreurs[] = self::TEMPSCOURSE_INVALIDE;
    }
    else
    {
      $this->temps_course = $temps_course;
    }
  }

    public function setVitesse_moy($vitesse_moy)
  {
    
      $this->vitesse_moy = $vitesse_moy;
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

  public function setCommentaire($commentaire)
  {
    if (!is_string($commentaire) || empty($commentaire))
    {
      $this->erreurs[] = self::COMMENTAIRE_INVALIDE;
    }
    else
    {
      $this->commentaire = $commentaire;
    }
  }

   
  // GETTERS //
  
  public function erreurs()
  {
    return $this->erreurs;
  }
  
  public function id()
  {
    return $this->id;
  }
  
  public function date_course()
  {
    return $this->date_course;
  }

  public function date_course_fr()
  {
    list($year,$month,$day) = explode("-", $this->date_course);
    return "$day/$month/$year";
  }
  
  public function Id_circuit()
  {
    return $this->id_circuit;
  }
  
  public function km()
  {
    return $this->km;
  }
  
  public function temps_course()
  {
    return $this->temps_course;
  }

  public function temps_course_fr()
  {
    list($hours,$minutes,$sec) = explode(":", $this->temps_course);
    return "$hours h $minutes mn $sec s";
  }

    public function vitesse_moy()
  {
    return $this->vitesse_moy;
  }

      public function calories()
  {
    return $this->calories;
  }


  public function commentaire()
  {
    return $this->commentaire;
  }
  
/* --- FONCTIONS SPECIFIQUES --- */

  /* Commentaire réduit à $nb_car caractères*/
  public function commentaire_red($nb_car)
  {
    if (strlen($this->commentaire()) < $nb_car) 
      {
        $com = $this->commentaire();
      }
      else{
        $debut = substr($this->commentaire(), 0, $nb_car);
        $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
        $com = $debut;
      }
    return $com;
  } 

  /* Calcul et SET de la vitesse moyenne */

    public function calc_vitesse_moy()
  {
    list($hours,$minutes,$seconde) = explode(":", $this->temps_course());
    $temps_heure = (($minutes/60) + $hours);
    $distance_km = $this->km();
    $vitesse_moy_calc = $distance_km / $temps_heure;
    $this->setVitesse_moy($vitesse_moy_calc); 

    return $this->vitesse_moy;
  }

}
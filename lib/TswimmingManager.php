<?php
class TswimmingManager extends TracksManager 
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
  protected $db;
  
  /**
   * Constructeur étant chargé d'enregistrer l'instance de PDO dans l'attribut $db.
   * @param $db PDO Le DAO
   * @return void
   */
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }
  
  /**
   * @see tracksManager::add()
   */
  protected function add_tswimming(Tswimming $tswimming)
  {
    $requete = $this->db->prepare('INSERT INTO swimming(date_course, id_circuit, km, temps_course, vitesse_moy, calories, commentaire) 
                                            VALUES (:date_course, :id_circuit, :km, :temps_course, :vitesse_moy, :calories, :commentaire)');
    
    $requete->bindValue(':date_course', $tswimming->date_course());
    $requete->bindValue(':id_circuit', $tswimming->id_circuit());
    $requete->bindValue(':km', $tswimming->km());
    $requete->bindValue(':temps_course', $tswimming->temps_course());
    $requete->bindValue(':vitesse_moy', $tswimming->vitesse_moy());
    $requete->bindValue(':calories', $tswimming->calories());
    $requete->bindValue(':commentaire', $tswimming->commentaire());
    
    $requete->execute();
  }
  
  /**
   * @see tswimmingManager::count()
   */
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM swimming')->fetchColumn();
  }
  
  /**
   * @see tswimmingManager::delete()
   */
  public function delete($id)
  {
    $this->db->exec('DELETE FROM swimming WHERE id = '.(int) $id);
  }
  
  /**
   * @see tswimmingManager::getList()
   */
  public function getList($debut = -1, $limite = -1)
  {
    $sql = 'SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, calories, commentaire FROM swimming ORDER BY id DESC';
    
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
    
    $requete = $this->db->query($sql);
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'tswimming');
    
    $listeswimming = $requete->fetchAll();

    $requete->closeCursor();
    
    return $listeswimming;
  }
  
  /**
   * @see tracksManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->db->prepare('SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, calories, commentaire
                                     FROM swimming WHERE id = :id');
    $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $requete->execute();
    
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'tswimming');

    $tswimming = $requete->fetch();
    
    return $tswimming;
  }
  
  /**
   * @see tracksManager::update()
   */
  protected function update_tswimming(Tswimming $tswimming)
  {
    $requete = $this->db->prepare('UPDATE swimming SET date_course = :date_course, 
                                                    id_circuit = :id_circuit,
                                                    km = :km, 
                                                    temps_course = :temps_course,
                                                    commentaire = :commentaire,
                                                    vitesse_moy = :vitesse_moy,
                                                    dennivele = :dennivele,
                                                    calories = :calories,
                                                    id = :id
                                                     WHERE id = :id');
    
    $requete->bindValue(':date_course', $tswimming->date_course());
    $requete->bindValue(':id_circuit', $tswimming->id_circuit());
    $requete->bindValue(':km', $tswimming->km());
    $requete->bindValue(':temps_course', $tswimming->temps_course());
    $requete->bindValue(':vitesse_moy', $tswimming->vitesse_moy());
    $requete->bindValue(':calories', $tswimming->calories());
    $requete->bindValue(':commentaire', $tswimming->commentaire());
    $requete->bindValue(':id', $tswimming->id(), PDO::PARAM_INT);
    
    $requete->execute();
  }

    public function save_tswimming(Tswimming $tswimming)
  {
    if ($tswimming->isValid())
    {
      $tswimming->isNew() ? $this->add_tswimming($tswimming) : $this->update_tswimming($tswimming);
    }
    else
    {
      throw new RuntimeException('Le track doit être valide pour être enregistré');
    }
  }
}
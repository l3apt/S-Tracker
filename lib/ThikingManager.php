<?php
class ThikingManager extends TracksManager 
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
  protected function add_thiking(Thiking $thiking)
  {
    $requete = $this->db->prepare('INSERT INTO hiking(date_course, id_circuit, km, temps_course, vitesse_moy, dennivele, calories, commentaire) 
                                            VALUES (:date_course, :id_circuit, :km, :temps_course, :vitesse_moy, :dennivele, :calories, :commentaire)');
    
    $requete->bindValue(':date_course', $thiking->date_course());
    $requete->bindValue(':id_circuit', $thiking->id_circuit());
    $requete->bindValue(':km', $thiking->km());
    $requete->bindValue(':temps_course', $thiking->temps_course());
    $requete->bindValue(':vitesse_moy', $thiking->vitesse_moy());
    $requete->bindValue(':dennivele', $thiking->dennivele());
    $requete->bindValue(':calories', $thiking->calories());
    $requete->bindValue(':commentaire', $thiking->commentaire());
    
    $requete->execute();
  }
  
  /**
   * @see thikingManager::count()
   */
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM hiking')->fetchColumn();
  }
  
  /**
   * @see thikingManager::delete()
   */
  public function delete($id)
  {
    $this->db->exec('DELETE FROM hiking WHERE id = '.(int) $id);
  }
  
  /**
   * @see thikingManager::getList()
   */
  public function getList($debut = -1, $limite = -1)
  {
    $sql = 'SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, dennivele, calories, commentaire FROM hiking ORDER BY id DESC';
    
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
    
    $requete = $this->db->query($sql);
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'thiking');
    
    $listehiking = $requete->fetchAll();

    $requete->closeCursor();
    
    return $listehiking;
  }
  
  /**
   * @see tracksManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->db->prepare('SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, dennivele, calories, commentaire
                                     FROM hiking WHERE id = :id');
    $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $requete->execute();
    
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'thiking');

    $thiking = $requete->fetch();
    
    return $thiking;
  }
  
  /**
   * @see tracksManager::update()
   */
  protected function update_thiking(Thiking $thiking)
  {
    $requete = $this->db->prepare('UPDATE hiking SET date_course = :date_course, 
                                                    id_circuit = :id_circuit,
                                                    km = :km, 
                                                    temps_course = :temps_course,
                                                    commentaire = :commentaire,
                                                    vitesse_moy = :vitesse_moy,
                                                    dennivele = :dennivele,
                                                    calories = :calories,
                                                    id = :id
                                                     WHERE id = :id');
    
    $requete->bindValue(':date_course', $thiking->date_course());
    $requete->bindValue(':id_circuit', $thiking->id_circuit());
    $requete->bindValue(':km', $thiking->km());
    $requete->bindValue(':temps_course', $thiking->temps_course());
    $requete->bindValue(':vitesse_moy', $thiking->vitesse_moy());
    $requete->bindValue(':dennivele', $thiking->dennivele());
    $requete->bindValue(':calories', $thiking->calories());
    $requete->bindValue(':commentaire', $thiking->commentaire());
    $requete->bindValue(':id', $thiking->id(), PDO::PARAM_INT);
    
    $requete->execute();
  }

    public function save_thiking(Thiking $thiking)
  {
    if ($thiking->isValid())
    {
      $thiking->isNew() ? $this->add_thiking($thiking) : $this->update_thiking($thiking);
    }
    else
    {
      throw new RuntimeException('Le track doit être valide pour être enregistré');
    }
  }
}
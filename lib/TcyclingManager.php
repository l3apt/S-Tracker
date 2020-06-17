<?php
class TcyclingManager extends TracksManager 
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
  protected function add_tcycling(Tcycling $tcycling)
  {
    $requete = $this->db->prepare('INSERT INTO cycling(date_course, id_circuit, km, temps_course, vitesse_moy, vitesse_max, dennivele, calories, commentaire) 
                                            VALUES (:date_course, :id_circuit, :km, :temps_course, :vitesse_moy, :vitesse_max, :dennivele, :calories, :commentaire)');
    
    $requete->bindValue(':date_course', $tcycling->date_course());
    $requete->bindValue(':id_circuit', $tcycling->id_circuit());
    $requete->bindValue(':km', $tcycling->km());
    $requete->bindValue(':temps_course', $tcycling->temps_course());
    $requete->bindValue(':vitesse_moy', $tcycling->vitesse_moy());
    $requete->bindValue(':vitesse_max', $tcycling->vitesse_max());
    $requete->bindValue(':dennivele', $tcycling->dennivele());
    $requete->bindValue(':calories', $tcycling->calories());
    $requete->bindValue(':commentaire', $tcycling->commentaire());
    
    $requete->execute();
  }
  
  /**
   * @see tcyclingManager::count()
   */
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM cycling')->fetchColumn();
  }
  
  /**
   * @see tcyclingManager::delete()
   */
  public function delete($id)
  {
    $this->db->exec('DELETE FROM cycling WHERE id = '.(int) $id);
  }
  
  /**
   * @see tcyclingManager::getList()
   */
  public function getList($debut = -1, $limite = -1)
  {
    $sql = 'SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, vitesse_max, dennivele, calories, commentaire FROM cycling ORDER BY id DESC';
    
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
    
    $requete = $this->db->query($sql);
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'tcycling');
    
    $listecycling = $requete->fetchAll();

    $requete->closeCursor();
    
    return $listecycling;
  }
  
  /**
   * @see tracksManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->db->prepare('SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, vitesse_max, dennivele, calories, commentaire
                                     FROM cycling WHERE id = :id');
    $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $requete->execute();
    
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'tcycling');

    $tcycling = $requete->fetch();
    
    return $tcycling;
  }
  
  /**
   * @see tracksManager::update()
   */
  protected function update_tcycling(Tcycling $tcycling)
  {
    $requete = $this->db->prepare('UPDATE cycling SET date_course = :date_course, 
                                                    id_circuit = :id_circuit,
                                                    km = :km, 
                                                    temps_course = :temps_course,
                                                    commentaire = :commentaire,
                                                    vitesse_moy = :vitesse_moy,
                                                    vitesse_max = :vitesse_max,
                                                    dennivele = :dennivele,
                                                    calories = :calories,
                                                    id = :id
                                                     WHERE id = :id');
    
    $requete->bindValue(':date_course', $tcycling->date_course());
    $requete->bindValue(':id_circuit', $tcycling->id_circuit());
    $requete->bindValue(':km', $tcycling->km());
    $requete->bindValue(':temps_course', $tcycling->temps_course());
    $requete->bindValue(':vitesse_moy', $tcycling->vitesse_moy());
    $requete->bindValue(':vitesse_max', $tcycling->vitesse_max());
    $requete->bindValue(':dennivele', $tcycling->dennivele());
    $requete->bindValue(':calories', $tcycling->calories());
    $requete->bindValue(':commentaire', $tcycling->commentaire());
    $requete->bindValue(':id', $tcycling->id(), PDO::PARAM_INT);
    
    $requete->execute();
  }

    public function save_tcycling(Tcycling $tcycling)
  {
    if ($tcycling->isValid())
    {
      $tcycling->isNew() ? $this->add_tcycling($tcycling) : $this->update_tcycling($tcycling);
    }
    else
    {
      throw new RuntimeException('Le track doit être valide pour être enregistré');
    }
  }
}
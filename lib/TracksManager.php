<?php
class TracksManager 
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
  protected function add(Tracks $tracks)
  {
    $requete = $this->db->prepare('INSERT INTO tracks(date_course, id_circuit, km, temps_course, vitesse_moy, calories, commentaire) 
                                            VALUES (:date_course, :id_circuit, :km, :temps_course, :vitesse_moy, :calories, :commentaire)');
    
    $requete->bindValue(':date_course', $tracks->date_course());
    $requete->bindValue(':id_circuit', $tracks->id_circuit());
    $requete->bindValue(':km', $tracks->km());
    $requete->bindValue(':temps_course', $tracks->temps_course());
    $requete->bindValue(':vitesse_moy', $tracks->vitesse_moy());
    $requete->bindValue(':calories', $tracks->calories());
    $requete->bindValue(':commentaire', $tracks->commentaire());
    
    $requete->execute();
  }
  
  /**
   * @see tracksManager::count()
   */
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM tracks')->fetchColumn();
  }
  
  /**
   * @see tracksManager::delete()
   */
  public function delete($id)
  {
    $this->db->exec('DELETE FROM tracks WHERE id = '.(int) $id);
  }
  
  /**
   * @see tracksManager::getList()
   */
  public function getList($debut = -1, $limite = -1)
  {
    $sql = 'SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, calories, commentaire FROM tracks ORDER BY id DESC';
    
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
    
    $requete = $this->db->query($sql);
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'tracks');
    
    $listetracks = $requete->fetchAll();

    $requete->closeCursor();
    
    return $listetracks;
  }
  
  /**
   * @see tracksManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->db->prepare('SELECT id, date_course, id_circuit, km, temps_course, vitesse_moy, calories, commentaire FROM tracks WHERE id = :id');
    $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $requete->execute();
    
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'tracks');

    $tracks = $requete->fetch();
    
    return $tracks;
  }
  
  /**
   * @see tracksManager::update()
   */
  protected function update(tracks $tracks)
  {
    $requete = $this->db->prepare('UPDATE tracks SET date_course = :date_course, 
                                                    id_circuit = :id_circuit,
                                                    km = :km, 
                                                    temps_course = :temps_course,
                                                    commentaire = :commentaire,
                                                    vitesse_moy = :vitesse_moy,
                                                    calories = :calories,
                                                    id = :id
                                                     WHERE id = :id');
    
    $requete->bindValue(':date_course', $tracks->date_course());
    $requete->bindValue(':id_circuit', $tracks->id_circuit());
    $requete->bindValue(':km', $tracks->km());
    $requete->bindValue(':temps_course', $tracks->temps_course());
    $requete->bindValue(':vitesse_moy', $tracks->vitesse_moy());
    $requete->bindValue(':calories', $tracks->calories());
    $requete->bindValue(':commentaire', $tracks->commentaire());
    $requete->bindValue(':id', $tracks->id(), PDO::PARAM_INT);
    
    $requete->execute();
  }

    public function save(Tracks $tracks)
  {
    if ($tracks->isValid())
    {
      $tracks->isNew() ? $this->add($tracks) : $this->update($tracks);
    }
    else
    {
      throw new RuntimeException('Le track doit être valide pour être enregistré');
    }
  }
}
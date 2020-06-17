<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new TracksManager($db);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Accueil du site</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
  </head>
  
  <body>

    <!-- Baniere -->

 <?php 
include("baniere.php"); 
    
/* Si les détails d'un track sont demandés  */
if (isset($_GET['id'])) 
{
  $tracks = $manager->getUnique((int) $_GET['id']);
  
  echo '<div id="conteneur_tracks">';
  echo '<div class="course_unique "> <p> <div class ="titre_bloc"><a href="?id=', $tracks->id(), '"> Track '. $_GET['nb'].' ', '</a></div><br/>';
  echo '<div class = "data_course_unique"> Course num  '.$tracks->id(). ' de '.$tracks->km().' km,  réalisée le '.$tracks->date_course_fr().'<br/> ';
  echo 'Vitesse moyenne:  '.$tracks->vitesse_moy().' km/h <br/>';
  echo '<em>Commentaire:</em> '.$tracks->commentaire(). '</p> </div> ';
  echo '</div> </div>';
}

else
  /*  AFFICHAGE DE TOUS LES TRACKS  */
{
  $nbtrack = 1;
  echo '<h2>Vos tracks</h2>';
  echo '<div id="conteneur_tracks">';
  foreach ($manager->getList() as $tracks)
  {
      echo '<div class="course "> <p> <div class ="titre_bloc">';
      echo '<a href="?id=', $tracks->id(),'&nb=',$nbtrack,'"> Track ', $nbtrack, '</a></div><br/>';
      echo '<div class = "data_course"> Course num  '.$tracks->id(). ' de '.$tracks->km().' km,  réalisée le '.$tracks->date_course_fr().'<br/> ';
      echo 'Vitesse moyenne:  '.$tracks->vitesse_moy().' km/h <br/>';
      echo '<em>Commentaire:</em> '.$tracks->commentaire_red(100). '</p> </div> ';
      $nbtrack++;
      
       echo '</div>';
  }
  echo '</div>';
}
?>
  </body>
</html>
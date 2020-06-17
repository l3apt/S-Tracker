<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new tracksManager($db);

if (isset($_GET['modifier']))
{
  $tracks = $manager->getUnique((int) $_GET['modifier']);
}

if (isset($_GET['supprimer']))
{
  $manager->delete((int) $_GET['supprimer']);
  $message = 'Le track a bien été supprimée !';
}

if (isset($_POST['km']))
{
  $tracks = new tracks(
    [
      'date_course' => $_POST['date_course'],
      'id_circuit' => $_POST['id_circuit'],
      'km' => $_POST['km'],
      'temps_course' => $_POST['temps_course'],
      'commentaire' => $_POST['commentaire']
    ]
  );
  
  if (isset($_POST['id']))
  {
    $tracks->setId($_POST['id']);
  }
  
  if ($tracks->isValid())
  {
    /* On calcule la vitesse moyenne avant d'enregistrer en BDD le track */
    list($hours,$minutes,$seconde) = explode(":", $tracks->temps_course());
    $temps_heure = (($minutes/60) + $hours);
    $distance_km = $tracks->km();
    $vitesse_moy_calc = $distance_km / $temps_heure;
    $tracks->setVitesse_moy($vitesse_moy_calc); 

    $manager->save($tracks);
    
    $message = $tracks->isNew() ? 'Le track a bien été ajoutée !' : 'Le track a bien été modifiée !';
  }
  else
  {
    $erreurs = $tracks->erreurs();
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Gestion Tracks</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
    
    <style type="text/css">
      table, td {
        border: 1px solid black;
      }
      
      table {
        margin:auto;
        text-align: center;
        border-collapse: collapse;
      }
      
      td {
        padding: 3px;
      }
    </style>
  </head>
  
  <body>
    <!--<p><a href="Home.php">Accéder à l'accueil du site</a></p>-->
    <?php 
      include("baniere.php"); 
      ?>
    
    <form action="gestionTracks.php" method="post">
      <p style="text-align: center">
<?php
if (isset($message))
{
  echo $message, '<br />';
}
?>      <!--  DATE  -->
        <?php if (isset($erreurs) && in_array(tracks::DATECOURSE_INVALIDE, $erreurs)) echo 'La date de la course est invalide.<br />'; ?>
        date course : <input type="date" name="date_course" value="<?php if (isset($tracks)) echo $tracks->date_course(); ?>" /><br />
        
        <!--  CIRCUIT  -->
        <?php if (isset($erreurs) && in_array(tracks::IDCIRCUIT_INVALIDE, $erreurs)) echo 'Le numéro du circuit est invalide.<br />'; ?>
        numero de circuit : <input type="number" name ="id_circuit" min ="0" step="1" value="<?php if (isset($tracks)) echo $tracks->id_circuit(); ?>"/><br />
        
        <!--  DISTANCE  -->
        <?php if (isset($erreurs) && in_array(tracks::KM_INVALIDE, $erreurs)) echo 'Le nombre de km est invalide.<br />'; ?>
        Distance (en km) : <input type="number" name ="km" min ="0" max ="20" step="0.01" value= "<?php if (isset($tracks)) echo $tracks->km(); ?>"/><br />

        <!--  TEMPS  -->
        <?php if (isset($erreurs) && in_array(tracks::TEMPSCOURSE_INVALIDE, $erreurs)) echo 'Le temps de course est invalide.<br />'; ?>
        Temps(HH:MM:SS) : <input type="time" name ="temps_course" min="00:00:00" max="05:00:00" step ="1" value="<?php if (isset($tracks)) echo $tracks->temps_course(); ?>"</input><br />

        <!--  COMMENTAIRE  -->
        <?php if (isset($erreurs) && in_array(Tracks::COMMENTAIRE_INVALIDE, $erreurs)) echo 'Le commentaire est invalide.<br />'; ?>
        Commentaire :<br /><textarea rows="3" cols="60" name="commentaire"><?php if (isset($tracks)) echo $tracks->commentaire(); ?></textarea><br />
<?php
if(isset($tracks) && !$tracks->isNew())
{
?>
        <input type="hidden" name="id" value="<?= $tracks->id() ?>" />
        <input type="submit" value="Modifier" name="modifier" />
<?php
}
else
{
?>
        <input type="submit" value="Ajouter" />
<?php
}
?>
      </p>
    </form>
    
    <p style="text-align: center">Il y a actuellement <?= $manager->count() ?> tracks. En voici la liste :</p>
    
    <table>
      <tr><th>Date</th><th>circuit</th><th>Distance</th><th>temps</th><th>vitesse</th><th>commentaire</th></tr>
<?php
foreach ($manager->getList() as $tracks)
{
  echo '<tr><td>', $tracks->date_course_fr(), '</td>
            <td>', $tracks->id_circuit(), '</td>
            <td>', $tracks->km(), '</td>
            <td>', $tracks->temps_course_fr(), '</td>
            <td>', $tracks->vitesse_moy(), '</td>
            <td>', $tracks->commentaire_red(150), '</td>
            <td>', '</td>
            <td><a href="?modifier=', $tracks->id(), '">Modifier</a> 
            | <a href="?supprimer=', $tracks->id(), '">Supprimer</a></td></tr>', "\n";
}
?>
    </table>
  </body>
</html>
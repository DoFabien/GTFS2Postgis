<?php

$file_gtfs = 'SEM-GTFS.zip'; //nom du fichier GTFS (zip) qui doit être dans dans /GTFS
$replace_data = true; // supprimer le contenu des tables avant l'import?

$db_name = 'gtfs';
$userPG = 'postgres';
$passwordPG = '';
$host = 'localhost';

try {
  $db = new PDO("pgsql:host=$host;dbname=$db_name", $userPG, $passwordPG);

}
catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}
?>
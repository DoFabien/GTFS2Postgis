# GTFS2Postgis
GTFS2Postgis est un script PHP qui permet d'importer des données GTFS dans Postgis. 
La structure de données est la même que celle des [des specifications de Google](https://developers.google.com/transit/gtfs/reference#field-definitions). A chaque fichier txt correspond une table portant le même nom ('.txt' en moins).

#### Prérequis
 - PHP 5 >= 5.3.0 ( utilisation de la fonction str_getcsv ) 
 - Une base PostgreSQL/Postgis
 - PDO_PGSQL
 
#### Comment l'utiliser?
 - Le fichier '**create_table.sql**' contient les requêtes qui permettent la création des différentes tables.
 - Le fichier '**config.php**' permet de configurer la connexion à PostgreSQL et le nom du fichier à traiter.
 - Le fichier à traiter doit être contenu dans le dossier GTFS.
 - Il suffit alors d'executer '**GTFS2Postgis.php**'.

Pour l'exemple, le fichier GTFS comprit  dans ce répértoire (*SEM-GTFS.zip*) provient du site [Métrobobilité](http://www.metromobilite.fr/pages/OpenData.html#) et concerne donc le réseau TC de la Métropole Grenobloise.

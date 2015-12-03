<?php
set_time_limit(0);
ini_set('error_reporting', E_ALL);
header('Content-type: text/html; charset=utf-8');
include_once('config.php');

/*Extraction du GTFS dans DATA/nom_du_zip*/
$path_gtfs = 'GTFS/'.$file_gtfs;

if (!file_exists('_GTFS_EXTRACT')) {
    mkdir('_GTFS_EXTRACT', 0777, true);
}

$zip = new ZipArchive;
$res = $zip->open($path_gtfs);
if ($res === TRUE) {
    $zip->extractTo('_GTFS_EXTRACT');
    $zip->close();
    echo "Extraction du GTFS OK <br>";
} else {
    echo "Oups, impossible d'extraire le GTFS";
}
ob_flush();flush();

$tables = array('agency','trips','calendar','calendar_dates','stops','stop_times','directions','fare_attributes','fare_rules','feed_info','frequencies','payment_methods','pickup_dropoff_types','routes','route_types','shapes','transfers','transfer_types');

if ($replace_data == true){
    for($i = 0; $i<count($tables);$i++){
        $req_trun = $db->prepare("TRUNCATE TABLE ".$tables[$i]);

        $req_trun->execute(); 
    }
    echo 'TRUNCATE TABLE <br>';
    ob_flush();flush();
}

/*On parcourt chaque fichier qui porte le nom $tables.txt*/
for ($k=0;  $k< count($tables); $k++){ 
    $file_str = $tables[$k] . '.txt'; // nom du fichier en cours d'import

    if (file_exists($current_file)){ //si le fichier existe 
        $current_file = "_GTFS_EXTRACT/" .$file_str; // chemin du fichier 
        $rHandle = fopen($current_file, 'r');
        $file_handle = fopen($current_file, "r");
        $num = 0;
        while (!feof($file_handle)) {
            $line = fgets($file_handle); 
            if($num ==0){
                $row_head = str_getcsv ( $line,',' , '"' );
                $sql_feilds = implode(',',$row_head);
                $sql_params = ':'.implode(',:',$row_head);
                $table = explode('.',$file_str)[0];
                $sql = "INSERT INTO $table ($sql_feilds) VALUES ($sql_params)";
                $req = $db->prepare($sql);
            }
            else{
                $arr_ass = array();
                $row = str_getcsv ( $line,',' , '"' );
                for ($j=0;$j<count($row);$j++){ 
                    $value = $row[$j]; 
                    if ($row[$j] ==''){$value = null;}
                    $arr_ass[$row_head[$j]] = $value;
                }
                $req->execute($arr_ass);    
            }
            $num ++;
        }
        fclose($file_handle);
        chmod($current_file,'0777');
        unlink($current_file) ;
        echo $file_str . ' Done <br>' ;
        ob_flush();flush();
    }
}

/*VACUUM ANALYZE*/
for($i = 0; $i<count($tables);$i++){
    $req_trun = $db->prepare("VACUUM ANALYZE ".$tables[$i]);
    $req_trun->execute(); 
}
echo 'VACUUM ANALYZE  <br>';
rmdir('_GTFS_EXTRACT');
echo 'Done!';
?>
<?php
set_time_limit(0);
ini_set('error_reporting', E_ALL);
include_once('config.php');
$tables = array('agency','trips','calendar','calendar_dates','stops','stop_times','directions','fare_attributes','fare_rules','feed_info','frequencies','payment_methods','pickup_dropoff_types','routes','route_types','shapes','transfers','transfer_types');


$replace_data = true;

if ($replace_data == true){
    for($i = 0; $i<count($tables);$i++){
        $req_trun = $db->prepare("TRUNCATE TABLE ".$tables[$i]);
       
        $req_trun->execute(); 
    }
     echo 'TRUNCATE TABLE <br>';
    ob_flush();
        flush();
}

/*On parcourt chaque fichier qui porte le nom $tables.txt*/
for ($k=0;  $k< count($tables); $k++){ 
    $file_str = $tables[$k] . '.txt'; // nom du fichier en cours d'import
    $chemin =  $path . "/" .$file_str; // chemin complet 
    if (file_exists($chemin)){ //si le fichier existe 
        $rHandle = fopen($chemin, 'r');
        
        $file_handle = fopen($chemin, "r");
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
        
        echo $file_str . ' traité <BR>' ;
          
        ob_flush();
        flush();
    }
}


/*VACUUM ANALYZE*/
for($i = 0; $i<count($tables);$i++){
        $req_trun = $db->prepare("VACUUM ANALYZE ".$tables[$i]);
        $req_trun->execute(); 
    }
echo 'VACUUM ANALYZE  <br>';
?>
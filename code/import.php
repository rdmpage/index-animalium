<?php

// Create a SQLite database from a CSV file

//----------------------------------------------------------------------------------------
// https://gist.github.com/fcingolani/5364532
function import_csv_to_sqlite(&$pdo, $csv_path, $options = array())
{
    extract($options);

    if (($csv_handle = fopen($csv_path, "r")) === FALSE)
        throw new Exception('Cannot open CSV file');

    if(!isset($delimiter))
        $delimiter = ',';

    if(!isset($table))
    {
        $table = preg_replace("/[^A-Z0-9]/i", '', basename($csv_path));
        $table = preg_replace("/[c|t]sv$/i", '', $table);
    }

    if(!isset($fields)){
        $fields = array_map(function ($field){
            return strtolower(preg_replace("/^col:/i", '', $field));
        }, fgetcsv($csv_handle, 0, $delimiter));
    }
    
    print_r($fields);

    $create_fields_str = join(', ', array_map(function ($field){
        return "`$field` TEXT NULL";
    }, $fields));
    
    $pdo->beginTransaction();

    $create_table_sql = "CREATE TABLE IF NOT EXISTS `$table` ($create_fields_str)";
    
    echo $create_table_sql . "\n";
        
    $pdo->exec($create_table_sql);

	$insert_fields_str = join(', ', array_map(function ($field){
        return "`$field`";
    }, $fields));    
    
    $insert_values_str = join(', ', array_fill(0, count($fields),  '?'));
    $insert_sql = "INSERT INTO `$table` ($insert_fields_str) VALUES ($insert_values_str)";
    $insert_sth = $pdo->prepare($insert_sql);
        
    $inserted_rows = 0;
    while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {

    	foreach ($data as $k => $v)
    	{
    		$data[$k] = trim($data[$k]);
     	}
     	
     	print_r($data);
    
        $insert_sth->execute($data);
        $inserted_rows++;
    }

    $pdo->commit();

    fclose($csv_handle);

    return array(
            'table' => $table,
            'fields' => $fields,
            'insert' => $insert_sth,
            'inserted_rows' => $inserted_rows
        );

}

//----------------------------------------------------------------------------------------

$pdo 		= new PDO('sqlite:ia.db');
$csv_path 	=  "bib.tsv"; 
$csv_path 	=  "taxa.tsv"; 



import_csv_to_sqlite($pdo, $csv_path, $options = array('delimiter' => "\t"));

?>

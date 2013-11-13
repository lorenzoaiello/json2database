<?php

/********** CONFIGURATION ************/

$json_file = 'example.json';

$db_host = 'localhost';
$db_name = '';
$db_user = '';
$db_pass = '';

/******** END CONFIGURATION **********/

$table = str_replace(".json","",$json_file);
$file = file_get_contents($json_file);
echo "File Loaded<br>";
$data = json_decode($file);

// create database connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if(mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else{
	echo "Connected to Database<br>";
}

// use first entry to structure database
$columns = array_keys((array)$data[0]);
$sql = 'CREATE TABLE IF NOT EXISTS `'.$table.'` (';
$tables = '';
foreach($columns as $column)
{
	$tables .= '`'.$column.'` varchar(250) NOT NULL,';
}
$sql .= trim($tables,',');
$sql .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1;';
mysqli_query($con,$sql);
echo "..Table Created<br>";

// insert data
foreach($data as $entry)
{
	$columns = array_keys((array)$entry);
	$values = array_values((array)$entry);

	mysqli_query($con,"INSERT INTO `".$table."` (`".implode("`, `",$columns)."`) VALUES ('".implode("', '",$values)."')");
}
echo "..Data Inserted<br>";

mysqli_close($con);
echo "Disconnected from Database";

?>
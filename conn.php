<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "elec_db";

try{
$conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);

$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


}catch(PDOException $e){
	echo "Connection Failed: ", $e->getMessage();
}




?>
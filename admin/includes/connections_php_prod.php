<?php
$hostname = "db";
$database = "web";
$username = "user";
$password = "lpJOaVKuvISzIfyr0teU";
try{
    //$con = new mysqli($hostname,$username,$password,$database);
// Create connection
$dsn = "mysql:host=".$hostname.";dbname=".$database.";charset=utf8";
$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
echo 'erro:'.$e->getMessage();	
}										
?>
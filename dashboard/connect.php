<?php 

$dsn = 'mysql:host=localhost;dbname=shop_elzero';
$user = 'root';
$pass = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{
    $connect = new PDO($dsn, $user, $pass, $options);  // $connect is an object with PDO Class 
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // setAttribute is A function inside PDO Class 
}
catch(PDOException $error){
    echo ' Faild to connect ' . $error->getMESSAGE();
}
<?php 
// Intilaize files to prepare my wrork

ini_set('display_errors', 'On');
error_reporting('E_ALL');

include('dashboard/connect.php');

// Check SESSEION User 
$sessionUser = "";
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

// Routes 
$tmpls  = 'includes/templs/'; // Templates Directory
$funcs  = 'includes/funcs/';  // Function Directory   
$langs  = 'includes/langs/';  // Languages Directory
$librs  = 'includes/librs/';  // Liberary Directory
$css    = 'layout/css/';        // Css Directiory
$js     = 'layout/js/';          // JS Directory 

// Include important files 

include $funcs . "functions.php";
include $langs . "english.php";     // Language must be include before header 
include $tmpls . "head.inc.php"; // Including Header + Nav Bar

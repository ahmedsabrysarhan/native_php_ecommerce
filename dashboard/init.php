<?php 
// Intilaize files to prepare my wrork

include('connect.php');

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
include $tmpls . "header.inc.php";
if(!isset($noNavbar)){ include $tmpls . "navBar.php" ;}
<?php 

session_start();    // => Start Session
session_unset();    // => Unset Data From Session 
session_destroy();  // => Destroy Session 

header('Location:login.php');
exit();
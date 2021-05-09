<?php
ob_start();
    session_start();
    $title = ' ';
    
    if(isset($_SESSION['User']) ){
    include('init.php');
        if(isset($_GET['do']) && $_GET['do'] == 'manage'){

            echo "Welcome to Categories Manager";

        }elseif(isset($_GET['do']) && $_GET['do'] == 'add'){
            echo "Welcome to add categoreis page";
            echo "<br><a href='?do=manage'>Manage</a>";

        }elseif(isset($_GET['do']) && $_GET['do'] == 'insert'){
            echo "Welcome to insert categoreis page";
            echo "<br><a href='?do=manage'>Manage</a>";

        }elseif(isset($_GET['do']) && $_GET['do'] == 'edit'){
            echo "Welcome to Edit categoreis page";
            echo "<br><a href='?do=manage'>Manage</a>";

        }elseif(isset($_GET['do']) && $_GET['do'] == 'update'){
            echo "Welcome to Update categoreis page";
            echo "<br><a href='?do=manage'>Manage</a>";

        }elseif(isset($_GET['do']) && $_GET['do'] == 'delete'){
            echo "Welcome to Delete categoreis page";
            echo "<br><a href='?do=manage'>Manage</a>";

        }else{
            echo 'SORRY! There is no page such that';
        }
        

    include($tmpls . 'footer.inc.php');
    }else{
        header("location:index.php");
        exit();
    }
ob_end_flush();

<?php
/*
Categories => [Manage | Edit | Update | Add | Insert | Delete | Stats] 
Condition ? TRUE : FALSE
*/
$title = 'Manage Page';
include('init.php');

$do = '';                                               // set var to control the movement between pages 
isset($_GET['do']) ? $do = $_GET['do'] : 'manage';      // Return To Manage Page 

// Check for page 
if($do == 'manage'){
    echo 'Welcome To Manage Page<br >';
    echo '<br><a href = "?do=edit">Edit Pages </a> ';
    echo '<br><a href = "?do=add">Add Pages </a> ';
    echo '<br><a href = "?do=insert">Insert Pages </a> ';
    echo '<br><a href = "?do=update">Update Pages </a> ';
    echo '<br><a href = "?do=stats">Statestics Pages </a> ';
    echo '<br><a href = "?do=delete">Delete Pages </a> ';
}elseif( $do == 'edit'){
    echo 'Welcome To Edit Page';
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}elseif( $do == 'update' ){
    echo 'Welcome To Update Page';
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}elseif( $do == 'add' ){
    echo 'Welcome To Add Page';
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}elseif( $do == 'insert' ){
    echo 'Welcome To Insert Page';
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}elseif( $do == 'delete' ){
    echo 'Welcome To Delete Page';
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}elseif( $do == 'stats' ){
    echo 'Welcome To Stats Page';
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}else{
    echo ' ERROR There\'s no Page with this Name' ;
    echo '<br><a href = "?do=manage">Back to Manager </a> ';
}

include( $tmpls . "footer.inc.php");

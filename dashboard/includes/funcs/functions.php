<?php 

/*
* getAllFrom() V1.0 
* Get All data form table 
* $where = NULL => if you want choose some specific add ("WHERE /- Your condition -/")
*/
function getAllFrom($field, $table , $where = NULL, $and = NULL , $alphaBet , $order = 'DESC'){
    global $connect;
    $getAll = $connect->prepare("SELECT $field FROM $table $where $and ORDER BY $alphaBet $order");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

/*
* Get title function v1.0 
* getTitle function => To set pages title 
* global $title to work from any page 
*/

function getTitle(){
    global $title;
    if ( isset($title)){
        echo $title;
    }else{
        echo 'Default';
    }
}


/*
* Error function v2.0
* From v1.0 to v2.0 
    => Change function name from Error to Redirect  
    => Change $errormsg to $themsg [Error | Success | any Message]
    => Add url insted 'index.php' to make the function more dynamic

* Function to SHOW Message and redirect to $url 
*/
function Redirect($themsg, $url = null ,$seconds = 3){
    echo  $themsg ;
        if($url === null){
            $page = "Home";
            $url = "index.php";
        }else {
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
                    $page = 'Previous Page';
                    $url = $_SERVER['HTTP_REFERER'];
                }else{
                    $page = "Home";
                    $url = "index.php";
                }
            }
        echo "<div class='container alert alert-info'>You'll be redirect to $page Page after " . $seconds . " seconds</div>";
        header("refresh:$seconds;url=$url");
        exit();
        }
    



/**
 * checkItem function v1.0
 * Check if there is item like that in database or not 
 * $select => column name to selected (SELECT name[$select] FROM)
 * $from => Table name to selected column  (FROM users [$from])
 * $value => checked value to check (WHERE name = "Ahmed [$value]"); 
 */

function checkItem($select, $from, $value){
    global $connect;
    $statemnt =$connect->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statemnt->execute(array($value));
    $count = $statemnt->rowCount();
    return $count ;
}


/*
 * Function to Calculate numbers of Items in tables v1.0 
 * $item => The item to COUNT 
 * $table => The table to choose from     
*/

function countItems($item, $table){
    global $connect;
    $stmtCounter = $connect->prepare("SELECT COUNT($item) FROM $table");
    $stmtCounter->execute();
    $counter = $stmtCounter->fetchColumn();
    return $counter;
}

/*
 *  Get Latesst Record Function v1.0 
 * $select => Item to select 
 * $table => Table to select from 
 * $order => The column to Order 
 * DESC => DESCENDING to get the laatest items were added into database 
 */

function latestItem($select, $table, $order, $limit = 3){
    global $connect;
    $stmtLatest = $connect->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmtLatest->execute();
    $rows = $stmtLatest->fetchAll();
    return $rows;
}

/**
 * InMB functuoin V1.0 
 * this function return the number in MB Directly 
 */

function inMB($size){
    $sizeInMB = ($size * 1024 * 1024);
    return $sizeInMB;
}


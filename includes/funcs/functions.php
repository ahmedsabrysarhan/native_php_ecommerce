<?php 

// Start FrontEnd functions // 

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
** userStatus() V1.0 
** function to check if user activated or not  
*/

function userStatus($user){
    global $connect;
    $checkActive =$connect->prepare("SELECT UserName, RegisterStatus FROM users WHERE UserName =? AND RegisterStatus = 0 ");
    $checkActive->execute(array($user));
    $active = $checkActive->rowCount();
    return $active;
}



/*  
** getCats() V1.0 
** function to get categories 
*/

function getCats(){
    global $connect;
    $getCats =$connect->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY CatID DESC");
    $getCats->execute();
    $cats = $getCats->fetchAll();
    return $cats ;
}

/*  
** getItems() V2.0 
** function to get Items 
** V2.0 is defferent in input data and more control and dynamic than V1.0 
*/

function getItems($where , $value, $approval = NULL){
    global $connect;
    if($approval == NULL){
        $sql = NULL;
    }else{
        $sql = 'AND Approval = 1 ';
    }
    $getItems =$connect->prepare("SELECT items.* , users.UserName AS Owner FROM items
                                INNER JOIN users ON items.User_ID = users.UserID
                                WHERE $where = ? $sql 
                                ORDER BY ItemID DESC");
    $getItems->execute( array($value) );
    $items = $getItems->fetchAll();
    return $items ;
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

/**
 * InMB functuoin V1.0 
 * this function return the number in MB Directly 
 */

 function inMB($size){
    $sizeInMB = ($size * 1024 * 1024);
    return $sizeInMB;
}
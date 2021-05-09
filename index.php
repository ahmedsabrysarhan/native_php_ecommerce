<?php 
ob_start();
session_start();
$title = "Home";
include "init.php";
$user = $_SESSION['user'];
$check = userStatus($user); //=> check if user active or not 1 thats means the member is not activate 

// Get All Items and Show them in Home Page ?>
<div class="container">
    <h1 class="text-center"> All Ads </h1>
    <div class="row">
    <?php
        $items = getAllFrom("*", "items" , "WHERE Approval = 1" ,"" , "ItemID");
        if(!empty($items)){
            foreach($items as $item){
                echo "<div class='col-sm-6 col-md-3 mb-2'>";
                    echo '<div class="card item-content-cat">';
                        echo '<span class="item-price">$'. $item['Price'] .'</span>';
                        // Check Image 
                        if(! empty($item['Image'])){
                            echo "<img src='upload\items\\" .$item['Image']. "' class='img-responsive card-img-top' alt='item'>";
                        }else{
                            echo '<img src="upload\items\\1.jpg" class="img-responsive card-img-top" alt="item">';
                        }
                        echo '<div class="card-body">';
                            echo '<h5 class="card-title text-center"><a href="items.php?itemid='. $item['ItemID'] .'&do=show">'. $item['Name'] .'</a></h5>';
                            // get Owner and add it 
                            $owner = getAllFrom("UserName", "users", "WHERE UserID = {$item['User_ID']}", "" ,"UserName");
                            echo '<p class="item-owner"> Advertise By: '. $owner[0]['UserName'] .'</p>';
                            // get categroy
                            $cat = getAllFrom("Name", "categories", "WHERE CatID = {$item['Cat_ID']}", "" ,"Name");
                            echo '<p class="item-cat"> Category: '. $cat[0]['Name'] .'</p>';
                            echo '<p class="card-text">'. $item['Description'] .'</p>';
                            echo '<div class="date">'. $item['AddDate'] .'</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>'; 
            }
        }else{
            echo "<div class='empty-msg col-12'> There're no Items to show </div>"; }
    ?>
    </div>
</div>

<?php
include $tmpls . "footer.inc.php";
ob_end_flush(); ?>
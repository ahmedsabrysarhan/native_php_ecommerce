<?php 
ob_start(); 
session_start();
$title = $_GET['tagname'];
include "init.php";
if(isset($_GET['tagname'])){ 
$tagName =  str_replace(" ", "" , $_GET['tagname']);
?>
<div class="container">
    <h1 class="text-center"> <?php echo $tagName; ?> </h1>
    <div class="row">
    <?php
        $items = getAllFrom("*" , "items", "WHERE Tags LIKE '%$tagName%'", "AND Approval = 1 ","ItemID");
        foreach($items as $item){
            echo "<div class='col-sm-6 col-md-3 mb-2'>";
                echo '<div class="card item-content-cat">';
                    echo '<span class="item-price">$'. $item['Price'] .'</span>';
                    echo "<img src='upload\items\\" .$item['Image']. "' class='img-responsive card-img-top' alt='item'>";
                    echo '<div class="card-body">';
                        echo '<h5 class="card-title text-center"><a href="items.php?do=show&itemid='. $item['ItemID'] .'">'. $item['Name'] .'</a></h5>';
                        echo '<p class="item-owner"> Advertise By: '. $item['Owner'] .'</p>';
                        echo '<p class="card-text">'. $item['Description'] .'</p>';
                        echo '<div class="date">'. $item['AddDate'] .'</div>';
                            if(! empty( $item['Tags'])){
                                echo "<span>Tags:</span>";
                                $tags = explode("," , $item['Tags']);
                                foreach($tags as $tag){
                                    echo "<a href='tags.php?tagname=". str_replace(" ", "" , $tag) ."'>". $tag ."</a> |";
                                }
                            }
                        echo '</div>';
                echo '</div>';
            echo '</div>';
            
        }
    ?>
    </div>
</div>
<?php
}else{
    echo "<div class='container alert alert-info mt-5'> There is no items in this tag </div>";
}
include $tmpls . "footer.inc.php";
ob_end_flush(); ?>
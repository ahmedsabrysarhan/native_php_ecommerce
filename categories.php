<?php 
ob_start(); 
session_start();
$title = isset($_GET['cat-name'])? $_GET['cat-name'] : "Category" ;
include "init.php";
if(isset($_GET['catid'])){ 
$Cat_ID =  $_GET['catid'];
$Cat_Name = $_GET['cat-name'];
$Items = getItems( 'Cat_ID',$Cat_ID, 1);
?>
    <div class="container">
    <h1 class="text-center"> <?php echo $Cat_Name; ?> </h1>
    <div class="row">
        <?php  
        if(!empty($Items)){
            foreach($Items as $item){
                echo "<div class='col-sm-6 col-md-3 mb-2'>";
                    echo '<div class="card item-content-cat">';
                        echo '<span class="item-price">$'. $item['Price'] .'</span>';
                        // check Image 
                        if(empty($item['Image'])){
                            echo '<img src="upload\items\\1.jpg" class="img-responsive card-img-top" alt="item">';
                        }else{
                            echo '<img src="upload\items\\'. $item['Image'] .'" class="img-responsive card-img-top img-responsive" alt="item">';
                        }
                        echo '<div class="card-body">';
                            echo '<h5 class="card-title text-center"><a href="items.php?do=show&itemid='. $item['ItemID'] .'">'. $item['Name'] .'</a></h5>';
                            echo '<p class="item-owner"> Advertise By: '. $item['Owner'] .'</p>';
                            echo '<p class="card-text">'. $item['Description'] .'</p>';
                            echo '<div class="date">'. $item['AddDate'] .'</div>';
                            if(! empty( $item['Tags'])){
                                echo "<span>Tags:</span>";
                                $tags = explode("," , $item['Tags']);
                                foreach($tags as $tag){
                                    echo "<a href='tags.php?tagname=". str_replace(" ", "" , $tag) ."'>". $tag ."</a> | ";
                                }
                             }

                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }else{
            echo "<div class='empty-msg col-10'> There're no Items to show </div>";
        }
        ?>
    </div>
</div>
<?php
}else{
    echo "<div class='container empty-msg col-10 mt-3'> There're no Items to show </div>";
}
include $tmpls . "footer.inc.php"; 
ob_end_flush(); ?>
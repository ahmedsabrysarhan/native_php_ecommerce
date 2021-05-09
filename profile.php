<?php
ob_start(); 
session_start();
$title = "My Proile";
include "init.php";
// check for swssion user in $_SESSION
if(isset($_SESSION['user'])){ 
    $check = userStatus($sessionUser); // => Check if user activated or not  
    // Check == 0 => The user is activated
    if($check == 0 ) { 
        //get information from database 
        $stmtUser = $connect->prepare("SELECT * FROM users WHERE UserName = ? ");
        $stmtUser->execute(array($sessionUser)); 
        $info = $stmtUser->fetch();
        ?>
        <h1 class="text-center"> My Profile </h1>

        <!-- Show User information -->
        <div class="information">
            <div class="container">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        My Information
                    </div>
                    <div class="card-body">
                        <ul class="list list-unstyled">
                            <li>
                                <i class="fa fa-unlock-alt fa-fw"></i>
                                <span>Login Name </span>: <?php echo $info['UserName']; ?> </li>
                            <li>
                                <i class="fa fa-user fa-fw"></i>
                                <span>Full Name </span> : <?php echo $info['FullName']; ?> </li>
                            <li>
                                <i class="fa fa-envelope-o fa-fw"></i>
                                <span>Email </span> : <?php echo $info['Email']; ?> </li>
                            <li>
                               <i class="fa fa-calendar fa-fw"></i>
                                <span>Status </span> : <?php if( $info['RegisterStatus']== 1 ) {echo 'Activate'; }else{echo 'Not Activate'; } ?> </li>
                            <li>
                                <i class="fa fa-tags fa-fw"></i>
                                <span>Favorite Category </span> : </li>
                        </ul>
                        <a href="#" class="btn btn-primary mt-3"> Edit Profile </a>
                    </div>
                </div>    
            </div>
        </div>

        <!-- Show User Ads  -->
        <div class="ads mt-3" id="my-ads">
            <div class="container">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                       Latest Advertisements
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php 
                                $items = getItems('User_ID', $info['UserID']);
                                if(! empty($items)){
                                    foreach($items as $item){
                                        echo "<div class='col-sm-6 col-md-3 mb-2'>";
                                            echo '<div class="card item-content-pro">';
                                                echo '<span class="item-price">$'. $item['Price'] .'</span>';
                                                if(! empty($item['Image'])){
                                                    echo "<img src='upload\items\\" .$item['Image']. "' class='img-responsive card-img-top' alt='item'>";
                                                }else{
                                                    echo '<img src="upload\items\\1.jpg" class="img-responsive card-img-top" alt="item">';
                                                }                                                
                                                echo '<div class="card-body">';
                                                    echo '<h5 class="card-title text-center"><a href="items.php?do=show&itemid=' . $item['ItemID'] . '">'. $item['Name'] .'</a></h5>';
                                                    echo '<p class="card-text mb-2">'. $item['Description'] .'</p>';
                                                    echo '<div class="date">'. $item['AddDate'] .'</div>';
                                                    if(! empty( $item['Tags'])){
                                                        echo "<span>Tags:</span>";
                                                        $tags = explode("," , $item['Tags']);
                                                        foreach($tags as $tag){
                                                            echo "<a href='tags.php?do=show&tagname=". str_replace(" ", "" , $tag) ."'>". $tag ."</a> | ";
                                                        }
                                                     }
                                                    if($item['Approval'] == 0 ){echo "<span class='active-status'>Waiting Activate</span>"; }
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                        }
                                }else{ echo "There is no recent advertisements <a href='ads.php'> New Ad </a> "; }
                            ?>
                        </div>
                    </div>
                </div>    
            </div>
        </div>

        <!-- Show user Comments -->
        <div class="comments mt-3">
            <div class="container">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        Latest Comments
                    </div>
                    <div class="card-body">
                        <?php
                            $stmt = $connect->prepare("SELECT Comment FROM comments WHERE UserID = ? ");
                            $stmt->execute(array($info['UserID']));
                            $comments = $stmt->fetchAll();
                            if(! empty($comments)){
                                echo "<ul class='list list-unstyled'>";
                                    foreach($comments as $comment){
                                        echo "<li>";
                                            echo "<i class='fa fa-comment-o fa-fw'></i>";
                                            echo  $comment['Comment'];
                                        echo "</li>";
                                    }
                                echo "</ul>";
                            }else{
                                echo "There's no comments to show ";
                            }
                        ?>
                    </div>
                </div>    
            </div>
        </div>

    <?php
    }else {
        echo "<div class='container alert alert-danger mt-3'> Welcome " . $sessionUser . " To Your Profile, Your Account Is Waiting For         Activate </div>";
    }
}else{
    header("location:login.php");
}
include $tmpls . "footer.inc.php"; 
ob_end_flush();
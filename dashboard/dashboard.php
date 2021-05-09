<?php 
ob_start();
    session_start();
    $title = 'Dashboard';
    if(isset($_SESSION['Admin'])){
        include "init.php"; 

        $limitMember = 5;   // => Latest 5 members registered 
        $latestMembers = latestItem("*", "users", "UserID", $limitMember);  // => Latest member function and fetchAll data 

        $limitItems = 5; 
        $latestItems = latestItem("*" , "items", "ItemID" , $limitItems);
?>
        <div class="container statestics text-center">
            <h1 class="text-center">Dashboard Page</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php?do=manage"><?php echo countItems("UserID" , "users"); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                       <div class="info">
                            Pending Members
                            <span><a href="members.php?do=manage&page=pending"> <?php echo checkItem('RegisterStatus', 'users', 0 );?> </a></span>
                       </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tags"></i>
                       <div class="info">
                            Total Items
                            <span><a href="items.php?do=manage"><?php echo countItems('ItemID' , 'items');?></a></span>
                       </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php?do=manage"><?php echo countItems("CommID" , "comments") ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest mt-5">
            <div class="row">
                <div class="col-sm-6 users">
                    <div class="card ">
                        <div class="card-header">
                            <i class="fa fa-users"></i>
                            Latset <?php echo $limitMember?> Registered Users
                            <span class="toggle-show pull-right"><i class="fa fa-plus fa-lg"></i></span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                            <?php
                                if(! empty($latestMembers) ){
                                    foreach($latestMembers as $user){
                                            echo "<li>";
                                                echo $user['UserName'];
                                                echo "<a href='members.php?do=edit&userid=" . $user['UserID'] . "' class='btn btn-success pull-right'><i class='fa fa-edit'></i> Edit</a>"; 
                                                if($user['RegisterStatus'] == 0 ){
                                                    echo "<a href='members.php?do=activate&userid=" . $user['UserID'] . "' class='btn btn-info pull-right mr-2'><i class='fa fa-link'> Activate</i></a>";
                                                }
                                            echo"</li>";
                                        }
                                }else{
                                    echo "There Is No Recorded Members";
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 items">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-tags"></i>
                            Latset Items
                            <span class="toggle-show pull-right"><i class="fa fa-plus fa-lg"></i></span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-items">
                            <?php
                                if(! empty($latestItems)){
                                    foreach($latestItems as $item){
                                        echo "<li>";
                                            echo $item['Name'];
                                            echo "<a href='items.php?do=edit&id=" . $item['ItemID'] . "' class='btn btn-success pull-right'><i class='fa fa-edit'></i> Edit </a>";
                                            if($item['Approval'] == 0 ){
                                                echo "<a href='items.php?do=approve&id=". $item['ItemID'] ."' class='btn btn-info pull-right mr-2'><i class='fa fa-check'></i> Approve </a>" ;
                                            }
                                        echo "</li>";
                                    }
                                }else{
                                    echo " There Is No Recent Items";
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
            <div class="col-sm-6 comments">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-comments-o"></i>
                            Latest Comments 
                            <span class="toggle-show pull-right"><i class="fa fa-plus fa-lg"></i></span>
                        </div>

                        <div class="card-body">
                            <?php
                                    $stmt = $connect->prepare("SELECT comments.* , users.UserName AS Owner FROM comments
                                                            INNER JOIN users ON comments.UserID = users.UserID ");
                                    $stmt->execute();
                                    $comments = $stmt->fetchAll();
                                    foreach($comments as $comment){
                                        echo "<div class='comment-box'>";
                                            echo "<a class='comment-owner' href='members.php?do=edit&userid=". $comment['UserID'] ."'>" . $comment['Owner'] . "</a>"; 
                                            echo "<p class='comment-com'>" . $comment['Comment'] . "</p>";
                                            echo "<span class='btns pull-right'>";
                                                echo"<a href='comments.php?do=edit&id=". $comment['CommID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
                                                echo"<a href='comments.php?do=delete&id=". $comment['CommID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                                if($comment['Approval'] == 0 ){
                                                    echo"<a href='comments.php?do=approve&id=". $comment['CommID'] ."' class='mt-1 btn btn-primary'><i class='fa fa-link'></i> Approve</a>";
                                                }
                                            echo "</span>";
                                        echo "</div>";
                                    }
                                ?>
                            </div>
                    </div>
                </div>

            </div>
        </div>

        <?php 
        include $tmpls . "footer.inc.php"; 
    }else {
        header('location:index.php');
        exit();
    }

ob_end_flush();
    
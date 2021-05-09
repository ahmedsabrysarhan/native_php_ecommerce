<?php
ob_start();
session_start();
$title = 'Comments';
if(isset($_SESSION['Admin'])){
    include('init.php');
        // Manage Comments Page 
        if(isset($_GET['do']) && $_GET['do'] == 'manage'){
            echo "<h1 class='text-center'> Comments Manager </h1>";
            $stmt = $connect->prepare("SELECT comments.* , users.UserName AS User , items.Name AS Item FROM comments
                                       INNER JOIN users ON users.UserID = comments.UserID
                                       INNER JOIN items ON items.ItemID = comments.ItemID");
            $stmt->execute();
            $comments = $stmt->fetchAll(); ?>
            <div class="container">
                <div class="table-responsive">
                    <table class="table main-table table-bordered text-center">
                        <tr>
                            <td>ID</td>
                            <td style="width:370px;">Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach( $comments as $comment ){
                            echo "<tr>";
                                echo "<td>" . $comment['CommID'] . "</td>";
                                echo "<td>" . $comment['Comment'] . "</td>";
                                echo "<td>" . $comment['Item'] . "</td>";
                                echo "<td>" . $comment['User'] . "</td>";
                                echo "<td>" . $comment['Date'] . "</td>";
                                echo "<td>";
                                    echo"<a href='?do=edit&id=". $comment['CommID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
                                    echo"<a href='?do=delete&id=". $comment['CommID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if($comment['Approval'] == 0 ){
                                        echo"<a href='?do=approve&id=". $comment['CommID'] ."' class='mt-1 btn btn-primary'><i class='fa fa-link'></i> Approve</a>";
                                    }
                                echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

        <?php
        // Edit Comments Page 
        }elseif(isset($_GET['do']) && $_GET['do'] == 'edit'){
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $comID = $_GET['id'];
                $stmt = $connect->prepare("SELECT * FROM comments WHERE CommID = ? ");
                $stmt->execute(array($comID));
                $comment = $stmt->fetch();
                $count = $stmt->rowCount();
                if($count > 0 ){?>
                    <h1 class="text-center">Edit Comment Page</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=update" method="POST">
                            <div class="row form-group">
                                <label class="col-sm-2 control-label"> Comment </label>
                                <div class=" col-md-6 col-sm-9 ">
                                  <textarea name="newComment" class="form-control"><?php echo $comment['Comment'];?></textarea>
                                </div>
                                <input type="hidden" name="ID" value="<?php echo $comment['CommID']; ?>">
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-sm-2"></label>
                                <input type="submit" value=" Save " class="btn btn-primary form-control col-md-2" >
                            </div>
                        </form>
                    </div>
                <?php
                }else{
                    $msg = "<div class='container alert alert-danger mt-3'> SORRY! There is No ID like That </div>";
                    Redirect($msg , 'back');
                }
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! you can't be here directly </div>";
                Redirect($msg , 'back');
            } 

        // UPDATE Comment Page 
        }elseif(isset($_GET['do']) && $_GET['do'] == 'update'){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $commID     = $_POST['ID'];
                $comment    = $_POST['newComment'];
                if(!empty($comment)){
                    $stmt = $connect->prepare("UPDATE comments SET Comment = ? WHERE CommID = ?");
                    $stmt->execute(array($comment , $commID));

                    $msg = "<div class='container alert alert-success mt-3'>". $stmt->rowCount() ." Row Updated </div>";
                    Redirect($msg , 'back'); 
                }else{
                    echo "<div class='container alert alert-danger mt-3'> SORRY! The Comment Can't be Empty </div>";
                }
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! you can't be here directly </div>";
                Redirect($msg , 'back'); 
            }

        // Approve Comment Page 
        }elseif(isset($_GET['do']) && $_GET['do'] == 'approve'){
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $commID = $_GET['id'];
                $check = checkItem('CommID' , 'comments' , $commID);
                if($check > 0 ){
                    $stmt = $connect->prepare("UPDATE comments SET Approval = 1 WHERE CommID = $commID ");
                    $stmt->execute();
    
                    $msg = "<div class='container alert alert-success mt-3'>". $stmt->rowCount() ." Row Approved </div>";
                    Redirect($msg , 'back');
                }else{
                    $msg = "<div class='container alert alert-danger mt-3'> SORRY! There is No ID like That </div>";
                    Redirect($msg , 'back');
                }
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! There is No ID like That </div>";
                Redirect($msg , 'back');
            }

        // DELETE PAGE 
        }elseif(isset($_GET['do']) && $_GET['do'] == 'delete'){
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $commID = $_GET['id'];

                $check = checkItem('CommID' , 'comments' , $commID);
                if($check > 0 ){
                    $stmt = $connect->prepare("DELETE FROM comments WHERE CommID = ? ");
                    $stmt->execute(array($commID));
    
                    $msg = "<div class='container alert alert-success mt-3'>". $stmt->rowCount() ." Row Deleted </div>";
                    Redirect($msg , 'back');
                }else{
                    $msg = "<div class='container alert alert-danger mt-3'> SORRY! There is No ID like That </div>";
                    Redirect($msg , 'back');
                }
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! There is No ID like That </div>";
                Redirect($msg , 'back');
            }


        }else{
            $msg = "<div class='container alert alert-danger mt-3'> Sorry You can't Enter to This Page </div>";
            Redirect($msg , "back");
        }
    include($tmpls."footer.inc.php");
}else{
    echo " You can't be HERE ";
}

ob_end_flush();
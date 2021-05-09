<?php 
ob_start();
session_start();
$title = "Show Item";
include("init.php");
// Get items from database 
if(isset($_GET['itemid']) && is_numeric($_GET['itemid'])){
    $itemID = intval($_GET['itemid']);
}else{ $itemID = 0; }
$stmt = $connect->prepare(" SELECT items.*, categories.Name AS category , users.UserName AS owner
                            FROM 
                                items
                            INNER JOIN 
                                categories ON categories.CatID = items.Cat_ID
                            INNER JOIN 
                                users ON users.UserID = items.User_ID
                           
                            WHERE 
                                ItemID = ? ");
$stmt->execute(array($itemID));
$item = $stmt->fetch();
$count = $stmt->rowCount();
if($count > 0 ){
    if(isset($_GET['do']) && $_GET['do'] == 'show'){ ?>
        <div class="container item-show">
            <h1 class="text-center"> <?php echo $item['Name'] ?> </h1>
            <!-- Show Item Informations  -->
            <div class="row">
                <!-- Item Image -->
                <div class="col-md-3">
                    <?php
                    if(! empty($item['Image'])){
                        echo "<img src='upload\items\\" .$item['Image']. "' class='img-responsive card-img-top' alt='item'>";
                    }else{
                        echo '<img src="upload\items\\1.jpg" class="img-responsive card-img-top" alt="item">';
                    }
                    $sessionUser =  str_replace(" ", "", $_SESSION['user']) . "<br>";
                    $itemOwner =  str_replace(" ", "", $item['owner']) . "<br>";
                    // Add Edit and delete bittons
                    if($sessionUser == $itemOwner){
                        echo "<a href='?itemid=". $item['ItemID'] ."&do=edit' class='m-3 btn btn-info'>Edit</a>";
                        echo "<a href='?do=delete&itemid=". $item['ItemID'] ."' class='m-3 btn btn-danger confirm'>Delete</a>";
                    }
                    ?>
                </div>
                <div class="col-md-9">
                    <h2> <?php echo $item['Name'] ?> </h2>

                    <p class="m-0"><?php echo $item['Description'] ?></p>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Date:</span><?php echo $item['AddDate'] ?></li>
                        <li>
                            <i class="fa fa-money fa-fw"></i>
                            <span>Price: $</span><?php echo $item['Price'] ?> </li>
                        <li>
                        <i class="fa fa-building fa-fw"></i>
                            <span>Made In:</span> <?php echo $item['CountryMade'] ?></li>
                        <li>
                            <i class="fa fa-clone fa-fw"></i>
                            <span>Status: </span><?php echo $item['Status'] ?> </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Added By:</span><a href="#"> <?php echo $item['owner'] ?></a></li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category:</span><a href="categories.php?catid=<?php echo $item['Cat_ID'] ?>&cat-name=<?php echo $item['category']?>"> <?php echo $item['category'] ?> </a>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Tags:</span>
                            <?php 
                                $tags = explode("," , $item['Tags']);
                                foreach($tags as $tag){
                                    echo "<a href='tags.php?tagname=". str_replace(" ", "" , $tag) ."'>". $tag ."</a> | ";
                                }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Item Informations  -->
            <!-- Start Add Comments -->
            <hr>
            <?php
                    if(isset($_SESSION['user'])){?>
                        <div class="row">
                        <div class="offset-md-3">
                            <div class="add-comment">
                                <h5> Add your comment </h5>
                                <form action=<?php echo $_SERVER['PHP_SELF'] . "?itemid=" . $item['ItemID'] ?> method="POST">
                                    <textarea name="comment" class="form-control" placeholder="Leave your comment Here" required></textarea>
                                    <input type="submit" value="Add Comment" class="btn btn-primary">
                                </form>
                                <?php 
                                if($_SERVER['REQUEST_METHOD'] == "POST"){
                                    if(! empty($_POST['comment'])){
                                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                        $userid = $item['User_ID'];
                                        $itemid = $item['ItemID'] ;

                                        $stmt = $connect->prepare(" INSERT INTO 
                                                                    comments(Comment, Date, Approval, UserID, ItemID)
                                                                    VALUES(:zcomment, now(), 0 , :zuserid, :zitemid) ");
                                        $stmt->execute(array(
                                            "zcomment"  => $comment,
                                            "zuserid"   => $_SESSION['userID'],
                                            "zitemid"   =>$itemid
                                        ));

                                        if($stmt){
                                            echo "<div class='alert alert-success mt-3'> Your comment is added </div>";
                                        }
                                    }else{
                                        echo "<div class='alert alert-danger mt-3'> There is no comment to add </div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                    }else{
                        echo "Please<a href='login.php'> Login </a>or<a href='login.php'> Register </a>to comment";
                    }
            ?>
            <hr>
            <!-- End Add Comments -->
            <!-- Show Commemts -->
            <?php
                $stmt = $connect->prepare(" SELECT comments.*, users.UserName AS member
                                            FROM comments 
                                            INNER JOIN users ON users.UserID = comments.UserID
                                            WHERE ItemID = ? 
                                            AND Approval = 1
                                            ORDER BY CommID DESC ");
                $stmt->execute(array($item['ItemID']));
                $comments = $stmt->fetchAll(); 
            ?>
            <?php
                foreach($comments as $comment){?>
                <div class="comment-box">
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <img class="img-responsive img-thumbnail d-flex rounded-circle" src="https://placehold.it/200" alt="member_img" >
                            <?php echo $comment['member']; ?>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead "><?php echo $comment['Comment']; ?></p>
                        </div>
                    </div>
                    <hr>
                </div>
            <?php } ?>
            
        </div>
    <?php
    } elseif(isset($_GET['do']) && $_GET['do'] == 'edit'){ 

        ?>
        <h1 class="text-center"><?php echo 'Edit  ' . $item['Name']; ?></h1>
        <!-- Edit Advertise  -->
        <div class="new-ad">
            <div class="container">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        <?php echo "Edit Advertise"; ?>
                    </div>
                    <div class="card-body">
                       <div class="row">
                           <!-- Edit Advertise -->
                           <div class="col-md-8">
                                <form   class="form-horizontal ml-auto"  action="?do=update&itemid=<?php echo $item['ItemID'];?>" method="POST" 
                                enctype="multipart/form-data">
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label ">Item Name</label>
                                        <input type="text" name="itemName" class="form-control col-sm-8 live" data-class=".live-title" 
                                        value ="<?php echo $item['Name']?>" required="required" autocomplete="off">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label ">Description</label>
                                        <input type="text" name="descripe" class="form-control col-sm-8 live" data-class=".live-desc"  
                                        value="<?php echo $item['Description']?>" required="required">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label ">Price</label>
                                        <input type="text" name="price" data-class=".live-price" class="form-control col-sm-8 live"
                                        value="<?php echo $item['Price']?>"  required="required" autocomplete="off" >
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Country</label>
                                        <input type="text" name="country" class="form-control col-sm-8" value="<?php echo $item['CountryMade']?>" required="required">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Image</label>
                                        <input type="file" name="image" class="form-control col-sm-8 live" data-class=".live-image">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Tags</label>
                                        <input type="text" name="tags" class="form-control col-sm-9 col-md-6" 
                                        value="<?php echo $item['Tags']?>" data-role="tagsinput">
                                    </div>
                            
                                    <!-- Add Category from categories table -->
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Category</label>
                                        <?php  
                                            $stmt = $connect->prepare("SELECT * FROM categories");
                                            $stmt->execute();
                                            $cats = $stmt->fetchAll();
                                            echo "<select class='form-control' name='categories'>";
                                            echo "<option value=''> .... </option>";
                                                foreach( $cats as $cat ){
                                                    echo "<option  value=" . " ' " . $cat['CatID'] . " ' " ;
                                                    if($cat['CatID'] == $item['Cat_ID']){echo 'selected';}
                                                    echo ">".  $cat['Name'] ."</option>";
                                                }
                                            echo "</select>";
                                        ?>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Status</label>
                                        <select class="form-control" name="status" >
                                            <option value= "" <?php if($item['Status'] == ""){echo 'selected';} ?> > ... </option>
                                            <option value= "New" <?php if($item['Status'] == "New"){echo 'selected';} ?>> New </option>
                                            <option value= "Like New" <?php if($item['Status'] == "Like New"){echo 'selected';} ?>> Like New </option>
                                            <option value= "Used" <?php if($item['Status'] == "Used"){echo 'selected';} ?>> Used </option>
                                            <option value= "Very Old" <?php if($item['Status'] == "Very Old"){echo 'selected';} ?>> Very Old </option>
                                        </select>
                                    </div>

                                    <div class="row form-group">
                                        <input type="submit" name="insert" class="offset-sm-3 form-control btn btn-info" value="Update Item" style="max-width:220px;">
                                    </div>
                            </form>
                        </div>

                           <!-- Live Preview -->
                            <div class="col-md-4">
                                <div class='col-12'>
                                    <div class="card item-content">
                                        <span class="item-price"> $<span class="live-price"> 0 </span> </span>
                                        <img src="upload\items\<?php if(! empty($item['Image'])){echo $item['Image'];} else{echo "1.jpg";}?>" class="card-img-top live-image" alt="item">
                                        <div class="card-body ">
                                            <h4 class="card-title text-center live-title"> Title </h4>
                                            <p class="card-text live-desc"> Description</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                       <!-- Start Looping Errors Through Form Errors -->
                       
                       <!-- End Looping Errors Through Form Errors -->
                    </div>
                </div>    
            </div>
        </div>
    <?php
    }elseif(isset($_GET['do']) &&  $_GET['do'] == "update"){
        if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST" ){

            $formErrors = array();
    
            // Advertise data
            $name       = filter_var($_POST['itemName'], FILTER_SANITIZE_STRING);
            $descripe   = filter_var($_POST['descripe'],FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT) ;
            $country    = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $tags       = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);
            $category   = filter_var($_POST['categories'],FILTER_SANITIZE_NUMBER_INT) ;
            $status     = filter_var($_POST['status'],FILTER_SANITIZE_STRING);
    
            // allowed image extentions
            $imageAllowedExtentions = array("jpeg", "png", "jpg", "gif");
    
            // image vars 
            // => Sort image data in array  
            $imageName      = $_FILES['image']['name'];
            $imageType      = $_FILES['image']['type'];
            $imageTmpName   = $_FILES['image']['tmp_name'];
            $imageSize      = $_FILES['image']['size'];
    
            $imageExtention = strtolower(end(explode("." , $imageName)));
    
    
            // Form ERRORS 
            if(strlen($name) < 4 ){
                $formErrors[] = "The item name must be more than 4 charcters";
            }
            if(strlen($descripe) < 10 ){
                $formErrors[] = "The item name must be more than 10 charcters";
            }
            if(empty($price)){
                $formErrors[] = "The price can't be empty";
            }
            if(empty($country)){
                $formErrors[] = "The country name must be exist";
            }
            if(empty($category)){
                $formErrors[] = "The category of item  must be exist";
            }
            if(empty($status)){
                $formErrors[] = "The status of item  must be exist";
            }
            if(! empty($imageName) && ! in_array($imageExtention , $imageAllowedExtentions)){
                $formErrors[] = "This Extention is not ALLOWED";
            }
            if(empty($imageName)){
                $formErrors[] = "Please Upload a product's image";
            }
            if($imageSize > inMB(4)){
                $formErrors[] = "Image can't be more than 4MB";
            }
            if(! empty($formErrors)){
                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }
            }
    
            if(empty($formErrors)){

                $ITEMID = $item['ItemID'];
    
                // set Image Name 
    
                $image = rand(0,1000000) . "_" . $imageName;
                if(isset($image)){
                    $image = $image . "_" . rand(0,1000) ;
                }
                // use temporary name to move file to upload 
                move_uploaded_file($imageTmpName , "upload\items\\" . $image );
    
                $stmt = $connect->prepare(" UPDATE items 
                                            SET Name=? , Description=? , Price=? , AddDate=now() , CountryMade=? , Image=? , Status=? , Cat_ID=? , Tags=?     
                                            WHERE ItemID = $ITEMID ");
                $stmt->execute(array( $name, $descripe, $price, $country, $image, $status, $category, $tags ));
                if($stmt){
                    $update =  "<div class='container alert alert-success mt-5'> Item Edited </div>";
                    Redirect($update , 'back');
                }else{ echo " Faild Update"; } 
            } 
        }else {
            echo "No It's Not Okay ";
        }
    }elseif(isset($_GET['do']) &&  $_GET['do'] == "delete"){
        echo "<h1 class='text-center'> Delete Item</h1>";
            if(isset($_GET['itemid']) && is_numeric($_GET['itemid'])){
                $itemID = $_GET['itemid'];
                $stmt = $connect->prepare("DELETE FROM items WHERE ItemID = ? ");
                $stmt->execute(array($itemID));

                echo "<div class='container alert alert-success mt-3'>" . $stmt->rowCount() . " Row Deleted </div>";
                header("location:index.php");
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! You can't browse this page directly </div>";
                Redirect($msg , "back", 30);
            }
    }
    
}else{
    echo "<div class='container alert alert-info mt-5'> There's No Item Withe ID ". $itemID ."</div>";
}

include $tmpls . "footer.inc.php"; 
ob_end_flush();
<?php 
ob_start();
session_start();
$title= "Items";
    if(isset($_SESSION['Admin'])){
        include("init.php");
        if( isset($_GET['do']) && $_GET['do'] == "manage"){
            // Fetch data from items table 
            $stmt = $connect->prepare("SELECT items.*, users.UserName AS Owner, categories.Name As Category FROM items
                                       INNER JOIN users ON users.UserID = items.User_ID
                                       INNER JOIN categories ON categories.CatID = items.Cat_ID ");
            $stmt->execute();
            $rows = $stmt->fetchAll();

            ?>
            <h1 class='text-center'> Items Manager</h1>
           <div class="container">
                <div class="table-responsive">
                    <table class="table main-table table-bordered text-center ">
                        <tr>
                            <td>ID</td>
                            <td>Image</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Category</td>
                            <td>Price</td>
                            <td>Status</td>
                            <td>Owner</td>
                            <td>Control</td>
                        </tr>

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>". $row['ItemID'] ."</td>";
                                    echo "<td>";
                                        if(empty($row['Image'])){
                                            echo "<img src='..\upload\items\\1.jpg' class='item-image'>";
                                        }else{
                                            echo "<img src='..\upload\items\\" . $row['Image'] . "' class='item-image'>";
                                        }
                                    echo "</td>";
                                    echo "<td>". $row['Name'] ."</td>";
                                    echo "<td><p  style='width:270px;' >". $row['Description'] ."</p></td>";
                                    echo "<td>". $row['Category'] ."</td>";
                                    echo "<td>$". $row['Price'] ."</td>";
                                    echo "<td>". $row['Status'] ."</td>";
                                    echo "<td>". $row['Owner'] ."</td>";
                                    echo "<td>";
                                    if($row['Approval'] == 0 ){
                                        echo "<a href='?do=approve&id=". $row['ItemID'] ."' class='btn btn-info'><i class='fa fa-check'></i> Approve </a>" ;}
                                        echo "<a href='?do=edit&id=". $row['ItemID'] ."' class='btn btn-success'><i class='fa fa-edit'></i>
                                        Edit </a>";
                                        echo "<a href='?do=delete&id=". $row['ItemID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                    <a href="?do=add" class="btn btn-info"><i class="fa fa-plus"></i> Add New Item </a>
                </div>
           </div>
        <?php
        // Add Item Page 
        }elseif(isset($_GET['do']) &&  $_GET['do'] == "add"){?>
            <h1 class='text-center'> Add New Item</h1>
            <div class="container">
                <form class="form-horizontal ml-auto"  action="?do=insert" method="POST" enctype="multipart/form-data">
                    <!-- Add Name -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Item Name</label>
                        <input type="text" name="itemName" class="form-control col-sm-9 col-md-6" placeholder="Please Insert Item Name"  required="required" autocomplete="off">
                    </div>
                    <!-- Add Image -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Image</label>
                        <input type="file" name="image" class="form-control col-sm-9 col-md-6" required="required">
                    </div>
                    <!-- Add Description -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Description</label>
                        <input type="text" name="descripe" class="form-control col-sm-9 col-md-6" placeholder="Please Descripe your product" required="required">
                    </div>
                    <!-- Add Price -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Price</label>
                        <input type="text" name="price" class="form-control col-sm-9 col-md-6" placeholder="The Item price in $"  required="required" autocomplete="off" >
                    </div>
                    <!-- Add Country -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Country</label>
                        <input type="text" name="country" class="form-control col-sm-9 col-md-6" placeholder="The Item country" required="required">
                    </div>
                    <!-- Add Tags -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Tags</label>
                        <input type="text" name="tags" class="form-control col-sm-9 col-md-6" placeholder="Seprate between tags by Comma ( , )" data-role="tagsinput">
                    </div>
                    <!-- Add Members from users table -->
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Member</label>
                        <?php  
                            $stmt = $connect->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            echo "<select class='form-control' name='member'>";
                                echo "<option value=''> .... </option>";
                                foreach( $users as $user ){
                                    echo "<option value=" . $user['UserID'] . " >" . $user['UserName'] . "</option>";
                                }
                            echo "</select>";
                        ?>
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
                                    echo "<option  value=" . $cat['CatID'] ." >" . $cat['Name'] . "</option>";
                                    // Show Childs categories 
                                    $childs = getAllFrom("*", "categories", "WHERE Parent = {$cat['CatID']}","","CatID");
                                    foreach($childs as $child){
                                        echo "<option value=". $child['CatID'] .">-- ". $child['Name'] ."</option>";
                                    }
                                }
                            echo "</select>";
                        ?>
                    </div>

                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <select class="form-control" name="status" >
                            <option value= ""> ... </option>
                            <option value= "New"> New </option>
                            <option value= "Like New"> Like New </option>
                            <option value= "Used"> Used </option>
                            <option value= "Very Old"> Very Old </option>
                        </select>
                    </div>

                    <div class="row form-group">
                        <label class="col-sm-3"></label>
                        <input type="submit" name="insert" class="form-control col-sm-9 col-md-2 btn btn-info" value="Add New Item">
                    </div>
                
                </form>
            </div>
        <?php    
        // INSERT PAGE 
        }elseif(isset($_GET['do']) &&  $_GET['do'] == "insert"){
            if( $_SERVER['REQUEST_METHOD'] == "POST" ){
                $name       = $_POST['itemName'];
                $descripe   = $_POST['descripe'];
                $price      = $_POST['price'];
                $country    = $_POST['country'];
                $tags       = $_POST['tags'];
                $member     = $_POST['member'];
                $cat        = $_POST['categories'];
                $status     = $_POST['status'];

                // Image 
                $allowedExt = array("png", "jpg", "jpeg", "gif");

                $imgName    = $_FILES['image']['name'];
                $imgSize    = $_FILES['image']['size'];
                $imgTmpName = $_FILES['image']['tmp_name'];
                $imgType    = $_FILES['image']['type'];
                $ext        = explode(".", $imgName);
                $imgExt     = strtolower(end($ext));

                // check Errors 
                $emptyErrors = array();
                if(empty($name)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item descripe can't be an EMPTY </div>";
                }if(empty($descripe)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item Description can't be an EMPTY </div>";
                }if(empty($price)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item Price can't be an EMPTY </div>";
                }if(empty($country)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Country can't be an EMPTY </div>";
                }if(empty($member)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Member must be selected </div>";
                }if(empty($cat)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Category must be selected </div>";
                }if(empty($status)){
                    $emptyErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Status must be selected </div>";
                } 
                if(! empty($imgName) && ! in_array($imgExt , $allowedExt)){
                    $formErrors[] = "This Extention is not ALLOWED";
                }
                // Check impty image
                if(empty($imgName)){
                    $formErrors[] = "Please Upload a product's image";
                }
                // Check Image Size 
                if($imgSize > inMB(4)){
                    $formErrors[] = "Image can't be more than 4MB";
                }
                
                // Check Errors
                foreach($emptyErrors as $error ){
                        echo "<div class='container alert alert-danger mt-5'>" . $error . "</div>";
                }
                
                if( empty($emptyErrors) ){
                    $img = rand(0,100000000) . $imgName ;
                    move_uploaded_file($imgTmpName , "..\upload\items\\" . $img);

                    $stmt = $connect->prepare("INSERT INTO items(Name, Image, Description, Price, CountryMade, Status, Approval, Cat_ID, User_ID , 
                                                                AddDate, Tags)
                                            VALUES(:iname, :iimage, :idescripe, :iprice, :icountry, :istatus, 1 , :icat, :imember, now(), :itags)");
                                            // Add Approval As 1 Because I Added It from Admin Page 
                    $stmt->execute(array(
                        "iname"     => $name,
                        "iimage"    => $img,
                        "idescripe" => $descripe,
                        "iprice"    => $price,
                        "icountry"  => $country,
                        "istatus"   => $status,
                        "icat"      => $cat,
                        "imember"   => $member,
                        "itags"     => $tags,
                    ));

                    $count = $stmt->rowCount();
                    $msg = "<div class='container alert alert-success mt-3'> " . $count . " Item Added </div>";
                    Redirect($msg , 'back');

                }else{
                    Redirect( "", 'back' , 5);
                }

            }else{
                $msg= "<div class='container alert alert-danger mt-3'> SORRY! you are not Allowed to be HERE </div>";
                Redirect($msg , 'back');
            }
        
        // EDIT PAGE 
        }elseif(isset($_GET['do']) &&  $_GET['do'] == "edit"){ 
            
        if( isset($_GET['id']) && is_numeric($_GET['id']) ){
            $itemID = $_GET['id'];
            $stmt = $connect->prepare("SELECT * FROM items WHERE ItemID = ? ");
            $stmt->execute(array($itemID));
            $row = $stmt->fetch();

        }else{
            $msg= "<div class='container alert alert-danger mt-3'> SORRY! you'r can't be here </div>";
            Redirect($msg , 'back');
        }
        ?>
        <h1 class="text-center">Edit Page</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=update" method="POST" enctype="multipart/form-data">
                <div class="row form-group">
                    <label class="control-label col-md-3">Name</label>
                    <input type="hidden" name="ID" value="<?php echo $row['ItemID']; ?>" >
                    <input class="form-control col-md-6" type="text" name="newName" value="<?php echo $row['Name'] ?>" required="required">
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Image</label>
                    <input type="file" name='image' class="form-control col-md-6">
                    <?php
                    if(empty($row['Image'])){
                        echo "<img src='..\upload\items\\1.jpg' class='item-image ml-3'>";
                    }else{
                        echo "<img src='..\upload\items\\". $row['Image'] ."' class='item-image ml-3'>";
                    }
                    ?>
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Description</label>
                    <input class="form-control col-md-6" type="text" name='description' value="<?php echo $row['Description'] ?>" >
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Price</label>
                    <input class="form-control col-md-6" type="text" name="price" value="<?php echo $row['Price'] ?>">
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Country</label>
                    <input class="form-control col-md-6" type="text" name="country" value="<?php echo $row['CountryMade'] ?>">
                </div>

                <div class="row form-group">
                    <label class="col-sm-3 control-label">Tags</label>
                    <input type="text" name="tags" class="form-control col-sm-9 col-md-6" value="<?php echo $row['Tags'] ?>" data-role="tagsinput">
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Member</label>
                    <?php
                    $stmtUser = $connect->prepare("SELECT * FROM users ");
                    $stmtUser->execute();
                    $users =  $stmtUser->fetchAll();
                       echo "<select class='form-control' name='owner'>";
                            foreach($users as $user){
                            echo "<option  value='" . $user['UserID'] . "' ";
                                if($user['UserID'] == $row['User_ID']){echo 'selected';}
                            echo ">" .  $user['UserName'] . "</option>";
                            }
                       echo "</select>";
                    ?>
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Category</label>
                    <select class="form-control" name="cat">
                    <?php
                    $stmtCat = $connect->prepare("SELECT * FROM categories WHERE Parent = 0");
                    $stmtCat->execute();
                    $Cats = $stmtCat->fetchAll();
                    foreach($Cats as $cat){
                        echo "<option value='" . $cat['CatID'] . "' ";
                            if($cat['CatID'] == $row['Cat_ID']){echo 'selected';}
                        echo ">" . $cat['Name'] . "</option>";
                    }
                    ?>
                    </select>
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3">Status</label>
                    <select class="form-control" name="status">
                        <option value="New"      <?php if ($row['Status'] == "New") echo "selected"; ?>> New </option>
                        <option value="Like New" <?php if ($row['Status'] == "Like New") echo "selected"; ?>> Like New </option>
                        <option value="Used"     <?php if ($row['Status'] == "Used") echo "selected"; ?>> Used </option>
                        <option value="Very Old" <?php if ($row['Status'] == "Very Old") echo "selected"; ?>> Very Old </option>
                    </select>
                </div>

                <div class="row form-group">
                    <label class="control-label col-md-3"></label>
                    <input class="form-control col-md-2 btn btn-info" type="submit" value="Updat Item">
                </div>

            </form>
        </div>
        <?php

        //INSERT COMMENTS IN EDIT ITEMS 

        $stmt = $connect->prepare("SELECT comments.* , users.UserName AS User FROM comments
                                   INNER JOIN users ON users.UserID = comments.UserID
                                   WHERE ItemID = ? ");
        $stmt->execute(array( $row['ItemID'] ));
        $comments = $stmt->fetchAll();
        if(! empty($comments)){ ?>
            <h1 class='text-center'> Comments <?php echo $row['Name'];?>  Manager </h1>
            <div class="container">
            <div class="table-responsive">
                <table class="table main-table table-bordered text-center">
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach( $comments as $comment ){
                        echo "<tr>";
                            echo "<td>" . $comment['CommID'] . "</td>";
                            echo "<td>" . $comment['Comment'] . "</td>";
                            echo "<td>" . $comment['User'] . "</td>";
                            echo "<td>" . $comment['Date'] . "</td>";
                            echo "<td>";
                                echo"<a href='comments.php?do=edit&id=". $comment['CommID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
                                echo"<a href='comments.php?do=delete&id=". $comment['CommID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                if($comment['Approval'] == 0 ){
                                    echo"<a href='comments.php?do=approve&id=". $comment['CommID'] ."' class='mt-1 btn btn-primary'><i class='fa fa-link'></i> Approve</a>";
                                }
                            echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        
        <?php
        }
    
        // Update PAGE 
        }elseif(isset($_GET['do']) &&  $_GET['do'] == "update"){
            echo "<h1 class='text-center'> Update Item</h1>";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $ID         = $_POST['ID'];
                $name       = $_POST['newName'];
                $descripe   = $_POST['description'];
                $price      = $_POST['price'];
                $country    = $_POST['country'];
                $tag        = $_POST['tags'];
                $owner      = $_POST['owner'];
                $category   = $_POST['cat'];
                $status     = $_POST['status'];
                // Image
                $allowedExt = array("png", "jpg", "jpeg", "gif"); 
                $imgName    = $_FILES['image']['name'];
                $imgSize    = $_FILES['image']['size'];
                $imgTmpName = $_FILES['image']['tmp_name'];
                $imgtype    = $_FILES['image']['type'];
                $exp        = explode(".", $imgName);
                $imgExt     = strtolower(end($exp));
                
                // Check empty ITEMS 
                $formErrors = array();
                if(empty($name)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item Name can't be Empty </div>";
                }
                if(empty($descripe)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item descripe can't be Empty </div>";
                }
                if(empty($price)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item Price can't be Empty </div>";
                }
                if(empty($country)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item country can't be Empty </div>";
                }
                if(empty($owner)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item owner can't be Empty </div>";
                }
                if(empty($category)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item category can't be Empty </div>";
                }
                if(empty($status)){
                    $formErrors[] = "<div class='container alert alert-danger mt-3'> SORRY! Item status can't be Empty </div>";
                }
                if(! empty($imgName) && ! in_array($imgExt , $allowedExt)){
                    $formErrors[] = "This Extention is not ALLOWED";
                }
                // Check impty image
                if(empty($imgName)){
                    $formErrors[] = "Please Upload a product's image";
                }
                // Check Image Size 
                if($imgSize > inMB(4)){
                    $formErrors[] = "Image can't be more than 4MB";
                }

                // Check Errors
                foreach($formErrors as $error){
                    echo $error ; 
                }
                if(empty($formErrors)){
                    $image = rand(0,10000000) . $imgName;
                    move_uploaded_file($imgTmpName , "..\upload\items\\". $image );
                    $stmtUpdate = $connect->prepare("UPDATE items
                                                     SET Name =?, Image=?, Description =?, Price =?, CountryMade =?, Tags = ?, Status =?, Cat_ID =?,  User_ID =?
                                                     WHERE ItemID = ?");
                    $stmtUpdate->execute(array( $name, $image, $descripe, $price, $country, $tag, $status, $category ,$owner, $ID ));
                    $count = $stmtUpdate->rowCount();

                    $msg = "<div class='container alert alert-success mt-3'>" . $count . " Row changed </div>";
                    Redirect($msg , "back");

                }else{
                    $msg = "<div class='container alert alert-danger mt-3'> SORRY! Check your data before editing </div>";
                    Redirect($msg , "back");
                }
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! You can't browse this page directly </div>";
                Redirect($msg , "back");
            }
            
        // DELETE PAGE 
        }elseif(isset($_GET['do']) &&  $_GET['do'] == "delete"){
            echo "<h1 class='text-center'> Delete Item</h1>";
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $itemID = $_GET['id'];
                $stmt = $connect->prepare("DELETE FROM items WHERE ItemID = ? ");
                $stmt->execute(array($itemID));

                $msg = "<div class='container alert alert-success mt-3'>" . $stmt->rowCount() . " Row Deleted </div>";
                Redirect($msg , "back");
            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! You can't browse this page directly </div>";
                Redirect($msg , "back");
            }
            
        // approved Items    
        }elseif(isset($_GET['do']) &&  $_GET['do'] == "approve"){
            echo "<h1 class='text-center'> Approving Item</h1>";
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $itemID = $_GET['id'];
                $check = checkItem('ItemID', 'items' , $itemID);
                if($check > 0 ){
                    $stmt = $connect->prepare("UPDATE items SET Approval = 1 WHERE ItemID = ? ");
                    $stmt->execute(array($itemID));

                    $msg = "<div class='container alert alert-success mt-3'>" . $stmt->rowCount() . " Row Approved </div>";
                    Redirect($msg , "back");
                }else{
                    $msg = "<div class='container alert alert-danger mt-3'> There's No Item Like That </div>";
                    Redirect($msg , "back");
                }

            }else{
                $msg = "<div class='container alert alert-danger mt-3'> SORRY! You can't browse this page directly </div>";
                Redirect($msg , "back");
            }
       
        }else{
            $msg = "<div class='container alert alert-danger mt-3'> SORRY! You can't browse this page directly </div>";
            Redirect($msg , "back");
        }
        
        include($tmpls . "footer.inc.php");
    }else{
        header("location:index.php");
        exit();
    }
ob_end_flush();
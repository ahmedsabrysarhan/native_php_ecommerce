<?php
ob_start(); 
session_start();
$title = "Create New Item";
include "init.php";
// check for swssion user in $_SESSION
if(isset($_SESSION['user'])){ 
    $check = userStatus($sessionUser); // => Check if user activated or not  
    // Check == 0 => The user is activated
    if($check == 0 ) { 

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

            if(empty($formErrors)){

                // set Image Name 

                $image = rand(0,1000000) . "_" . $imageName;
                if(isset($image)){
                    $image = $image . "_" . rand(0,1000) ;
                }
                // use temporary name to move file to upload 
                move_uploaded_file($imageTmpName , "upload\items\\" . $image );

                $stmt = $connect->prepare("INSERT INTO 
                                            items (Name, Description, Price, AddDate, CountryMade, Image, Status, Cat_ID, User_ID, Tags)
                                            VALUES(:iname, :idesc, :iprice, now(), :icountry, :iimage, :istat, :icategoty, :iuser, :itags ) ");
                $stmt->execute(array(
                    "iname"     => $name,
                    "idesc"     => $descripe,
                    "iprice"    => $price,
                    "icountry"  => $country,
                    "iimage"    => $image,
                    "istat"     => $status,
                    "icategoty" => $category,
                    "iuser"     => $_SESSION['userID'],
                    "itags"     => $tags

                ));
                if($stmt){
                    $itemAdd = "<div class='alert alert-success'> Item Added </div>";
                } 
            } 
        }

        ?>
        
        <h1 class="text-center"><?php echo $title; ?></h1>

        <!-- Add New Advertise  -->
        <div class="new-ad">
            <div class="container">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        <?php echo $title; ?>
                    </div>
                    <div class="card-body">
                       <div class="row">
                           <!-- Add New Advertise -->
                           <div class="col-md-8">

                            <form   class="form-horizontal ml-auto"  action=<?php echo $_SERVER['PHP_SELF'] ?> method="POST" 
                                    enctype="multipart/form-data">
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label ">Item Name</label>
                                        <input type="text" name="itemName" class="form-control col-sm-8 live" data-class=".live-title" placeholder="Please Insert Item Name"  required="required" autocomplete="off">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label ">Description</label>
                                        <input type="text" name="descripe" class="form-control col-sm-8 live" data-class=".live-desc"  placeholder="Please Descripe your product" required="required">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label ">Price</label>
                                        <input type="text" name="price" data-class=".live-price" class="form-control col-sm-8 live" placeholder="The Item price in $"  required="required" autocomplete="off" >
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Country</label>
                                        <input type="text" name="country" class="form-control col-sm-8" placeholder="The Item country" required="required">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Image</label>
                                        <input type="file" name="image" class="form-control col-sm-8">
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">Tags</label>
                                        <input type="text" name="tags" class="form-control col-sm-9 col-md-6" placeholder="Seprate between tags by Comma ( , )" data-role="tagsinput">
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
                                        <input type="submit" name="insert" class="form-control btn btn-info" value="Add New Item" style="max-width:220px;">
                                    </div>

                                </form>

                           </div>

                           <!-- Live Preview -->
                            <div class="col-md-4">
                                <div class='col-12'>
                                    <div class="card item-content">
                                        <span class="item-price"> $<span class="live-price"> 0 </span> </span>
                                        <img src="https://placehold.it/150" class="card-img-top" alt="item">
                                        <div class="card-body">
                                            <h4 class="card-title text-center live-title"> Title </h4>
                                            <p class="card-text live-desc"> Description</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                       <!-- Start Looping Errors Through Form Errors -->
                       <?php
                            if(! empty($formErrors)){
                                foreach($formErrors as $error){
                                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                                }
                            }
                            if(isset($itemAdd)){ echo $itemAdd ; }
                       ?>
                       <!-- End Looping Errors Through Form Errors -->
                    </div>
                </div>    
            </div>
        </div>
    <?php
    // If this user is not activate 
    }else {
        echo "<div class='container alert alert-danger mt-3'> Welcome " . $sessionUser . " To Your Profile, Your Account Is Waiting For         Activate </div>";
    }
}else{
    header("location:login.php");
}
include $tmpls . "footer.inc.php"; 
ob_end_flush();
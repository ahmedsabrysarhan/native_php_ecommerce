<?php 
/*
-----------------------------------------------------------------
-----------------------------------------------------------------
Members Page => you can (EDIT | ADD | DELETE) Members From Here
-----------------------------------------------------------------
-----------------------------------------------------------------
*/
ob_start();
session_start();
$title = 'Members';

if(isset($_SESSION['Admin'])){
    include "init.php";    

    $do = isset($_GET['do']) ? $do = $_GET['do'] : 'manage';      // Return To Manage Page 
    // Check for page 
        // Manage page 
        if($do == 'manage'){ 
            // Add query to Show membres whom waiting for pending
             $query = '';
             if(isset($_GET['page']) && $_GET['page'] == 'pending'){
                $query = "AND RegisterStatus = 0";
                
             }
            // Select all members from users table 
            $stmt = $connect->prepare("SELECT * FROM users WHERE GroupId != 1 $query");
            // هتختار كل الناس ماعدا الادمن ولو جاي من ال بيندنج هاتعرض اللي مش متأكتف بس 
            
            $stmt->execute();
            $rows = $stmt->fetchAll();  // => Fetch all elements in a big var
            ?>
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="table main-table table-bordered text-center">
                        <tr>
                            <td>ID</td>
                            <td>Image</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Register Date</td>
                            <td>Control</td>
                        </tr>
                       <?php
                       
                       foreach($rows as $row){
                           echo "<tr>";
                                echo "<td>" . $row['UserID']    . "</td>";

                                if(empty($row['Image'])){
                                    echo "<td>";
                                        echo "<img src='..\upload\users\\1.jpg' alt='image' class='user-img'>";
                                    echo "</td>";
                                }else{
                                    echo "<td>";
                                        echo "<img src='..\upload\users\\". $row['Image'] ."' alt='image' class='user-img'>";
                                    echo "</td>"; 
                                }

                                echo "<td>" . $row['UserName']  . "</td>";
                                echo "<td>" . $row['Email']     . "</td>";
                                echo "<td>" . $row['FullName']  . "</td>";
                                echo "<td>"  .$row['Date']      . "</td>";
                                echo "<td>"; 
                                    echo '<a href="?do=edit&userid=' . $row["UserID"] . ' " class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</a>';
                                    echo '<a href="?do=delete&userid=' .  $row["UserID"] . ' "class="btn btn-danger confirm pull-right"><i class="fa fa-close"></i> Delete</a> ';
                                    if($row['RegisterStatus'] == 0 ){
                                        echo '<a href="?do=activate&userid=' .  $row["UserID"] . ' "class="btn btn-info pull-left"> <i class="fa fa-link"></i> Activate</a> ';
                                    }
                                echo"</td>";
                           echo "</tr>";
                       }
                       ?>
                    </table>
                </div>
            <a href ="?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member </a>
            </div>
            

        <?php
        // Edit page 
        }elseif( $do == 'edit'){
            // Check if GET Request userid IS Numeric & Integer value 
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ?intval($_GET['userid']) :'SOORY! There\'s no Page like that';
                // Select All Data depending on ID 
                $stmt = $connect->prepare("SELECT * FROM users WHERE UserID = ? LIMIT  1 ");
                // Execute Query and fetch data in row 
                $stmt->execute(array($userid));
                $row = $stmt->fetch();
                // The Row count 
                $count= $stmt->rowcount();
                // If ther's ID such that Execute the code 
                if($count > 0 ){ ?>
                    <h1 class="text-center">Edit Member</h1>
                    <div class="container edit">
                        <form class="form-horizontal ml-5" action="?do=update" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                            <div class="row form-group ml-5">
                                <label class="col-sm-2  control-label">Username</label>
                                <input type="hidden" name="oldName" value="<?php echo $row['UserName'] ?>">
                                <input type="text" name="username" value="<?php echo $row['UserName'] ?>" class="col-sm-9 col-md-6 form-control" autocomplete="off" required="required">
                            </div>

                            <div class="row form-group ml-5">
                                <label class="col-sm-2 control-label">Password</label>
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                                <input type="password" name="newpassword" class="col-sm-9 col-md-6 form-control" autocomplete="new-password" placeholder="Leave it blank if you don't change it">
                            </div>

                            <div class="row form-group ml-5">
                                <label class="col-sm-2 control-label">Email</label>
                                <input type="hidden" name="oldMail" value="<?php echo $row['Email'] ?>">
                                <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="col-sm-9 col-md-6 form-control" autocomplete="off" required="required">
                            </div>

                            <div class="row form-group ml-5">
                                <label class="col-sm-2 control-label">Full name</label>
                                <input type="text" name="name" value="<?php echo $row['FullName'] ?>" class="col-sm-9 col-md-6 form-control" autocomplete="off" required="required">
                            </div>

                            <div class="row form-group ml-5">
                                <label class="col-sm-2 control-label">Image</label>
                                <input type="file" name="image" class="col-sm-9 col-md-6 form-control">
                                <?php
                                    if(empty($row['Image'])){
                                            echo "<img src='..\upload\users\\1.jpg' alt='image' class='user-img ml-3'>";
                                    }else{
                                            echo "<img src='..\upload\users\\". $row['Image'] ."' alt='image' class='user-img ml-3'>";
                                    }
                                ?>
                            </div>

                            <div class="row form-group ml-5">
                                <label class="col-sm-2 control-label"></label>
                                <input type="submit" value="Update" class=" btn btn-primary col-sm-offset-3 col-sm-2">
                            </div>
                        </form>
                 </div>
            <?php
                }else{
                    $msg = "<div class='container alert alert-danger m-3'>Sorry! There's no page such that.</div>";
                    Redirect($msg, 'back', 5);
                }

        // update page         
        }elseif( $do == 'update' ){
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){

                echo  "<h1 class='text-center'>Update Member</h1>";
                // get vars from The FROM 
                $id      = $_POST['userid'];    // => Define user id to use it to update data in database;
                $oldName = $_POST['oldName'];
                $name    = $_POST['username'];
                $email   = $_POST['email'];
                $oldMail = $_POST['oldMail'];
                $full    = $_POST['name'];

                // Image 
                $imageName      = $_FILES['image']['name'];
                $imageTmpName   = $_FILES['image']['tmp_name'];
                $imageSize      = $_FILES['image']['size'];
                $imageType      = $_FILES['image']['type'];

                $imageAllowedExt = array("jpg", "jpeg", "png", "gif");
                $Ext = explode(".", $imageName);
                $imageExt = strtolower(end($Ext)); 
   
                // Check validation from BackEnd 
                $formErrors =array();  //=> Array to add errors inside it as array 

                if(empty($name)){
                    $formErrors[] = "Username can't be Empty";
                }
                if(strlen($name) < 3 ){
                    $formErrors[] = "Username can't be less than 4 Charcters";
                }
                if(empty($email)){
                    $formErrors[] = "Email can't be Empty";
                }
                if(empty($full)){
                    $formErrors[] = "Full Name can't be Empty";
                }
                if($imageSize > inMB(4)){
                    $formErrors[] = "Sorry! the image must be less than 4 MB";
                }
                if( !empty($imageName) && ! in_array($imageExt , $imageAllowedExt) ){
                    $formErrors[] = "Sorry! this extension is not allowed";
                }

                // Check Errors 
                foreach($formErrors as $error){
                    $msg = "<div class='container alert alert-danger'>" . $error . "</div>";
                    Redirect($msg, 'back', 10000);
                }

                // Change Data After Check Validation 

                if(empty($formErrors)){

                    $check = checkItem("UserName", "users" , $name);
                    $check2 = checkItem("Email", "users" , $email); // $email == New mail from EDIT FORM 

                    // Double Check in IF condation Check for UserName and CHECK for Email    
                    if(  ($check > 0 && ($oldName !== $name) ) || ( $check2 > 0 && ($oldMail != $email) ) ){   //
                        $error = "<div class='container alert alert-danger'> Sorry This Name (" . $name . " ) Is Already Exist</div>";
                        Redirect($error ,'back');
                    }else{
                    // Image 
                    $image = rand(0,10000000) . "_" . $imageName;
                    move_uploaded_file($imageTmpName , "..\upload\users\\" . $image);

                    // Password 
                    $pass = empty($_POST['oldpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                    // Update the database information 
                    $stmt = $connect->prepare("UPDATE users SET UserName =? ,Image =?, Email =? , FullName =? , Password =?  WHERE UserId =?");
                    $stmt->execute(array($name , $image, $email, $full , $pass , $id));
                    // Check Success 
                    $msg = "<div class='container alert alert-success'>" . $stmt->rowcount() . " Record Udated </div>";
                    Redirect($msg, 'back', 4 );
                    }
                }

            }else{
                $msg = "<div class='container alert alert-danger'>Sorry! There's no page such that.</div>";
                Redirect($msg ,'back');
                }

        // Add Page 
        }elseif( $do == 'add' ){?>
             <h1 class="text-center">Add Member</h1>
            <div class="container add">
                <form class="form-horizontal ml-5" action="?do=insert" method="POST" enctype="multipart/form-data">
                    <div class="row form-group ml-5">
                        <label class="col-sm-2  control-label">Username</label>
                        <input type="text" name="username" class="col-sm-9 col-md-6 form-control" autocomplete="off" required="required" placeholder="Username to login must be Unique">
                    </div>

                    <div class="row form-group ml-5">
                        <label class="col-sm-2  control-label">Image</label>
                        <input type="file" name="image" class="col-sm-9 col-md-6 form-control">
                    </div>

                    <div class="row form-group ml-5">
                        <label class="col-sm-2 control-label">Password</label>
                        <input type="password" name="password" class="password col-sm-9 col-md-6 form-control" autocomplete="new-password" required="required" placeholder="Please make your password strong one">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>

                    <div class="row form-group ml-5">
                        <label class="col-sm-2 control-label">Email</label>
                        <input type="email" name="email" class="col-sm-9 col-md-6 form-control" autocomplete="off" required="required" placeholder="Type your Email here">
                    </div>

                    <div class="row form-group ml-5">
                        <label class="col-sm-2 control-label">Full name</label>
                        <input type="text" name="name" class="col-sm-9 col-md-6 form-control" autocomplete="off" required="required" placeholder="Full name to show in the shop">
                    </div>

                    <div class="row form-group ml-5">
                        <label class="col-sm-2 control-label"></label>
                        <input type="submit" value="Add Member" class=" btn btn-primary col-sm-offset-3 col-sm-2">
                    </div>
                </form>
            </div>
        
        <?php
        // Insert Members 
        }elseif( $do == 'insert' ){
           if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo  "<h1 class='text-center'>Insert Member</h1>";
                // get vars from The FROM 
                $name   = $_POST['username'];
                $pass   = $_POST['password'];
                $email  = $_POST['email'];
                $full   = $_POST['name'];

                $hashpass = sha1($_POST['password']);

                // Upload User Image : 

                $imgAllowedExt = array("png", "jpg", "jpeg", "gif");

                $imageName      = $_FILES['image']['name'];
                $imageType      = $_FILES['image']['type'];
                $imageTmpName   = $_FILES['image']['tmp_name'];
                $imageSize      = $_FILES['image']['size'];

                $ext = explode("." , $imageName);
                $imgExtension = strtolower(end($ext));



                // Check validation from BackEnd 
                $formErrors =array();

                if(empty($name)){
                    $formErrors[] = "Username can't be Empty";
                }
                if(strlen($name) < 3 ){
                    $formErrors[] = "Username can't be less than 4 Charcters";
                }
                if(strlen($name) > 20 ){
                    $formErrors[] = "Username can't be more than 20 Charcters";
                }
                if(empty($pass)){
                    $formErrors[] = "Password can't be Empty";
                }
                if(empty($email)){
                    $formErrors[] = "Email can't be Empty";
                }
                if(empty($full)){
                    $formErrors[] = "Full Name can't be Empty";
                }
                // image Errors 
                if($imageSize > inMB(4)){
                    $formErrors[] = "Sorry! the image must be less than 4 MB";
                }
                if( !empty($imageName) && ! in_array($imgExtension , $imgAllowedExt) ){
                    $formErrors[] = "Sorry! this extension is not allowed";
                }
    
                foreach($formErrors as $error){
                    $msg = "<div class='container alert alert-danger'>" . $error . "</div>";
                    Redirect($msg, 'back', 5);
                }

                // Change Data After Check Validation 

                if(empty($formErrors)){
                    
                    $check1 = checkItem("UserName", "users", $name);
                    $check2 = checkItem("Email", "users", $email);
                    if($check1 > 0 || $check2 > 0 ){
                        $error =  "<div class='container alert alert-danger'>Sorry This Name (" . $name . ") Or Email Is Already Exist</div>";
                        Redirect($error , 5);
                        
                    }else{
                        // set Name
                        $image = rand(0,1000000) . "_" . $imageName;

                        if(isset($image)){
                            $image = rand(0,1000). "_" . $imageName ;
                        }
                        move_uploaded_file($imageTmpName , "..\upload\users\\" . $image);
                        
                        // Insert Information to the database 
                        $stmt = $connect->prepare("INSERT INTO 
                                                users(UserName, Image, Password, Email, FullName, RegisterStatus, Date)
                                                VALUES (:iuser,:iimage, :ipass, :imail, :ifull, 1, now())" );
                        $stmt->execute(array(
                            'iuser' => $name,
                            'iimage'=> $image,
                            'ipass' => $hashpass,
                            'imail' => $email,
                            'ifull' => $full,
                        ));

                        // Check Success 
                        $msg = "<div class='container alert alert-success'>" . $stmt->rowcount() . " Record Inserted </div>";
                        $url = "?do=mamage";
                        Redirect($msg, $url, 4);
                    }

                }

           }else{
               $error = "<div class='alert alert-danger'>SORRY! There's No Page Such That</div>";
               Redirect($error , 4);
           } 

        // Delete member Page    
        }elseif( $do == 'delete' ){
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ?intval($_GET['userid']) :'SOORY! There\'s no Page like that';
            // Get data from table 
            $stmt = $connect->prepare('SELECT * FROM users WHERE UserId = ? LIMIT 1');
            $stmt->execute(array($userid));
            // get how many counts with that 
            $count = $stmt->rowcount();
            if($count > 0){
                $stmt = $connect->prepare('DELETE FROM users WHERE UserID = :Duserid');
                $stmt->bindparam(":Duserid", $userid); // => binding parametere with database
                $stmt->execute();
                $msg= '<div class="container alert alert-success mt-5">' . $count .' RECORD DELEATED </div>';
                Redirect($msg, 'back', 3);
                
            }else{
                $msg = "<div class='container alert alert-danger'>Sorry! There's no page such that.</div>";
                Redirect($msg ,'back',5);
            }

        // Activate Members     
        }elseif($do=="activate"){
            echo "<h1 class='text-center'>Activate Page</h1>";
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ?intval($_GET['userid']) :'SOORY! There\'s no Page like that'; 
            $check = checkItem('UserID' , 'users' , $userid);
            if( $check > 0 ){
                $stmt = $connect->prepare("UPDATE users SET RegisterStatus = 1 WHERE UserId = ? ");
                $stmt->execute(array($userid));
                $count = $stmt->rowCount();
                $msg= '<div class="container alert alert-success mt-5">' . $count .' RECORD Activated </div>';
                Redirect($msg, 'back', 3);
            }else{
                $msg= '<div class="container alert alert-danger mt-5"> This ID Isn\'t Exist </div>';
                Redirect($msg, 'back', 3);
            }
        }else{
            $msg = '<div class="container alert alert-danger mt-5">ERROR There\'s no Page with this Name </div>' ;
            Redirect($msg, 'back');
        }
                                                 
    include $tmpls . "footer.inc.php"; 
}else {
    header('location:index.php');
    exit();
}
ob_end_flush();
<?php
ob_start();
session_start();
$title = 'Login page';

// Start Login Code [Check data with database]
if(isset($_SESSION['user'])){
    header('location:index.php');
}
include ('init.php');

if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    if(isset($_POST['login'])){
        $user = $_POST['username'];
        $pass= $_POST['password'];
        $hashPass = sha1($pass);
        
        // Check if the User is Exost in Database
        $stmt= $connect->prepare("SELECT UserID, UserName , Password FROM users WHERE UserName=? AND Password=? ");
        $stmt->execute(array($user, $hashPass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0 ){
            $_SESSION['user'] = $user;
            $_SESSION['userID'] = $get['UserID'];
            header("location:index.php");
            exit();
        }else{
            $msg = "<div class='container alert alert-danger mt-3'> There isn't username such that </div>";
            Redirect($msg , 'back');
        }
    }else{ // Start Signup form 
        $formErrors = array();
        // Check and validate username
        $newUser    = $_POST['username'];
        $newPass    = $_POST['password'];
        $newPass2   = $_POST['confirm-password'];
        $newEmail   = $_POST['Email'];

        if(isset($newUser)){
            $filteredUser = filter_var( $newUser , FILTER_SANITIZE_STRING); 
            if(strlen($filteredUser) < 4 ){
                $formErrors[] = "Username must be more than 4 charcters";
            }
        }
        // Check and validate Password
        if( isset($newPass) && isset($newPass2)) {

            if(empty($_POST['password'])){
                $formErrors[] = "The password can't be empty";
            }

            if(sha1($newPass) !== sha1($newPass) ){
                $formErrors[] = "Please Confirm Your Password";
            }
        }
        // Check and validate Email
        if(isset( $newEmail)){
            $email = filter_var( $newEmail , FILTER_SANITIZE_EMAIL);
            if(filter_var($email, FILTER_VALIDATE_EMAIL) != true ){
                $formErrors[] = 'Please enter a valid Email';
            }
        }
        // Check if username exists in database or not 
        $check = checkItem('UserName' , 'users' , $newUser);
        if($check == 1 ){
            $formErrors[] = 'This username is already exists';
        // insert a new user in database 
        }else{
            $stmt = $connect->prepare("INSERT INTO 
                                                users(UserName, Password, Email, RegisterStatus, Date)
                                                VALUES (:nUser, :nPass, :nEmail, 0 , now() )");
            $stmt->execute(array(
                'nUser'  => $newUser, 
                'nPass'  => sha1($newPass),
                'nEmail' => $newEmail
            ));

            $successMsg = 'Congrats you are a registered member now';

        }
    } 

}


?>
<!-- Start Forms -->
<div class="container login-page">
    <h1 class="text-center"><span data-class=".login" class="log selected">Login</span>|
                            <span class="sign" data-class=".signup">SignUp</span></h1>
    <!-- Start Login Form -->
    <form class="login" action=<?php echo $_SERVER['PHP_SELF']; ?> method="POST" >
        <div class="input-container">
            <input type="text" name="username" class="form-control " placeholder="Enter your Name" autocomplete="off" required>
        </div>

        <div class="input-container">
            <input type="password" name="password" class="form-control" placeholder="Enter Your Password" autocomplete="new-password" required>
        </div>

        <input type="submit" value="Login" name="login" class="btn btn-primary form-control">
    </form>
    <!-- End Login Form -->

    <!-- Start Signup Form -->
    <form class="signup" action=<?php echo $_SERVER['PHP_SELF'] ?> method="POST" >
        <div class="input-container">
        <input pattern=".{4,}" title="The username must be more than 4 charcters" 
        type="text" name="username" class="form-control" placeholder="Choose your Name" autocomplete="off" required>
        </div>

        <div class="input-container">
        <input type="email" name="Email" class="form-control" placeholder="Enter your Email" autocomplete="off" required>
        </div>

        <div class="input-container">
        <input minlength="4" type="password" name="password" class="form-control" placeholder="Make Your Password Strong" autocomplete="new-password" required>
        </div>

        <div class="input-container">
        <input minlength="4" type="password" name="confirm-password" class="form-control" placeholder="Re-Enter Your Password" autocomplete="new-password"required >
        </div>

        <input type="submit" value="signUp" name="signup" class="btn form-control">
    </form>   
    <!-- End Signup Form -->
    <!-- Start Messages Div -->
    <div class="messages text-center">
        <?php
            foreach($formErrors as $Error){
                echo "<div class='error'>". $Error ."</div>";
            }
            if(isset($successMsg)){
                echo "<div class='success'>". $successMsg ."</div>";
            }
        ?>
    </div>
</div>


<?php include $tmpls."footer.inc.php"; 
ob_end_flush();

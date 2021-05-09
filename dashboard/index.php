<?php 
ob_start();
    session_start();
    $noNavbar = '';
    $title = 'Login Page';
    
    if(isset($_SESSION['Admin'])){
        header('location:dashboard.php');
    }
    include "init.php";

    // check if User coming from POST method
    if($_SERVER['REQUEST_METHOD'] == "POST" ){
        $username = $_POST['username'];
        $pass = $_POST['password'];
        $hashPass = sha1($pass);

        // Check if the User is Exost in Database
        $stmt = $connect->prepare(" SELECT UserID, UserName, password 
                                    FROM   users 
                                    WHERE  UserName = ?
                                    AND    password = ? 
                                    AND    GroupID = 1
                                    LIMIT  1 ");

        $stmt-> execute(array($username, $hashPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0){
            $_SESSION['Admin'] = $username;      // Set session for Admin from login Form 
            $_SESSION['ID'] = $row['UserID'];   // Set session for UserId from Database 
            header('location:dashboard.php');   // Redirect to Dashboard Page
            exit();
        }
    }
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Login Page</h4>
    <input type="text" class="form-control" name="username" placeholder="User Name" autocomplete="off">
    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password">
    <input type="submit"class="btn btn-primary btn-block" value="Login">
</form>

<?php ob_end_flush();  ?>
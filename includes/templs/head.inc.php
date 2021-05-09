<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href= "<?php echo $css;?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $css;?>font-awesome.min.css">
        <link rel="stylesheet" href= "<?php echo $css;?>jquery.selectBoxIt.css">
        <link rel="stylesheet" href="<?php echo $css;?>main_index.css">
        <title><?php getTitle(); ?>  </title>
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><?php echo language('LOGO');?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php"><?php echo language('MainPage');  ?><span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo language('about');?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo language('contact');?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <span class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo language('categories');?></span>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                                foreach(getCats() as $cat){
                                echo "<a class='dropdown-item' href='categories.php?catid=". $cat['CatID'] ."&cat-name=". str_replace(" ","-", $cat['Name']) ."'>" .  $cat['Name'] . "</a>";
                                }
                            ?>
                    </li>
                    <li class="nav-item dropdown my-info ml-4">
                        <?php 
                            if(isset($_SESSION['user'])){
                        ?>
                            <a href="profile.php">
                                <img src="https://placehold.it/150" class="img-thumbnail rounded-circle">
                            </a>
                            <span class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo  $_SESSION['user'] ?>
                            </span> 
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="profile.php"> Profile </a>
                                <a class="dropdown-item" href="ads.php"> New Add </a>
                                <a class="dropdown-item" href="profile.php#my-ads"> My Ads </a>
                                <a class="dropdown-item" href="logout.php"> Logout </a>
                            </div>
                    </li>
                        <?php
                        }else{
                            echo '<li class="nav-item"><a class="nav-link login" href="login.php">Login | SignUp</a></li>';
                        }
                        ?>     
                </ul>
            </div>
        </div>
    </nav>
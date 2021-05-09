<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><?php echo language('LOGO');?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php"><?php echo language('MainPage');  ?><span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo language('about');?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($_GET['members']){echo "active";}?>" href="members.php?do=manage"><?php echo language('members');?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php?do=manage"><?php echo language('categories');?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="items.php?do=manage"><?php echo language('items');?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="comments.php?do=manage"><?php echo language('comment');?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo language('contact');?></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['Admin'];?></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../index.php"> Visit Shop </a>
                    <a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID']?>">
                    <?php echo language('Profile');?></a>
                    <a class="dropdown-item" href=""> <?php echo language('settings');?> </a>
                    <a class="dropdown-item" href="logout.php"><?php echo language('logout');?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
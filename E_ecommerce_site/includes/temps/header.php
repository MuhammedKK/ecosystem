<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title><?= getTheTitle();?></title>
        <link rel="stylesheet" href="<?= $CSS; ?>bootstrap.min.css"/>
        <link rel="stylesheet" href="<?= $CSS; ?>font-awesome.css"/>
        <link rel="stylesheet" href="<?= $CSS; ?>jquery-ui.min.css"/>
        <link rel="stylesheet" href="<?= $CSS; ?>jquery.selectBoxIt.css"/>
        <link rel="stylesheet" href="<?= $CSS;?>front-end.css"/>
    </head>
    <body>

    <!-- Start Upper Navbar Section -->
    <div class="upper">
        <div class="container">
            <div class="register">
<?php
    
    if(isset($_SESSION['user'])) { ?>
        <h3 class="pull pull-left">Welcome <?php echo $userSession; ?></h3>
        <div class="pull pull-right">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <?php echo $userSession; ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
                    <li><a href="newAd.php"><i class="fa fa-plus"></i> New Ad</a></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    <?php } else {

?>
            
                <a href="login.php">Login</a> - 
                <a href="login.php">Signup</a>
            </div>
<?php
    }
?>
        </div>
    </div>
    <!-- End Upper Navbar Section -->

    <nav class="navbar navbar-inverse">
    <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myApp" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php">HomePage</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="myApp">
    <ul class="nav navbar-nav navbar-right">
        <?php
            $cats = getAll("*", "categories", "WHERE cat_visibility = 0", "", "cat_id");

            foreach($cats as $cat) {

                echo '<li><a href="categories.php?pageID='. $cat['cat_id'] .'&pageName='. str_replace(' ', '-', $cat['cat_name']) .'">' . $cat['cat_name'] . '</a></li>';
            }
        ?>
    </ul>
    </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
    </nav>
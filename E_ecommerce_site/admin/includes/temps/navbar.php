
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
      <a class="navbar-brand" href="dashboard.php"><?= lang('MYSHOP') ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="myApp">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?= lang("CATEGORIES") ?></a></li>
        <li><a href="items.php"><?= lang('ITEMS') ?></a></li>
        <li><a href="members.php"><?= lang('MEMBERS') ?></a></li>
        <li><a href="comments.php"><?= lang('COMMENTS') ?></a></li>
        <li><a href="stats.php"><?= lang('STATS') ?></a></li>
        <li><a href="#"><?= lang('LOGS') ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $_SESSION['Username'] ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php"><i class="fa fa-home"></i> HomePage</a></li>
            <li><a href="members.php?action=edit&Userid=<?php echo $_SESSION['ID'] ?>"><i class="fa fa-edit"></i> Edit Profile</a></li>
            <li><a href="setting.php"><i class="fa fa-bars"></i> Settings</a></li>
            <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
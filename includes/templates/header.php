<!DOCTYPE html>
<html>
  <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <title> <?php echo getTitle(); ?> </title>
     <link rel="icon" href="open.png">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
     <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
     <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
     <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
     <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
  </head>
  <body>


  <div class="upper-bar">
    <div class='container'>
    <?php
    
        if (isset($_SESSION['user'])){  ?>

            <div class="btn-group my-info pull-right">
              <?php
                  $getUser = $con->prepare('SELECT * FROM users WHERE Username = ?');
                  $getUser->execute(array($sessionUser));
                  $info = $getUser->fetch();

                  if (empty($info['avatar'] )) {
                      echo "<img class='img-circle img-thumbnail' src='admin/uploads/avatars/defualt_img.png' alt='avatar'/>";
                  }else{
                      echo "<img class='img-circle img-thumbnail' src='admin/uploads/avatars/". $info['avatar'] . "' alt='avatar'/>";
                  }
                ?>
              <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?php echo $sessionUser ?>
                <span class="caret"></span>
              </span>
              <ul class="dropdown-menu">
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="newad.php">New Item</a></li>
                <li><a href="profile.php#my-ads">My Items</a></li>
                <li><a href="logout.php">LogOut</a></li>
              </ul>
            </div>

        <?php

        $userStatus = checkUserStatus($sessionUser); // function check if the user is activate or not
          if($userStatus == 1){
            echo 'Welcome '.$sessionUser.' Your Membership Need To Activate By Admin';
          }

        } else{
          ?>
          <a href="login.php">
            <span class="pull-right">Login/SignUp</span>
          </a>
        <?php } ?>
    </div> 
  </div>


  <nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?php echo lang('HOME_ADMIN')?></a>
    </div>

    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
        <?php

          $allCats = getAllFrom('*' , 'categories' , 'WHERE parent = 0', '', 'ID', 'ASC');
          foreach($allCats as $cat){
              echo '<li>
                      <a href="categoires.php?pageid='. $cat['ID'] .'">'.$cat['Name'].'</a>
                    </li>';
          }
        ?>
      </ul>
    </div>
  </div>
</nav>

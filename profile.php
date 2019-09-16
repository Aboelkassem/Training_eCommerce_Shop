<?php
	ob_start(); // Output Buffering Start
    session_start();

    $pageTitle = 'Profile'; // function getTitle();

    include 'init.php';

    if(isset($_SESSION['user'])){

    $getUser = $con->prepare('SELECT * FROM users WHERE Username = ?');

    $getUser->execute(array($sessionUser));

    $info = $getUser->fetch();

    $userid = $info['UserID'];
?>

<h1 class="text-center">My Profile</h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                   <li>
                        <i class='fa fa-unlock-alt fa-fw'></i>
                        <span>Login Name </span>:  <?php echo $info ['Username'] ?> 
                  </li>
                   <li>
                        <i class='fa fa-envelope fa-fw'></i>
                        <span> Email </span>: <?php echo $info ['Email'] ?> 
                   </li>
                   <li>
                        <i class='fa fa-user fa-fw'></i>
                        <span> Full Name </span>: <?php echo $info ['FullName'] ?> 
                   </li>
                   <li>
                       <i class='fa fa-calendar-check fa-fw'></i>
                       <span> Register Date </span>: <?php echo $info ['Date'] ?> 
                   </li>
                   <li>
                       <i class='fa fa-tags fa-fw'></i>
                       <span> Fav Category </span>: </li>
                </ul>
                <a href="#" class="btn btn-default">Edit Information </a>
            </div>
        </div>
    </div> 
</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
                <?php
                    $myItems = getAllFrom('*', 'items', "WHERE Member_ID = $userid" , '' , 'Item_ID') ;
                    if(! empty($myItems)){ // $items = getItems('Member_ID',$info['UserID'])
                        echo '<div class="row">';
                        foreach( $myItems as $item){ // get all data Depend on categoiry ID which send it link ==> WHERE Cat_ID =ID of the page [Relations]
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                    if($item['Approve'] == 0) {
                                        echo '<span class="approve-status">Waiting Approval</span>';
                                    }
                                    echo '<span class="price-tag">$'. $item['Price'].'</span>';
                                    if (empty($item['Image'] )) {
                                        echo "<img class='img-responsive' style='height: 200px;width: 200px;' src='admin/uploads/avatars/defualt_img.png' alt='avatar'/>";
                                    }else{
                                        echo "<img class='img-responsive' style='height: 200px;width: 200px;' src='admin/uploads/avatars/". $item['Image'] . "' alt='avatar'/>";
                                    }
                                    echo '<div class="caption">';
                                        echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'. $item['Name'] .'</a></h3>';
                                        echo '<p>'. $item['Description'] .'</p>';
                                        echo '<div class="date">'. $item['Add_Date'] .'</div>';
                                    echo '</div>';
                                echo '</div>';
                        echo '</div>';
                        } 
                        echo '</div>';
                    }else {
                        echo 'Sorry There\'s No Ads To Show , Create <a href="newad.php">New Ad</a> ';
                    }
                ?>
            </div>
        </div>
    </div> 
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"> Latest Comments</div>
            <div class="panel-body">
            <?php

                $myComments = getAllFrom("comment" , "comments" , "WHERE user_id = $userid", "","c_id");
                if(! empty($myComments)){

                    foreach($myComments as $comment){
                        echo '<p>' . $comment['comment'] . '</p>';
                    }
                    
                }else{
                    echo 'There\'s No Comments To Show';
                }

            ?>
            </div>
        </div>
    </div> 
</div>

<?
    } else{
        header('Location: login.php');
        exit();
    }
    include $tpl . "footer.php"; 

    ob_end_flush(); // Release The Output

?>

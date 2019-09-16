<?php
ob_start(); // Output buffering Start to fix header already send error
session_start();
if (isset($_SESSION['Username'])){

    $pageTitle = 'Dashboard';

    include 'init.php';

    /* Start Dashboard Page */

    $numUsers = 6 ; // number of latest Users

    $latestUsers = getLatest('*','users' ,'UserID' , $numUsers); // Latest User Array

    $numItems = 7 ; // number of latest Items

    $latestItems = getLatest('*','items' ,'Item_ID' , $numUsers); // Latest Items Array

    $numComments = 10 ; // number of latest Comments

    ?>
        <div class='home-stats'>
            <div class ='container text-center'>
                <h1><?php echo lang('DASHBOARD_TITLE'); ?></h1>
                <div class='row'>
                    <div class='col-md-3'>
                        <div class='stat st-members'>
                            <i class="fa fa-users"></i>
                            <div class="info">
                                <?php echo lang('MEMERS'); ?>
                                <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='stat st-pending'>
                          <i class="fa fa-user-plus"></i>
                          <div class="info">
                            <?php echo lang('PENDING_MEMBERS'); ?>
                                <span><a href="members.php?do=Manage&page=Pending">
                                    <?php echo checkItem("RegStatus","users", 0)?>
                                </a></span>
                          </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='stat st-items'>
                          <i class="fa fa-tag"></i>
                          <div class="info">
                            <?php echo lang('ITMES'); ?>
                                <span><a href="items.php"><?php echo countItems('Item_ID', 'items') ?></a></span>
                          </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='stat st-comments'>
                          <i class="fa fa-comments"></i>
                          <div class="info">
                            <?php echo lang('COMMENTS'); ?>
                            <span><a href="comments.php"><?php echo countItems('c_id', 'comments') ?></a></span>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='latest'>
            <div class='container'>
                <div class ='row'>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registerd Users
                                <span class="toggle-info pull-right">
                                  <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class='panel-body'>
                                <ul class="list-unstyled latest-users">
                                    <?php
                                        if(!empty($latestUsers)){
                                            foreach($latestUsers as $user){       // get latest 5 data from database and print [username] in this loop
                                                echo '<li>';
                                                    echo $user['Username'];
                                                    echo '<a href="members.php?do=Edit&userid='. $user['UserID'].'">';
                                                        echo '<span class="btn btn-success pull-right">';
                                                            echo '<i class="fa fa-edit"></i> Edit';

                                                            if($user['RegStatus'] == 0){
                                                                echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '" class ="btn btn-info pull-right activate"> <i class="fa fa-check-circle"></i> ' . lang("ACTIVITE_MEMBER") . ' </a>';
                                                            }

                                                        echo '</span>';
                                                    echo '</a>';
                                                echo '</li>';
                                            }
                                        }else {
                                            echo '<div class ="nice-massage">There\'s No Members To Show </div>';
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest <?php echo $numItems ; ?> Items
                                <span class="toggle-info pull-right">
                                  <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class='panel-body'>
                                <ul class="list-unstyled latest-users">
                                        <?php
                                            if(! empty($latestItems)){
                                                foreach($latestItems as $item){       // get latest 5 data from database and print [username] in this loop
                                                    echo '<li>';
                                                        echo $item['Name'];
                                                        echo '<a href="items.php?do=Edit&itemid='. $item['Item_ID'].'">';
                                                            echo '<span class="btn btn-success pull-right">';
                                                                echo '<i class="fa fa-edit"></i> Edit';

                                                                if($item['Approve'] == 0){
                                                                    echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class ="btn btn-info pull-right activate"> <i class="fa fa-check"></i> ' . lang("APPROVE_ITEM") . ' </a>';
                                                                }

                                                            echo '</span>';
                                                        echo '</a>';
                                                    echo '</li>';
                                                }
                                             }else{
                                                echo '<div class ="nice-massage">There\'s No Items To Show </div>';
                                             }
                                        ?>
                                    </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Latest Comments -->
                <div class ='row'>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="far fa-comments"></i> Latest <?php echo $numComments; ?> Comments
                                <span class="toggle-info pull-right">
                                  <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class='panel-body'>
                            <?php
                            $stmt = $con->prepare("SELECT 
                                                comments.*, users.Username As Members
                                            FROM 
                                                comments
                                            INNER JOIN
                                                users
                                            ON
                                                users.UserID = comments.user_id
                                            ORDER BY 
                                                c_id DESC
                                            LIMIT $numComments");
                            $stmt->execute(); // execute the statement

                            // Assign all data to variables
                            $comments = $stmt->fetchAll(); 

                              if (!empty($comments)){
                                foreach($comments as $comment){

                                    echo '<div class="comment-box">';
                                        echo '<a href ="members.php?do=Edit&userid='. $comment['user_id'] .'"><span class="member-n">'.$comment['Members'] . '</span></a>';
                                        echo '<p class="member-c">'.$comment['comment'] . '</p>';
                                    echo '</div>';
                                    
                                }
                              }else{
                                echo '<div class ="nice-massage">There\'s No Comments To Show </div>';
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Latest Comments -->
            </div>
        </div>
        <?php
        /* End Dashboard Page */

        include $tpl . "footer.php";

    }else {

       header('Location: index.php');
       exit();

    }

    ob_end_flush();
    ?>

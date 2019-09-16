<?php
	ob_start(); // Output Buffering Start
    session_start();

    $pageTitle = 'Show Items'; // function getTitle();

    include 'init.php';

    // check if get request itemid is numberic & get its integer value
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]

    // Select All Data Depend On this ID
    $stmt = $con-> prepare("SELECT 
                                items.* , categories.Name AS category_name , users.Username AS members
                            FROM 
                                `items`
                            INNER JOIN 
                                categories
                            ON
                                categories.ID  = items.Cat_ID
                            INNER JOIN 
                                users
                            ON
                                users.UserID  = items.Member_ID
                            WHERE 
                                Item_ID =  ?
                            AND 
                                Approve = 1 ");
    $stmt-> execute(array($itemid)); // execute query
    $count = $stmt->rowCount();

    if ($count > 0){


    $item = $stmt-> fetch();         // get Data from database and print it in array

?>

<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php
                if (empty($item['Image'] )) {
                    echo "<img class='img-responsive img-thumbnail center-block' src='admin/uploads/avatars/defualt_img.png' alt='avatar'/>";
                }else{
                    echo "<img class='img-responsive img-thumbnail center-block' src='admin/uploads/avatars/". $item['Image'] . "' alt='avatar'/>";
                }
            ?>
        </div>
        <div class="col-md-9 item-info">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar-alt fa-fw"></i>
                    <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                </li>
                <li>
                    <i class="fa fa-money-bill-alt fa-fw"></i>
                    <span>Price</span> : $<?php echo $item['Price'] ?>
                </li>
                <li>
                    <i class="fa fa-globe-africa fa-fw"></i>
                    <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Category</span> : <a href="categoires.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span>Added By</span> : <a href="#"><?php echo $item['members'] ?></a>
                </li>
                <li class="tags-items">
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Tags</span> : 
                    <?php
                        $allTags = explode("," , $item['tags']); // separate all tags by (,) and get all in array
                        foreach($allTags as $tag){
                            $tag      = str_replace(' ', '', $tag); // replace all spaces to empty [delete spaces]
                            $lowerTag = strtolower($tag);           // after that make it in lower case 
                            if(!empty($tag)){
                                echo "<a href='tags.php?name={$lowerTag}'>".$tag .'</a>';
                            }
                        }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr class="custom-hr">
    
    <?php if(isset($_SESSION['user'])){ ?>
    <!-- Start Add Comment  -->
    <div class="row">
        <div class="col-md-offset-3">
            <div class="add-comment">
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='. $item['Item_ID']; ?>" method="POST">
                    <textarea name="comment" id="" cols="30" rows="5" required></textarea>
                    <input class="btn btn-primary" type="submit" value="Add Comment">
                </form>
                <?php
                
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){

                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $itemid  = $item['Item_ID'];
                        $userid  = $_SESSION['uid'];

                        if (!empty($comment)){
                        
                            $stmt = $con->prepare("INSERT INTO 
                                                    comments(comment , status , comment_date , item_id , user_id)
                                                    VALUES (:zcomment , 0 , now(), :zitemid , :zuserid)");
                            $stmt->execute(array(
                                
                                'zcomment'  => $comment ,
                                'zitemid'   => $itemid,
                                'zuserid'   => $userid

                            ));

                            if($stmt){

                                echo '<div class="alert alert-success">Comment Added</div>';

                            }

                        }
                    }

                ?>
            </div>
        </div>
    </div>
    <!-- End Add Comment  -->
    <?php } else {
        echo '<a href="login.php">Login Or Register </a> To Add Comment';
    } ?>

    <hr class="custom-hr">

    <?php 
        $stmt = $con->prepare("SELECT 
                                    comments.*, users.Username As Member , users.avatar As Photo
                                FROM 
                                    comments
                                INNER JOIN 
                                    users
                                ON
                                    users.UserID = comments.user_id
                                WHERE 
                                    item_id = ?
                                AND 
                                    status = 1
                                ORDER BY 
                                    c_id DESC ");
        $stmt->execute(array($item['Item_ID'])); // execute the statement
        // Assign all data to variables
        $comments = $stmt->fetchAll();

        
    foreach($comments as $comment){ ?>

            <div class="comment-box">
               <div class="row">
                     <div class="col-sm-2 text-center">
                        <?php
                        if (empty($comment['Photo'] )) {
                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='admin/uploads/avatars/defualt_img.png' alt='avatar'/>";
                        }else{
                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='admin/uploads/avatars/". $comment['Photo'] . "' alt='avatar'/>";
                        }
                        ?>
                        <?php echo $comment['Member'] ?>
                     </div>

                    <div class="col-sm-10">
                       <p class="lead"><?php echo $comment['comment'] ?></p>
                    </div>
                </div>
            </div>
            <hr class="custom-hr">
           <?php }
    ?>
</div>

<?php
    }else{
        echo '<div class="container">';
            echo '<div class="alert alert-danger">There\'s No Such ID : Or This Item Waiting Approval </div>';
        echo '</div>';

    }
    include $tpl . "footer.php"; 
    ob_end_flush(); // Release The Output

?>

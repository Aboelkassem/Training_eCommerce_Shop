<?php

/*
========================================================
=== Manage Comments Page
=== you can Edit | Delete | Approve Comments from here
========================================================
*/

session_start();

$pageTitle = 'Comments';

if (isset($_SESSION['Username'])){

    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // check if request get do

    if ($do == 'Manage' ){          //* Start Manage Page =============================================================================================================================

            $stmt = $con->prepare("SELECT 
                                        comments.*,items.Name AS Item_Name , users.Username As Members
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        items
                                    ON
                                        items.Item_ID = comments.item_id
                                    INNER JOIN 
                                        users
                                    ON
                                        users.UserID = comments.user_id
                                    ORDER BY 
                                        c_id DESC ");
            $stmt->execute(); // execute the statement

            // Assign all data to variables
            $comments = $stmt->fetchAll();

            if(!empty($comments)){
        
        ?>
        <h1 class ='text-center'> <?php echo lang('COMMENT_TITLE') ?> </h1>
        <div class ='container'>
            <div class='table-responsive'>
                <table class = 'main-table text-center table table-bordered'>
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                    
                        foreach($comments as $comment){
                            echo '<tr>';
                                echo '<td>'. $comment['c_id'] . '</td>' ;
                                echo '<td>'. $comment['comment'] . '</td>' ;
                                echo '<td>'. $comment['Item_Name'] . '</td>' ;
                                echo '<td>'. $comment['Members'] . '</td>' ;
                                echo '<td>'. $comment['comment_date']  .'</td>' ;
                                echo '<td>
                                    <a href="comments.php?do=Edit&comid=' . $comment['c_id'] . '" class ="btn btn-success"> <i class="fa fa-edit"></i> ' .lang("EDIT_MEMBER") . ' </a>
                                    <a href="comments.php?do=Delete&comid=' . $comment['c_id'] . '" class ="btn btn-danger confirm"> <i class="fa fa-close"></i> ' . lang("Delete_MEMBER") . ' </a>';

                                    if($comment['status'] == 0){

                                        echo '<a href="comments.php?do=Approve&comid=' . $comment['c_id'] . '" class ="btn btn-info activate"> <i class="fa fa-check-circle"></i> ' . lang("APPROVE_COMMENT") . ' </a>';

                                    }

                                echo '</td>' ;
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
        </div>

        <?php } else {
            echo '<div class="container">';
                echo '<div class ="nice-massage">There\'s No Records To Show </div>';
            echo '</div>';
        }

    }
    elseif($do == 'Edit'){         //* Start Edit Page ==============================================================================================================================

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
       
        // Select All Data Depend On this ID
        $stmt = $con-> prepare("SELECT * FROM `comments` WHERE c_id =  ?");
        $stmt-> execute(array($comid)); // execute query
        $row = $stmt-> fetch();         // get Data from database and print it in array
        $count = $stmt->rowCount();     // get the record or count that found or effected

        // if there's Such Id Show the form
        if($count > 0){ ?>

            <h1 class ='text-center'> <?php echo lang('EDIT_Comment') ?> </h1>
            <div class ='container'>
                <form class='form-horizontal' action ='?do=Update' method='POST'>

                    <input type="hidden" name='comid' value='<?php echo $comid ?>'> 
                    <!-- Start Comment Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('COMMENT') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <textarea class='form-control' name="comment" cols="30" rows="10"><?php echo $row['comment'] ?></textarea>
                        </div>
                    </div>
                    <!-- End Comment Field -->

                    <!-- Start submit Field -->
                    <div class ='form-group form-group-lg'>
                        <div class ='col-sm-offset-2 col-sm-10 col-md-6'>
                            <input type="submit" value='<?php echo lang('UPDATE') ?>' class='btn btn-primary'>
                        </div>
                    </div>
                    <!-- End submit Field -->    
                </form>
            </div>

    <?php

        // IF there's No such ID in database Show this message

        } else{

            echo "<div class='container'>";
            
            $theMsg = '<div class ="alert alert-danger">There\'s No Such ID </div>';

            redirectHome($theMsg); // function redirect home showing error massage and time berfore doing that

            echo "</div>";
        }
    }elseif($do =='Update'){ //* Start Update Page ==============================================================================================================================

        echo "<h1 class ='text-center'> Update Comment </h1>";
        echo "<div class ='container'> ";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
         
            // Get Variables from The Form attribute[name]

            $comid      = $_POST['comid'];
            $comment    = $_POST['comment'];


            // Update This Database With This info

            $stmt = $con->prepare('UPDATE `comments` SET comment = ? WHERE c_id = ? ');
            $stmt-> execute(array($comment, $comid));
            
            // echo Sucessful message

            $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
            redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that


            echo "</div>";
            
        } else {

            $theMsg = '<div class="alert alert-danger">You Can\'t Browse this page Directory </div>';
            redirectHome($theMsg , 'back' , 5); // function redirect home showing error massage and time berfore doing that

        }

    } elseif($do =='Delete'){ //* Start Delete Page ========================================================================================================================================================

        echo "<h1 class ='text-center'> Delete Comment</h1>";
        echo "<div class ='container'> ";

            // check if get request userid is numeric & get the integer value of it
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
        
            // Select All Data Depend On this ID
            $check = checkItem("c_id","comments",$comid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
            
            // if there's Such Id Show the form
            if($check > 0){

                $stmt = $con->prepare('DELETE FROM comments WHERE c_id = :zid');
                $stmt->bindParam(":zid", $comid); // relate the $userid with UserID(الاستعلام) like makeing => UserID = $userid
                $stmt->execute();

                echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Deleted </div>';
        
                redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
    
                echo "</div>";

            }else{

                $theMsg ='<div class = "alert alert-danger"> This ID is Not Exist </div>';
                redirectHome($theMsg , 'back' , 4); // function redirect home showing error massage and time berfore doing that

            }
        echo '</div>';
    }elseif($do == 'Approve'){ //* Start Approve Page =======================================================================================================================================================

        echo "<h1 class ='text-center'> Approve Comment</h1>";
        echo "<div class ='container'> ";

            // check if get request comid is numeric & get the integer value of it
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
        
            // Select All Data Depend On this ID
            $check = checkItem("c_id","comments",$comid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
            
            // if there's Such Id Show the form
            if($check > 0){

                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                $stmt->execute(array($comid));

                echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Approved </div>';
        
                redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
    
                echo "</div>";

            }else{

                $theMsg ='<div class = "alert alert-danger"> This ID is Not Exist </div>';
                redirectHome($theMsg , 'back' , 4); // function redirect home showing error massage and time berfore doing that

            }
        echo '</div>';
    }

    include $tpl . "footer.php";

}else {

   header('Location: index.php');
   exit();

}
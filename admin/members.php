<?php

/*
========================================================
=== Manage Members Page
=== you can Add | Edit | Delete Members from here
========================================================
*/

session_start();
$pageTitle = 'Members';

if (isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // check if request get do

    

    if ($do == 'Manage' ){          //* Start Manage Page =============================================================================================================================

            $query = '';
            // select only pending members to activate it 
            if(isset($_GET['page']) && $_GET['page'] == 'Pending' ){ 
                $query = 'AND RegStatus = 0';
            }
            // Select all user except admin

            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
            $stmt->execute(); // execute the statement

            // Assign all data to variables
            $rows = $stmt->fetchAll();

            if(!empty($rows)){
        
        ?>
        <h1 class ='text-center'> <?php echo lang('MANAGE_TITLE') ?> </h1>
        <div class ='container'>
            <div class='table-responsive'>
                <table class = 'main-table manage-members text-center table table-bordered'>
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registerd Date</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                    
                        foreach($rows as $row){
                            echo '<tr>';
                                echo '<td>'. $row['UserID'] . '</td>' ;
                                echo "<td>";
                                    if (empty($row['avatar'] )) {
                                        echo "<img src='uploads/avatars/defualt_img.png' alt='avatar'/>";
                                    }else{
                                        echo "<img src='uploads/avatars/". $row['avatar'] . "' alt='avatar'/>";
                                    }
                                echo "</td>";
                                echo '<td>'. $row['Username'] . '</td>' ;
                                echo '<td>'. $row['Email'] . '</td>' ;
                                echo '<td>'. $row['FullName'] . '</td>' ;
                                echo '<td>'. $row['Date']  .'</td>' ;
                                echo '<td>
                                    <a href="members.php?do=Edit&userid=' . $row['UserID'] . '" class ="btn btn-success"> <i class="fa fa-edit"></i> ' .lang("EDIT_MEMBER") . ' </a>
                                    <a href="members.php?do=Delete&userid=' . $row['UserID'] . '" class ="btn btn-danger confirm"> <i class="fa fa-close"></i> ' . lang("Delete_MEMBER") . ' </a>';

                                    if($row['RegStatus'] == 0){

                                        echo '<a href="members.php?do=Activate&userid=' . $row['UserID'] . '" class ="btn btn-info activate"> <i class="fa fa-check-circle"></i> ' . lang("ACTIVITE_MEMBER") . ' </a>';

                                    }

                                echo '</td>' ;
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> <?php echo lang("ADD_MEMBER")?> </a>
        </div>
        <?php }else {
            ?>
                <div class="container">'
                    <div class ="nice-massage">There's No Members To Show </div>'
                    <a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> <?php echo lang("ADD_MEMBER")?> </a>
                </div>
        <?php
        } 

        }elseif($do == "Add"){        //* Start Add Page =============================================================================================================================
        ?>
        <h1 class ='text-center'> <?php echo lang('ADD_TITLE') ?> </h1>
            <div class ='container'>
                <form class='form-horizontal' action ='members.php?do=Insert' method='POST' enctype="multipart/form-data"> <!-- enctype = نوع التشفير بيبعت البيانات ازاى  -->
                    <!-- Start Username Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('USERNAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='username' class='form-control' autocomplete='off' required='required'placeholder="Username To login into Shop"/>
                        </div>
                    </div>
                    <!-- End Username Field -->

                    <!-- Start Password Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('PASSWORD') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="password" name='password' class='password form-control' required='required' autocomplete ='new-password' placeholder="Password Must be Hard & Complex">
                            <i class='show-pass fa fa-eye fa-1x'></i>
                        </div>
                    </div>
                    
                    <!-- End Password Field -->

                    <!-- Start Email Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('EMAIL') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="email" name='email'  class='form-control' required='required' placeholder="Email Must Be Valid">
                        </div>
                    </div>
                    <!-- End Email Field -->

                    <!-- Start Full Name Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('FULL_NAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='full' class='form-control' required='required' placeholder="Full Name Will Appear In Your Profile Page">
                        </div>
                    </div>
                    <!-- End Full Name Field -->

                    <!-- Start Avatar Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('USER_ABATER') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="file" name='avatar' class='form-control' required='required'>
                        </div>
                    </div>
                    <!-- End Avatar Field -->

                    <!-- Start submit Field -->
                    <div class ='form-group form-group-lg'>
                        <div class ='col-sm-offset-2 col-sm-10 col-md-6'>
                            <input type="submit" value='<?php echo lang('ADD_MEMBER') ?>' class='btn btn-primary'>
                        </div>
                    </div>
                    <!-- End submit Field -->    
                </form>
            </div>

    <?php 
    
    }elseif($do == 'Insert'){       //* Start Insert Member Page ==============================================================================================================================

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            echo "<h1 class ='text-center'> Insert New Member</h1>";
            echo "<div class ='container'> ";

            //* Upload Avatars

            //$avatar = $_FILES['avatar']; // get the info about files in array [name][type][tmp_name][size]
            
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarType = $_FILES['avatar']['type'];
            $avatarTem  = $_FILES['avatar']['tmp_name']; // الاسم المؤقت 
            
            // List OF Allowed File Typed To Upload
            $avatarAllowedExtension = array("jpeg","jpg","png","gif"); // الحاجات الي انا بسمح بيها ان الشخص يرفعها this is types of photo that i allow to add [Secuirty]

            // Get Avatar Extension

            $avatarExtension = strtolower(end(explode('.',$avatarName))); // end = get final value of array and make it in lower case to check with $avatarAllowedExtension

            // Get Variables from The Form attribute[name]

            $user     = $_POST['username'];
            $pass     = $_POST['password']; // just for check error , $hashPass will send to database
            $email    = $_POST['email'];
            $name     = $_POST['full'];

            $hashPass = sha1($_POST['password']);
            // Validate The Form

            $formErrors = array();

            if(strlen($user) < 4) {

                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 characters </strong>';

            }
            if(empty($user)){
                
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';

            }
            if(empty($pass)){
                
                $formErrors[] = 'Password Can\'t Be <strong>Empty</strong>';

            }
            if(empty($name)){
                
                $formErrors[] = 'Full Name Can\'t Be <strong>Empty</strong>';

            }
            if(empty($email)){
                
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';

            }
            if(!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtension)){

                $formErrors[] = 'This Type Of Photo Is Not <strong>Allowed</strong>';

            }
            if(empty($avatarName)){

                $formErrors[] = 'You Have To Choose <strong>Photo</strong> to Upload It';

            }
            if($avatarSize > 4194307){ // 4194307 = 4MB

                $formErrors[] = 'Avatar Cant Be Larger Than<strong>4MB</strong>';

            }


            foreach($formErrors as $errors){

                echo '<div class="alert alert-danger">'.$errors .'</div>';

            }
            
            // check if there's no error proced or continue The Update Operation

            if(empty($formErrors)){

                // avatar to send in database [rand()] to prevent image to repeat

                $avatar = rand(0 , 100000000) . '_' . $avatarName ; // Avatar Name

                //move_uploaded_file(filename, destination)
                move_uploaded_file($avatarTem ,"uploads\avatars\\".$avatar); // $avatarTem = Temporary Name ,, function to move files 

               // check if the username is exist in database

                $check = checkItem("Username","users",$user); // SELECT Username FROM users WHERE Username = $user //* look at checkItem function.php in includes 

                if($check == 1){

                    $theMsg = '<div class = "alert alert-danger">Sorry This User Is Exist , You can\'t add it </div>';
                    redirectHome($theMsg , 'back' , 4);

                }else {

                // Insert User Info To Database

                    $stmt = $con->prepare("INSERT INTO 
                                                users(Username, Password , Email , FullName , RegStatus, Date , avatar)
                                            VALUES( :zuser , :zpass , :zmail , :zname , 1, now(), :zavatar)");    // keys that will use to define array values
                    $stmt->execute(array(

                        'zuser'     => $user ,
                        'zpass'     => $hashPass ,
                        'zmail'     => $email ,
                        'zname'     => $name,
                        'zavatar'   => $avatar

                    ));
            
                    // echo Sucessful message

                    $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
                    redirectHome($theMsg , 'back' ,1);
                }
            }

            echo "</div>";
            
        } else {
            
            echo "<div class='container'>";
            
            $theMsg = '<div class = "alert alert-danger">Sorry You Can\'t Browse this page Directory </div>';

            redirectHome($theMsg , 1); // function redirect home showing error massage and time berfore doing that

            echo "</div>";
         }
    }
    elseif($do == 'Edit'){         //* Start Edit Page ==============================================================================================================================

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
       
        // Select All Data Depend On this ID
        $stmt = $con-> prepare("SELECT * FROM `users` WHERE UserID =  ? LIMIT 1 ");
        $stmt-> execute(array($userid)); // execute query
        $row = $stmt-> fetch();         // get Data from database and print it in array
        $count = $stmt->rowCount();     // get the record or count that found or effected

        // if there's Such Id Show the form
        if($count > 0){ ?>

            <h1 class ='text-center'> <?php echo lang('EDIT_TITLE') ?> </h1>
            <div class ='container'>
                <form class='form-horizontal' action ='members.php?do=Update' method='POST' enctype="multipart/form-data"> <!-- enctype = نوع التشفير بيبعت البيانات ازاى  -->

                    <input type="hidden" name='userid' value='<?php echo $userid ?>'> 
                    <!-- Start Username Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('USERNAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='username' class='form-control' value="<?php echo $row['Username'] ?>" autocomplete='off' required='required'/>
                        </div>
                    </div>
                    <!-- End Username Field -->

                    <!-- Start Password Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('PASSWORD') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="hidden" name='oldpassword' value='<?php echo $row['Password'] ?>'>
                            <input type="password" name='newpassword' class='form-control' autocomplete ='new-password' placeholder="Set Empty If you don't Want to Change">
                        </div>
                    </div>
                    
                    <!-- End Password Field -->

                    <!-- Start Email Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('EMAIL') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="email" name='email'  class='form-control' value="<?php echo $row['Email'] ?>" required='required'>
                        </div>
                    </div>
                    <!-- End Email Field -->

                    <!-- Start Full Name Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('FULL_NAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='full' class='form-control' value="<?php echo $row['FullName'] ?>" required='required'>
                        </div>
                    </div>
                    <!-- End Full Name Field -->

                    <!-- Start Avatar Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('USER_ABATER') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="file" name='avatar' class='form-control' required='required'>
                        </div>
                    </div>
                    <!-- End Avatar Field -->

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

        echo "<h1 class ='text-center'> Update Member</h1>";
        echo "<div class ='container'> ";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
         
            //* Upload Avatars

            //$avatar = $_FILES['avatar']; // get the info about files in array [name][type][tmp_name][size]
            
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarType = $_FILES['avatar']['type'];
            $avatarTem  = $_FILES['avatar']['tmp_name']; // الاسم المؤقت 
            
            // List OF Allowed File Typed To Upload
            $avatarAllowedExtension = array("jpeg","jpg","png","gif"); // الحاجات الي انا بسمح بيها ان الشخص يرفعها this is types of photo that i allow to add [Secuirty]

            // Get Avatar Extension

            $avatarExtension = strtolower(end(explode('.',$avatarName))); // end = get final value of array and make it in lower case to check with $avatarAllowedExtension


            // Get Variables from The Form attribute[name]

            $id      = $_POST['userid'];
            $user    = $_POST['username'];
            $email   = $_POST['email'];
            $name    = $_POST['full'];

            // Password Trick
            #condition ? true : false
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;

            // Validate The Form

            $formErrors = array();

            if(strlen($user) < 4) {

                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 characters </strong>';

            }
            if(empty($user)){
                
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';

            }
            if(empty($name)){
                
                $formErrors[] = 'Full Name Can\'t Be <strong>Empty</strong>';

            }
            if(empty($email)){
                
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';

            }
            if(!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtension)){

                $formErrors[] = 'This Type Of Photo Is Not <strong>Allowed</strong>';

            }
            if(empty($avatarName)){

                $formErrors[] = 'You Have To Choose <strong>Photo</strong> to Upload It';

            }
            if($avatarSize > 4194307){ // 4194307 = 4MB

                $formErrors[] = 'Avatar Cant Be Larger Than<strong>4MB</strong>';

            }


            foreach($formErrors as $errors){

                echo '<div class="alert alert-danger">'.$errors .'</div>';

            }

            // check if there's no error proced or continue The Update Operation

            if(empty($formErrors)){

                // Just For Check to get the user don't have his ID to Update his Info
                //  هو كدا بيعمل استعلام بيفحص لو اليوزر نيم موجود فعلا ولا لا لو موجود هيقولك سوري لو العكس هيحدث وبالتالي مش هتيحدث غير لما تغير اليوزر نيم عشان هو موجود فعلا فكد هتواجهك مشاكل وحلها انك تقوله الاي بي لا يساوي عشان يجيب يوزر غير يوزر بمعني لو لقيت يوزر نيم وملهوش الاي بي بتاعي مش هيتحدث الي هو يوزر نيم موجود قبل كا يغر كدا هيتحدث يعني لو جيت حدث اي حاجه ف البيانات وخلت اليوزر نيت زي مهو هيحدث عادي  
                $stmt2 = $con->prepare("SELECT 
                                            * 
                                        FROM 
                                            users 
                                        WHERE 
                                            Username = ? 
                                        AND 
                                            UserID != ?");
                $stmt2->execute(array($user , $id));

                $count = $stmt2->rowCount();
                
                if($count == 1 ){

                    $theMsg = '<div class ="nice-massage">Sorry This User Is Exist</div>';
                    redirectHome($theMsg , 'back');
                }else{
                // Update This Database With This info

                // avatar to send in database [rand()] to prevent image to repeat

                $avatar = rand(0 , 100000000) . '_' . $avatarName ; // Avatar Name

                //move_uploaded_file(filename, destination)
                move_uploaded_file($avatarTem ,"uploads\avatars\\".$avatar); // $avatarTem = Temporary Name ,, function to move files 


                $stmt = $con->prepare('UPDATE `users` SET Username = ? , Email = ? , FullName = ? , avatar = ?, Password = ? WHERE UserID = ? ');
                $stmt-> execute(array($user, $email, $name, $avatar, $pass, $id));
                
                // echo Sucessful message

                $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
                redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
                }          

            }

            echo "</div>";
            
        } else {

            $theMsg = '<div class="alert alert-danger">You Can\'t Browse this page Directory </div>';
            redirectHome($theMsg , 'back' , 5); // function redirect home showing error massage and time berfore doing that

        }

    } elseif($do =='Delete'){ //* Start Delete Page ========================================================================================================================================================

        echo "<h1 class ='text-center'> Delete Member</h1>";
        echo "<div class ='container'> ";

            // check if get request userid is numeric & get the integer value of it
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
        
            // Select All Data Depend On this ID
            $check = checkItem("userid","users",$userid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
            
            // if there's Such Id Show the form
            if($check > 0){

                $stmt = $con->prepare('DELETE FROM users WHERE UserID = :zuser');
                $stmt->bindParam(":zuser", $userid); // relate the $userid with UserID(الاستعلام) like makeing => UserID = $userid
                $stmt->execute();

                echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Deleted </div>';
        
                redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
    
                echo "</div>";

            }else{

                $theMsg ='<div class = "alert alert-danger"> This ID is Not Exist </div>';
                redirectHome($theMsg , 'back' , 4); // function redirect home showing error massage and time berfore doing that

            }
        echo '</div>';
    }elseif($do == 'Activate'){ //* Start Activated Page =======================================================================================================================================================

        echo "<h1 class ='text-center'> Activate Member</h1>";
        echo "<div class ='container'> ";

            // check if get request userid is numeric & get the integer value of it
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
        
            // Select All Data Depend On this ID
            $check = checkItem("userid","users",$userid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
            
            // if there's Such Id Show the form
            if($check > 0){

                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));

                echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
        
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
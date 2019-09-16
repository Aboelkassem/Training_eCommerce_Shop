<?php

	/*
	================================================
	== Items Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') { //* Start Manage Page ============================================================================================================================= 

            $stmt = $con->prepare("SELECT 
                                        items.* , 
                                        categories.Name AS category_name ,
                                        users.Username AS Member 
                                   FROM 
                                        items

                                   INNER JOIN 
                                        categories 
                                    ON 
                                        categories.ID = items.Cat_ID
            
                                   INNER JOIN 
                                        users 
                                    ON 
                                        users.UserID = items.Member_ID
                                    ORDER BY 
                                        Item_ID DESC");

            $stmt->execute(); // execute the statement

            // Assign all data to variables
            $items = $stmt->fetchAll();

            if(!empty($items)){
        ?>
        <h1 class ='text-center'> <?php echo lang('IT_MANAGE_TITLE') ?> </h1>
        <div class ='container'>
            <div class='table-responsive'>
                <table class = 'main-table text-center table table-bordered'>
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                    
                        foreach($items as $item){
                            echo '<tr>';
                                echo '<td>'. $item['Item_ID'] . '</td>' ;
                                echo "<td>";
                                    if (empty($item['Image'] )) {
                                        echo "<img src='uploads/avatars/defualt_img.png' alt='avatar'/>";
                                    }else{
                                        echo "<img src='uploads/avatars/". $item['Image'] . "' alt='avatar'/>";
                                    }
                                echo "</td>";
                                echo '<td>'. $item['Name'] . '</td>' ;
                                echo '<td>'. $item['Description'] . '</td>' ;
                                echo '<td>'. $item['Price'] . '</td>' ;
                                echo '<td>'. $item['Add_Date']  .'</td>' ;
                                echo '<td>'. $item['category_name'] . '</td>' ;
                                echo '<td>'. $item['Member'] . '</td>' ;
                                echo '<td>
                                    <a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '" class ="btn btn-success"> <i class="fa fa-edit"></i> ' .lang("EDIT_MEMBER") . ' </a>
                                    <a href="items.php?do=Delete&itemid=' . $item['Item_ID'] . '" class ="btn btn-danger confirm"> <i class="fa fa-close"></i> ' . lang("Delete_MEMBER") . ' </a>';
                                    if($item['Approve'] == 0){

                                        echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class ="btn btn-info activate"> <i class="fa fa-check-circle"></i> ' . lang("Approve_MEMBER") . ' </a>';

                                    }
                                echo '</td>' ;
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <a href='items.php?do=Add' class='btn btn-sm btn-primary'><i class='fa fa-plus'></i> <?php echo lang("ADD_ITEMS")?> </a>
        </div>

        <?php } else {
            ?>
                <div class="container">'
                    <div class ="nice-massage">There's No Items To Show </div>'
                    <a href='items.php?do=Add' class='btn btn-sm btn-primary'><i class='fa fa-plus'></i> <?php echo lang("ADD_ITEMS")?> </a>
                </div>
        <?php
        } 
        
		} elseif ($do == 'Add') { //* Start Add Page ============================================================================================================================= 
           ?> 
            <h1 class ='text-center'> <?php echo lang('ADD_ITEM') ?> </h1>
            <div class ='container'>
                <form class='form-horizontal' action ='?do=Insert' method='POST' enctype="multipart/form-data"> <!-- enctype = نوع التشفير بيبعت البيانات ازاى  -->
                    <!-- Start Name Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('NAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='name' 
                                class='form-control' 
                                required='required' 
                                placeholder="Name of the Item"/>
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_DESCRIPTION') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <textarea 
                                name='description' 
                                class='form-control' 
                                required='required' 
                                placeholder="Description of the Item"></textarea>
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Price Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_PRICE') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='price' 
                                class='form-control' 
                                required='required' 
                                placeholder="Price of the Item"/>
                        </div>
                    </div>
                    <!-- End Price Field -->

                    <!-- Start Country Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_COUNTRY') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='country' 
                                class='form-control' 
                                required='required' 
                                placeholder="Country of the Item which made in It"/>
                        </div>
                    </div>
                    <!-- End Country Field -->

                    <!-- Start Status Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_STATIS') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->

                    <!-- Start Members Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_MEMBER') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="member">
                                <option value="0">...</option>
                                <?php
                                    $allMembers = getAllFrom("*" , "users" , '' , '', 'UserID');
                                    foreach($allMembers as $user){
                                        echo "<option value='" . $user['UserID'] . "'> " . $user['Username']  . "</option>";
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->

                    <!-- Start Categoires Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_CATEGOIRY') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="category">
                                <option value="0">...</option>
                                <?php
                                    $allCats = getAllFrom("*" , "categories" , 'WHERE parent = 0' , '', 'ID');
                                    foreach($allCats as $cat){
                                        echo "<option value='" . $cat['ID'] . "'> " . $cat['Name']  . "</option>";
                                        $childCats = getAllFrom("*" , "categories" , "WHERE parent = {$cat['ID']}" , '', 'ID');
                                        foreach ($childCats as $child) {
                                            echo "<option value='" . $child['ID'] . "'>==> " . $child['Name']  . "</option>";
                                        }
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categoires Field -->

                    <!-- Start Tags Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('TAGS') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='tags' 
                                class='form-control' 
                                placeholder="Separate Tags With Comma (,)"/>
                        </div>
                    </div>
                    <!-- End Tags Field -->

                    <!-- Start Avatar Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('ITEM_ABATER') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="file" name='image' class='form-control' required='required'>
                        </div>
                    </div>
                    <!-- End Avatar Field -->

                    <!-- Start submit Field -->
                    <div class ='form-group form-group-lg'>
                        <div class ='col-sm-offset-2 col-sm-10 col-md-6'>
                            <input type="submit" value='<?php echo lang('ADDING_BUTTOM_ITEM') ?>' class='btn btn-sm btn-primary'>
                        </div>
                    </div>
                    <!-- End submit Field -->    
                </form>
            </div>
        
        <?php
		} elseif ($do == 'Insert') { //* Start Insert Page ============================================================================================================================= 
            
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            echo "<h1 class ='text-center'> Insert Item </h1>";
            echo "<div class ='container'> ";

            //* Upload Avatars

            //$avatar = $_FILES['avatar']; // get the info about files in array [name][type][tmp_name][size]
            
            $avatarName = $_FILES['image']['name'];
            $avatarSize = $_FILES['image']['size'];
            $avatarType = $_FILES['image']['type'];
            $avatarTem  = $_FILES['image']['tmp_name']; // الاسم المؤقت 
            
            // List OF Allowed File Typed To Upload
            $avatarAllowedExtension = array("jpeg","jpg","png","gif"); // الحاجات الي انا بسمح بيها ان الشخص يرفعها this is types of photo that i allow to add [Secuirty]

            // Get Avatar Extension

            $avatarExtension = strtolower(end(explode('.',$avatarName))); // end = get final value of array and make it in lower case to check with $avatarAllowedExtension

            // Get Variables from The Form attribute[name]

            $name        = $_POST['name'];
            $desc        = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $status      = $_POST['status'];
            $member      = $_POST['member'];
            $cat         = $_POST['category'];
            $tags        = $_POST['tags'];

            // Validate The Form

            $formErrors = array();

            if(empty($name)) {

                $formErrors[] = 'Name Can\'t Be <strong> Empty </strong>';

            }
            if(empty($desc)){
                
                $formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';

            }
            if(empty($price)){
                
                $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';

            }
            if(empty($country)){
                
                $formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';

            }
            if($status == 0){
                
                $formErrors[] = 'You must Choose The <strong>Status</strong>';

            }
            if($member == 0){
                
                $formErrors[] = 'You must Choose The <strong>Member</strong>';

            }
            if($cat == 0){
                
                $formErrors[] = 'You must Choose The <strong>Categoiry</strong>';

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

                // Insert User Info To Database

                // avatar to send in database [rand()] to prevent image to repeat

                $avatar = rand(0 , 100000000) . '_' . $avatarName ; // Avatar Name

                //move_uploaded_file(filename, destination)
                move_uploaded_file($avatarTem ,"uploads\avatars\\".$avatar); // $avatarTem = Temporary Name ,, function to move files 

                    $stmt = $con->prepare("INSERT INTO 
                                                items(Name, Description, Price, Country_Made, Image ,Status, Add_Date , Cat_ID , Member_ID , tags)
                                            VALUES( :zname , :zdesc , :zprice , :zcountry , :zimage, :zstatus, now() , :zcat , :zmember , :ztags) ");    // keys that will use to define array values
                    $stmt->execute(array(

                        'zname'     => $name ,
                        'zdesc'     => $desc ,
                        'zprice'    => $price ,
                        'zcountry'  => $country ,
                        'zimage'    => $avatar,
                        'zstatus'   => $status,
                        'zcat'      => $cat,
                        'zmember'   => $member,
                        'ztags'     => $tags

                    ));
            
                    // echo Sucessful message

                    $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
                    redirectHome($theMsg , 'back' ,1);

            }

            echo "</div>";

        } else {
            
            echo "<div class='container'>";
            
            $theMsg = '<div class = "alert alert-danger">You Can\'t Browse this page Directory </div>';

            redirectHome($theMsg); // function redirect home showing error massage and time berfore doing that

            echo "</div>";
         }

		} elseif ($do == 'Edit') { 	//* Start Edit Page ============================================================================================================================= 
           
            // check if get request itemid is numberic & get its integer value
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
       
            // Select All Data Depend On this ID
            $stmt = $con-> prepare("SELECT * FROM `items` WHERE Item_ID =  ? ");
            $stmt-> execute(array($itemid)); // execute query
            $item = $stmt-> fetch();         // get Data from database and print it in array
            $count = $stmt->rowCount();     // get the record or count that found or effected
    
            // if there's Such Id Show the form
            if($count > 0){ ?>
            
            <h1 class ='text-center'> <?php echo lang('EDIT_ITEM') ?> </h1>
            <div class ='container'>
                <form class='form-horizontal' action ='?do=Update' method='POST' enctype="multipart/form-data"> <!-- enctype = نوع التشفير بيبعت البيانات ازاى  -->
                    <input type="hidden" name='itemid' value='<?php echo $itemid ?>'> 
                    <!-- Start Name Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('NAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='name' 
                                class='form-control' 
                                required='required' 
                                placeholder="Name of the Item"
                                value ="<?php echo $item['Name']?> "/>
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_DESCRIPTION') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type='text'
                                name='description' 
                                class='form-control' 
                                required='required' 
                                placeholder="Description of the Item"
                                value ="<?php echo $item['Description']?> "/>
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Price Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_PRICE') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='price' 
                                class='form-control' 
                                required='required' 
                                placeholder="Price of the Item"
                                value ="<?php echo $item['Price']?> "/>
                        </div>
                    </div>
                    <!-- End Price Field -->

                    <!-- Start Country Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_COUNTRY') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='country' 
                                class='form-control' 
                                required='required' 
                                placeholder="Country of the Item which made in It"
                                value ="<?php echo $item['Country_Made']?> "/>
                        </div>
                    </div>
                    <!-- End Country Field -->

                    <!-- Start Status Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_STATIS') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="status">
                                <option value="1" <?php if ($item['Status'] == 1){echo 'selected';} ?> >New</option>
                                <option value="2" <?php if ($item['Status'] == 2){echo 'selected';} ?>>Like New</option>
                                <option value="3" <?php if ($item['Status'] == 3){echo 'selected';} ?>>Used</option>
                                <option value="4" <?php if ($item['Status'] == 4){echo 'selected';} ?>>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->

                    <!-- Start Members Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_MEMBER') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="member">
                                <?php

                                    $allMembers = getAllFrom("*" , "users" , '' , '', 'UserID');
                                    foreach($allMembers as $user){
                                        echo "<option value='" .  $user['UserID'] . "'"; 
                                        if ($item['Member_ID'] ==  $user['UserID'] ){echo 'selected';} 
                                        echo ">" . $user['Username']  . "</option>";
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->

                    <!-- Start Categoires Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('IT_CATEGOIRY') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="category">
                                <?php

                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach($cats as $cat){
                                        echo "<option value='" . $cat['ID'] . "'";
                                        if ($item['Cat_ID'] ==  $cat['ID'] ){echo 'selected';} 
                                        echo "> " . $cat['Name']  . "</option>";
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categoires Field -->

                    <!-- Start Tags Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('TAGS') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input 
                                type="text" 
                                name='tags' 
                                class='form-control' 
                                placeholder="Separate Tags With Comma (,)"
                                value ="<?php echo $item['tags']?> "/>
                        </div>
                    </div>
                    <!-- End Tags Field -->

                    <!-- Start Avatar Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('ITEM_ABATER') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="file" name='image' class='form-control' required='required'>
                        </div>
                    </div>
                    <!-- End Avatar Field -->           

                    <!-- Start submit Field -->
                    <div class ='form-group form-group-lg'>
                        <div class ='col-sm-offset-2 col-sm-10 col-md-6'>
                            <input type="submit" value='<?php echo lang('SAVE_BUTTOM_ITEM') ?>' class='btn btn-sm btn-primary'>
                        </div>
                    </div>
                    <!-- End submit Field -->    
                </form>
                
                <?php
                    $stmt = $con->prepare("SELECT 
                                            comments.*, users.Username As Members
                                        FROM 
                                            comments
                                        INNER JOIN 
                                            users
                                        ON
                                            users.UserID = comments.user_id 
                                        WHERE 
                                        item_id = ?");
                $stmt->execute(array($itemid)); // execute the statement

                // Assign all data to variables
                $rows = $stmt->fetchAll();

                if(!empty($rows)){
            
            ?>
            <h1 class ='text-center'> Manage [<?php echo $item['Name']?>] Comments</h1>
                <div class='table-responsive'>
                    <table class = 'main-table text-center table table-bordered'>
                        <tr>
                            <td>Comment</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>

                        <?php 
                        
                            foreach($rows as $row){
                                echo '<tr>';
                                    echo '<td>'. $row['comment'] . '</td>' ;
                                    echo '<td>'. $row['Members'] . '</td>' ;
                                    echo '<td>'. $row['comment_date']  .'</td>' ;
                                    echo '<td>
                                        <a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class ="btn btn-success"> <i class="fa fa-edit"></i> ' .lang("EDIT_MEMBER") . ' </a>
                                        <a href="comments.php?do=Delete&comid=' . $row['c_id'] . '" class ="btn btn-danger confirm"> <i class="fa fa-close"></i> ' . lang("Delete_MEMBER") . ' </a>';

                                        if($row['status'] == 0){

                                            echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '" class ="btn btn-info activate"> <i class="fa fa-check-circle"></i> ' . lang("APPROVE_COMMENT") . ' </a>';

                                        }

                                    echo '</td>' ;
                                echo '</tr>';
                            }
                        ?>
                    </table>
                </div>
                <?php } ?>
            </div>
                
        <?php
    
            // IF there's No such ID in database Show this message
    
            } else{
    
                echo "<div class='container'>";
                
                $theMsg = '<div class ="alert alert-danger">There\'s No Such ID </div>';
    
                redirectHome($theMsg); // function redirect home showing error massage and time berfore doing that
    
                echo "</div>";
            }

		} elseif ($do == 'Update') { //* Start Update Page ============================================================================================================================= 
            
            echo "<h1 class ='text-center'> Update Item</h1>";
            echo "<div class ='container'> ";
    
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
             
            //* Upload Avatars

            //$avatar = $_FILES['avatar']; // get the info about files in array [name][type][tmp_name][size]
            
            $avatarName = $_FILES['image']['name'];
            $avatarSize = $_FILES['image']['size'];
            $avatarType = $_FILES['image']['type'];
            $avatarTem  = $_FILES['image']['tmp_name']; // الاسم المؤقت 
            
            // List OF Allowed File Typed To Upload
            $avatarAllowedExtension = array("jpeg","jpg","png","gif"); // الحاجات الي انا بسمح بيها ان الشخص يرفعها this is types of photo that i allow to add [Secuirty]

            // Get Avatar Extension

            $avatarExtension = strtolower(end(explode('.',$avatarName))); // end = get final value of array and make it in lower case to check with $avatarAllowedExtension



                // Get Variables from The Form attribute[name]
    
                $id             = $_POST['itemid'];
                $name           = $_POST['name'];
                $desc           = $_POST['description'];
                $price          = $_POST['price'];
                $country        = $_POST['country'];
                $status         = $_POST['status'];
                $cat            = $_POST['category'];
                $member         = $_POST['member'];
                $tags           = $_POST['tags'];
    
                // Validate The Form

                $formErrors = array();

                if(empty($name)) {

                    $formErrors[] = 'Name Can\'t Be <strong> Empty </strong>';

                }
                if(empty($desc)){
                    
                    $formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';

                }
                if(empty($price)){
                    
                    $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';

                }
                if(empty($country)){
                    
                    $formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';

                }
                if($status == 0){
                    
                    $formErrors[] = 'You must Choose The <strong>Status</strong>';

                }
                if($member == 0){
                    
                    $formErrors[] = 'You must Choose The <strong>Member</strong>';

                }
                if($cat == 0){
                    
                    $formErrors[] = 'You must Choose The <strong>Categoiry</strong>';

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
    
                    // Update This Database With This info

                // avatar to send in database [rand()] to prevent image to repeat

                $avatar = rand(0 , 100000000) . '_' . $avatarName ; // Avatar Name

                //move_uploaded_file(filename, destination)
                move_uploaded_file($avatarTem ,"uploads\avatars\\".$avatar); // $avatarTem = Temporary Name ,, function to move files 

    
                    $stmt = $con->prepare("UPDATE 
                                                items
                                            SET 
                                                Name = ? , 
                                                Description = ? , 
                                                Price = ? , 
                                                Country_Made = ?,
                                                Status = ? ,
                                                Cat_ID = ? ,
                                                Member_ID = ? ,
                                                tags = ? ,
                                                Image = ?
                                                 
                                            WHERE 
                                                Item_ID = ? ");

                    $stmt-> execute(array($name, $desc, $price, $country, $status ,$cat ,$member, $tags, $avatar,$id));
                    
                    // echo Sucessful message
    
                    $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
                    redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
    
                }
    
                echo "</div>";
                
            } else {
    
                $theMsg = '<div class="alert alert-danger">You Can\'t Browse this page Directory </div>';
                redirectHome($theMsg , 'back' , 5); // function redirect home showing error massage and time berfore doing that
    
            }

		} elseif ($do == 'Delete') { //* Start Delete Page ============================================================================================================================= 
           
            echo "<h1 class ='text-center'> Delete Item </h1>";
            echo "<div class ='container'> ";
    
                // check if get request itemid is numeric & get the integer value of it
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
            
                // Select All Data Depend On this ID
                $check = checkItem("Item_ID","items",$itemid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
                
                // if there's Such Id Show the form
                if($check > 0){
    
                    $stmt = $con->prepare('DELETE FROM items WHERE Item_ID = :zitemid');
                    $stmt->bindParam(":zitemid", $itemid); // relate the $itemid with Item_ID(الاستعلام) like makeing => UserID = $userid
                    $stmt->execute();
    
                    echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Deleted </div>';
            
                    redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
        
                    echo "</div>";
    
                }else{
    
                    $theMsg ='<div class = "alert alert-danger"> This ID is Not Exist </div>';
                    redirectHome($theMsg , 'back' , 4); // function redirect home showing error massage and time berfore doing that
    
                }
            echo '</div>';

		} elseif ($do == 'Approve') { //* Start Approve Page ============================================================================================================================= 

            echo "<h1 class ='text-center'> Approve Item</h1>";
            echo "<div class ='container'> ";
    
                // check if get request userid is numeric & get the integer value of it
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
            
                // Select All Data Depend On this ID
                $check = checkItem("Item_ID","items",$itemid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
                
                // if there's Such Id Show the form
                if($check > 0){
    
                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
                    $stmt->execute(array($itemid));
    
                    echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
            
                    redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
        
                    echo "</div>";
    
                }else{
    
                    $theMsg ='<div class = "alert alert-danger"> This ID is Not Exist </div>';
                    redirectHome($theMsg , 4); // function redirect home showing error massage and time berfore doing that
    
                }
            echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>
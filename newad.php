<?php
	ob_start(); // Output Buffering Start
    session_start();

    $pageTitle = 'Create New Item'; // function getTitle();

    include 'init.php';

    if(isset($_SESSION['user'])){


        if($_SERVER['REQUEST_METHOD']=='POST'){

            $formErrors = array();

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

            $name       = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);          // return string as secuirty
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);    // return string as secuirty
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);      // return number [integer] as secuirty
            $country    = filter_var($_POST['country'] , FILTER_SANITIZE_STRING);       // return string as secuirty
            $status     = filter_var($_POST['status'] , FILTER_SANITIZE_NUMBER_INT);    // return number [integer] as secuirty
            $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);   // return number [integer] as secuirty
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(strlen($name) < 4 ){
                $formErrors[] = 'Item Title Must Be 4 Characters At least';
            }
            
            if(strlen($desc) < 10 ){
                $formErrors[] = 'Item Description Must Be 10 Characters At least';
            }

            if(strlen($country) < 2 ){
                $formErrors[] = 'Item Country Must Be 2 Characters At least';
            }

            if(empty(strlen($price))){
                $formErrors[] = 'Item Price Must Be Not Empty';
            }

            if(empty(strlen($status))){
                $formErrors[] = 'Item status Must Be Not Empty';
            }
            
            if(empty(strlen($category))){
                $formErrors[] = 'Item category Must Be Not Empty';
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
            
            // check if there's no error proced or continue The Update Operation

            if(empty($formErrors)){

                // Insert User Info To Database

                // avatar to send in database [rand()] to prevent image to repeat

                $avatar = rand(0 , 100000000) . '_' . $avatarName ; // Avatar Name

                //move_uploaded_file(filename, destination)
                move_uploaded_file($avatarTem ,"admin\uploads\avatars\\".$avatar); // $avatarTem = Temporary Name ,, function to move files 


                    $stmt = $con->prepare("INSERT INTO 
                                                items(Name, Description, Price, Country_Made, Status, Add_Date , Cat_ID , Member_ID , tags ,Image)
                                            VALUES( :zname , :zdesc , :zprice , :zcountry , :zstatus, now() , :zcat , :zmember , :ztags , :zimage) ");    // keys that will use to define array values
                    $stmt->execute(array(

                        'zname'     => $name ,
                        'zdesc'     => $desc ,
                        'zprice'    => $price ,
                        'zcountry'  => $country ,
                        'zstatus'   => $status,
                        'zcat'      => $category,
                        'zmember'   => $_SESSION['uid'],
                        'ztags'     => $tags,
                        'zimage'    => $avatar

                    ));
                    
                    if ($stmt){
                        // echo Sucessful message
                        $successMsg = 'Item Added';
                    }else{
                        echo 'There is an Error in Database'.mysql_error();
                    }
            }

        }

?>

<h1 class="text-center"><?php echo $pageTitle; ?></h1>

<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $pageTitle; ?> </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class='form-horizontal main-form' action ='<?php echo $_SERVER['PHP_SELF'] ?>' method='POST' enctype="multipart/form-data"> <!-- enctype = نوع التشفير بيبعت البيانات ازاى  -->
                        <!-- Start Name Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-3 control-label'><?php echo lang('NAME') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <input
                                    pattern=".{4,32}"
                                    title="You must write 4 characters at least and 32 at most"
                                    type="text" 
                                    name='name' 
                                    class='form-control live' 
                                    placeholder="Name of the Item"
                                    data-class=".live-title"
                                    required
                                    />
                            </div>
                        </div>
                        <!-- End Name Field -->

                        <!-- Start Description Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-3 control-label'><?php echo lang('IT_DESCRIPTION') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <textarea 
                                    pattern=".{10,}"
                                    title="You must write 10 characters at least"
                                    name='description' 
                                    class='form-control live' 
                                    data-class=".live-desc"
                                    required
                                    placeholder="Description of the Item"></textarea>
                            </div>
                        </div>
                        <!-- End Description Field -->

                        <!-- Start Price Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-3 control-label'><?php echo lang('IT_PRICE') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <input 
                                    type="text" 
                                    name='price' 
                                    class='form-control live' 
                                    placeholder="Price of the Item"
                                    data-class=".live-price"
                                    required
                                    />
                            </div>
                        </div>
                        <!-- End Price Field -->

                        <!-- Start Country Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-3 control-label'><?php echo lang('IT_COUNTRY') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <input 
                                    type="text" 
                                    name='country' 
                                    class='form-control'
                                    required
                                    placeholder="Country of the Item which made in It"/>
                            </div>
                        </div>
                        <!-- End Country Field -->

                        <!-- Start Status Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-3 control-label'><?php echo lang('IT_STATIS') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <select name="status" required>
                                    <option value="">...</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">Very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Field -->

                        <!-- Start Categoires Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-3 control-label'><?php echo lang('IT_CATEGOIRY') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <select name="category" required>
                                    <option value="">...</option>
                                    <?php

                                        $cats = getAllFrom('*' , 'categories','' ,'' ,'ID'); // Select * from `categories` order by ID DESC
                                        foreach($cats as $cat){
                                            echo "<option value='" . $cat['ID'] . "'> " . $cat['Name']  . "</option>";
                                        }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Categoires Field -->

                        <!-- Start Tags Field -->
                            <div class ='form-group form-group-lg'>
                                <label class ='col-sm-3 control-label'><?php echo lang('TAGS') ?></label>
                                <div class = 'col-sm-10 col-md-9'>
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
                            <label class ='col-sm-3 control-label'><?php echo lang('USER_ABATER') ?></label>
                            <div class = 'col-sm-10 col-md-9'>
                                <input type="file" name='image' class='form-control' required='required'>
                            </div>
                        </div>
                        <!-- End Avatar Field -->

                        <!-- Start submit Field -->
                        <div class ='form-group form-group-lg'>
                            <div class ='col-sm-offset-3 col-sm-9 col-md-9'>
                                <input type="submit" value='<?php echo lang('ADDING_BUTTOM_ITEM') ?>' class='btn btn-sm btn-primary'>
                            </div>
                        </div>
                        <!-- End submit Field -->
                    </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">
                                $<span class="live-price">0</span>
                            </span>
                            <img class="img-responsive" src="img.png" alt=""/>
                            <div class="caption">
                                <h3 class="live-title">Title</h3>
                                <p class="live-desc">Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start Looping Through Erros -->
                    <?php

                     if (!empty($formErrors)){
                         foreach($formErrors as $error){
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                         }
                     }
                     if(isset($successMsg)){
                        echo '<div class="alert alert-success">' . $successMsg .'</div>';
                    }

                    ?>
                <!-- End Looping Through Erros -->
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

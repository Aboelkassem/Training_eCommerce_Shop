<?php

	/*
	================================================
	== Categoires Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Categoires';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') { //* Start Manage Page ============================================================================================================================= 

            $sort = 'ASC'; // The Ordering Sort Defualt
            $sort_array = array('ASC','DESC');

            if( isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll(); 
            
            if (!empty($cats)){
            ?>
            <h1 class='text-center'><?php echo lang('MANAGE_CATEGOIRES') ?></h1>
            <div class="container categoires">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <i class='fa fa-edit'></i> <?php echo lang('MANAGE_CATEGOIRES') ?>
                     <div class="option pull-right">
                        <i class='fa fa-sort'></i> <?php echo lang('ORDERING') ?> :[ 
                        <a class="<?php if($sort =='ASC'){echo 'active';} ?>" href="?sort=ASC"><?php echo lang('ASC') ?></a> |
                        <a class="<?php if($sort =='DESC'){echo 'active';} ?>" href="?sort=DESC"><?php echo lang('DECS') ?></a> ]
                        <i class='fa fa-eye'></i> <?php echo lang('VIEW') ?> : [
                        <span class='active' data-view='full'><?php echo lang('FULL') ?></span> |
                        <span data-view='classic'><?php echo lang('CLASSIC') ?></span> ]
                     </div>
                    </div>  
                    <div class="panel-body">
                        <?php
                         foreach($cats as $cat){
                            echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'> <i class='fa fa-edit'></i> Edit </a>";
                                    echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'> <i class='fa fa-close'></i> Delete </a>";
                                echo "</div>";
                                echo "<h3>".$cat['Name']."</h3>";
                                echo "<div class ='full-view'>";
                                    echo "<p>"; if($cat['Description'] == ''){echo 'This Categoiry Has No Description';}else{echo $cat['Description'];} echo "</p>";
                                    if($cat['Visibility'] == 1 ){ echo '<span class="visibility"><i class="fa fa-eye-slash"></i> Hidden</span>'; };
                                    if($cat['Allow_Comment'] == 1 ){ echo '<span class="commenting"><i class="fa fa-comment-slash"></i> Comment Disabled</span>'; };
                                    if($cat['Allow_Ads'] == 1 ){ echo '<span class="advertises"><i class="fa fa-times-circle"></i> Ads Disabled</span>'; };
                                echo '</div>';
                                // Get Child Category [Sub Categories]

                                $childCats = getAllFrom("*" , "categories" , "WHERE parent = {$cat['ID']}", "", "ID" ,'ASC');
                                if (!empty($childCats)) {

                                    echo "<h4 class='child-head'><i class='glyphicon glyphicon-sort-by-attributes'></i> Sub Categoires </h4>";
                                    echo "<ul class='list-unstyled child-cats'>";
                                        foreach($childCats as $c){
                                          echo "<li class='child-link'>
                                                <a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
                                                <a href='categories.php?do=Delete&catid=" . $c['ID'] . "' class='show-delete confirm'>Delete</a>
                                            </li>";
                                        }
                                    echo "</ul>";
                                 }
                            echo "</div>";
                            echo "<hr>";
                            }
                        ?>
                    </div>
                </div>
                <a href='categories.php?do=Add' class='add-category btn btn-primary'><i class='fa fa-plus'></i> Add Categoires </a>
            </div>

            <?php } else {
            ?>
                <div class="container">'
                    <div class ="nice-massage">There's No Categoires To Show </div>'
                    <a href='categories.php?do=Add' class='add-category btn btn-primary'><i class='fa fa-plus'></i> Add Categoires </a>
                </div>
        
        <?php
        } 
            
        } elseif ($do == 'Add') {  //* Start Add Page ============================================================================================================================= 
        ?>

            <h1 class ='text-center'> <?php echo lang('ADD_GATEGOIRY') ?> </h1>
            <div class ='container'>
                <form class='form-horizontal' action ='categories.php?do=Insert' method='POST'>
                    <!-- Start Name Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('NAME') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='name' class='form-control' autocomplete='off' required='required'placeholder="Name of the Gategoiry"/>
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('Description') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='description' class='form-control' placeholder="Describe The Categoiry">
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Ordering Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('ORDERING') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <input type="text" name='ordering'  class='form-control'  placeholder="Number To Arrange The Categoires">
                        </div>
                    </div>
                    <!-- End Ordering Field -->

                    <!-- Start Gategory Type -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('CAT_TYPE') ?></label> <!-- Parent [value 0] mean that this is Main Category .. [value 1] mean it's Sub Category -->
                        <div class = 'col-sm-10 col-md-6'>
                            <select name="parent">
                                <option value="0">None</option>
                                <?php 

                                $allCats = getAllFrom("*" , "categories" , "WHERE parent = 0", "" , "ID");

                                foreach ($allCats as $cat) {
                                    echo "<option value='" . $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Gategory Type  -->

                    <!-- Start Visibility Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('VISIABLE') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value='0' Checked >
                                <label for="vis-yes"><?php echo lang("YES") ?></label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value='1'>
                                <label for="vis-no"><?php echo lang("NO") ?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Field -->

                    <!-- Start Commenting Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('ALLOW_COMMENTING') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value='0' Checked >
                                <label for="com-yes"><?php echo lang("YES") ?></label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value='1'>
                                <label for="com-no"><?php echo lang("NO") ?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Field -->  

                    <!-- Start Ads Field -->
                    <div class ='form-group form-group-lg'>
                        <label class ='col-sm-2 control-label'><?php echo lang('ALLOW_ADS') ?></label>
                        <div class = 'col-sm-10 col-md-6'>
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value='0' Checked >
                                <label for="ads-yes"><?php echo lang("YES") ?></label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value='1'>
                                <label for="ads-no"><?php echo lang("NO") ?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Field -->

                    <!-- Start submit Field -->
                    <div class ='form-group form-group-lg'>
                        <div class ='col-sm-offset-2 col-sm-10 col-md-6'>
                            <input type="submit" value='<?php echo lang('ADDING_BUTTOM_CATEGOIRY') ?>' class='btn btn-primary'>
                        </div>
                    </div>
                    <!-- End submit Field -->    
                </form>
            </div>

        <?php 
		} elseif ($do == 'Insert') { //* Start Insert Page ============================================================================================================================= 

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
                echo "<h1 class ='text-center'> Insert Categoiry </h1>";
                echo "<div class ='container'> ";
        
                // Get Variables from The Form attribute[name]
    
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $parent   = $_POST['parent'];
                $order    = $_POST['ordering'];
                $visible  = $_POST['visibility'];
                $comment  = $_POST['commenting'];
                $ads      = $_POST['ads'];
                

                // check categoiry the username is exist in database

                $check = checkItem("Name","categories",$name); // SELECT Username FROM users WHERE Username = $user //* look at checkItem function.php in includes 

                if($check == 1){

                    $theMsg = '<div class = "alert alert-danger">Sorry This Categoiry Is Exist , You can\'t add it </div>';
                    redirectHome($theMsg , 'back' , 4);

                }else {

                // Insert Categoiry Info To Database

                    $stmt = $con->prepare("INSERT INTO 
                                                categories(Name, Description, parent ,Ordering, Visibility , Allow_Comment, Allow_Ads)
                                            VALUES( :zname , :zdesc , :zparent ,:zorder , :zvisible , :zcomment, :zads )");    // keys that will use to define array values
                    $stmt->execute(array(

                        'zname'     => $name ,
                        'zdesc'     => $desc ,
                        'zparent'   => $parent ,
                        'zorder'    => $order ,
                        'zvisible'  => $visible,
                        'zcomment'  => $comment,
                        'zads'      => $ads

                    ));
            
                    // echo Sucessful message

                    $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Inserted </div>';
                    redirectHome($theMsg , 'back' ,1);
                }

            echo "</div>";
    
            } else {
                
                echo "<div class='container'>";
                
                $theMsg = '<div class = "alert alert-danger">You Can\'t Browse this page Directory </div>';
    
                redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
    
                echo "</div>";
             }
    
		} elseif ($do == 'Edit') { 	//* Start Edit Page ============================================================================================================================= 
            
            // Check If Get Request catID Is numberic and get it's integer value
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
       
            // Select All Data Depend On this ID
            $stmt = $con-> prepare("SELECT * FROM `categories` WHERE ID =  ? ");
            $stmt-> execute(array($catid)); // execute query
            $cat = $stmt-> fetch();         // get Data from database and print it in array
            $count = $stmt->rowCount();     // get the record or count that found or effected
    
            // if there's Such Id Show the form
            if($count > 0){ ?>

                <h1 class ='text-center'> <?php echo lang('EDIT_GATEGOIRY') ?> </h1>
                <div class ='container'>
                    <form class='form-horizontal' action ='categories.php?do=Update' method='POST'>
                        <input type="hidden" name='catid' value='<?php echo $catid ?>'> 
                        <!-- Start Name Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-2 control-label'><?php echo lang('NAME') ?></label>
                            <div class = 'col-sm-10 col-md-6'>
                                <input type="text" name='name' class='form-control' required='required'placeholder="Name of the Gategoiry" value="<?php echo $cat['Name'];?>"/>
                            </div>
                        </div>
                        <!-- End Name Field -->

                        <!-- Start Description Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-2 control-label'><?php echo lang('Description') ?></label>
                            <div class = 'col-sm-10 col-md-6'>
                                <input type="text" name='description' class='form-control' placeholder="Describe The Categoiry" value="<?php echo $cat['Description'];?>">
                            </div>
                        </div>
                        
                        <!-- End Description Field -->

                        <!-- Start Ordering Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-2 control-label'><?php echo lang('ORDERING') ?></label>
                            <div class = 'col-sm-10 col-md-6'>
                                <input type="text" name='ordering'  class='form-control'  placeholder="Number To Arrange The Categoires" value="<?php echo $cat['Ordering'];?>">
                            </div>
                        </div>
                        <!-- End Ordering Field -->

                        <!-- Start Gategory Type -->
                            <div class ='form-group form-group-lg'>
                                <label class ='col-sm-2 control-label'><?php echo lang('CAT_TYPE') ?></label> <!-- Parent [value 0] mean that this is Main Category .. [value 1] mean it's Sub Category -->
                                <div class = 'col-sm-10 col-md-6'>
                                    <select name="parent">
                                        <option value="0">None</option>
                                        <?php 

                                        $allCats = getAllFrom("*" , "categories" , "WHERE parent = 0", "" , "ID");
                                        foreach ($allCats as $c) {
                                            echo "<option value='" . $c['ID'] ."'";
                                                if($cat['parent'] == $c['ID']){echo 'selected';}
                                            echo ">" . $c['Name'] . "</option>";
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>
                        <!-- End Gategory Type  -->

                        <!-- Start Visibility Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-2 control-label'><?php echo lang('VISIABLE') ?></label>
                            <div class = 'col-sm-10 col-md-6'>
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value='0' <?php if( $cat['Visibility'] == 0){echo 'checked';} ?> >
                                    <label for="vis-yes"><?php echo lang("YES") ?></label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value='1' <?php if( $cat['Visibility'] == 1){echo 'checked';} ?> > 
                                    <label for="vis-no"><?php echo lang("NO") ?></label>
                                </div>
                            </div>
                        </div>
                        <!-- End Visibility Field -->

                        <!-- Start Commenting Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-2 control-label'><?php echo lang('ALLOW_COMMENTING') ?></label>
                            <div class = 'col-sm-10 col-md-6'>
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value='0' <?php if( $cat['Allow_Comment'] == 0){echo 'checked';} ?> >
                                    <label for="com-yes"><?php echo lang("YES") ?></label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value='1'<?php if( $cat['Allow_Comment'] == 1){echo 'checked';} ?>>
                                    <label for="com-no"><?php echo lang("NO") ?></label>
                                </div>
                            </div>
                        </div>
                        <!-- End Commenting Field -->  

                        <!-- Start Ads Field -->
                        <div class ='form-group form-group-lg'>
                            <label class ='col-sm-2 control-label'><?php echo lang('ALLOW_ADS') ?></label>
                            <div class = 'col-sm-10 col-md-6'>
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value='0' <?php if( $cat['Allow_Ads'] == 0){echo 'checked';} ?> >
                                    <label for="ads-yes"><?php echo lang("YES") ?></label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value='1' <?php if( $cat['Allow_Ads'] == 1){echo 'checked';} ?>>
                                    <label for="ads-no"><?php echo lang("NO") ?></label>
                                </div>
                            </div>
                        </div>
                        <!-- End Ads Field -->

                        <!-- Start submit Field -->
                        <div class ='form-group form-group-lg'>
                            <div class ='col-sm-offset-2 col-sm-10 col-md-6'>
                                <input type="submit" value='<?php echo lang('SAVING_BUTTOM') ?>' class='btn btn-primary'>
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

		} elseif ($do == 'Update') { //* Start Update Page ============================================================================================================================= 
           
            echo "<h1 class ='text-center'> Update Categoiry </h1>";
            echo "<div class ='container'> ";
    
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
             
                // Get Variables from The Form attribute[name]
    
                $id         = $_POST['catid'];
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $parent     = $_POST['parent'];
                $order      = $_POST['ordering'];
                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['ads'];

                // Update This Database With This info

                $stmt = $con->prepare(' UPDATE
                                            `categories`
                                        SET
                                            Name = ? ,
                                            Description = ? ,
                                            parent = ? ,  
                                            Ordering = ? , 
                                            Visibility = ? , 
                                            Allow_Comment = ?, 
                                            Allow_Ads = ? 
                                        WHERE
                                            ID = ? ');
                $stmt-> execute(array($name, $desc, $parent, $order, $visible, $comment , $ads, $id));
                
                // echo Sucessful message

                $theMsg = '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Updated </div>';
                redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that

                echo "</div>";
                
            } else {
    
                $theMsg = '<div class="alert alert-danger">You Can\'t Browse this page Directory </div>';
                redirectHome($theMsg , 'back' , 5); // function redirect home showing error massage and time berfore doing that
    
            }

        } elseif ($do == 'Delete') { //* Start Delete Page ============================================================================================================================= 
           
            echo "<h1 class ='text-center'> Delete Member</h1>";
            echo "<div class ='container'> ";
    
                // check if get request catid is numeric & get the integer value of it
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
            
                // Select All Data Depend On this ID
                $check = checkItem("ID","categories",$catid); // SELECT userid FROM users WHERE userid = $user //* look at checkItem function.php in includes 
                
                // if there's Such Id Show the form
                if($check > 0){
    
                    $stmt = $con->prepare('DELETE FROM categories WHERE ID = :zid');
                    $stmt->bindParam(":zid", $catid); // relate the $userid with UserID(الاستعلام) like makeing => UserID = $userid
                    $stmt->execute();
    
                    echo '<div class = "alert alert-success">'. $stmt->rowCount() . ' Record Deleted </div>';
            
                    redirectHome($theMsg , 'back' , 1); // function redirect home showing error massage and time berfore doing that
        
                    echo "</div>";
    
                }else{
    
                    $theMsg ='<div class = "alert alert-danger"> This ID is Not Exist </div>';
                    redirectHome($theMsg , 'back' , 4); // function redirect home showing error massage and time berfore doing that
    
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
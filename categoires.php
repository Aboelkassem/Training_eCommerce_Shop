<?php
	ob_start(); // Output Buffering Start
    session_start();
include 'init.php'; ?>

<div class ="container">
    <h1 class='text-center'>Show Category Items</h1> 
    <div class="row">
        <?php
        //$category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;  // check if the user id is only number ,true put value in integer [$userid]
        if(isset($_GET['pageid']) && is_numeric($_GET['pageid']) ){
        $category = intval($_GET['pageid']) ;

        $allItems = getAllFrom("*" , "items" , "WHERE Cat_ID = {$category}" ,"AND Approve = 1" ,"Item_ID");
            foreach($allItems as $item){ // get all data Depend on categoiry ID which send it link ==> WHERE Cat_ID =ID of the page [Relations]
                echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
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
        } else {
            echo '<div class="container">';
                echo '<div class="alert alert-danger">Undefined Page ID</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<?php
include $tpl . "footer.php"; 

ob_end_flush(); // Release The Output
?>

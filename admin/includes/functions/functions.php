<?php

 /*
 * get All Function v2.0
 * Function To get All Records From Any Table in Database
 */

function getAllFrom($field, $table, $where = NULL ,$and = NULL, $orderField, $ordering = "DESC") { // if let where is empty , it will get all items , if you wanna to write , write the query [WHERE Approve = 1]

    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();

    return $all;

 }



 

/* 
* title function v1.0
* Title Function That echo The Page Title In Case The Page has the variable $pageTitle and echo Defualt Title for other pages
*/

 function getTitle(){

    global $pageTitle;

    if(isset($pageTitle)){

        echo $pageTitle;

    }else {

        echo 'eCommerce';

    }

 }


 /*  
 * Home Redirect Function v2.0
 * This Function Accept Paramerters
 * $theMsg   = Echo Ther Massage [ error | success | warning ]
 * $url      = the link which you want to redirect to 
 * $seconds  = seconds or delay before Redirecting
 */

 function redirectHome($theMsg , $url = null ,$seconds = 3){

    if($url === null) { // if don't write url in function or make it empty

        $url ='index.php';
        $link = 'Home Page';

    }else {
        // advanced if
        //$url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='' ? $_SERVER['HTTP_REFERER'] : 'index.php';

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='' ){ // if server request in set and not empty

            $url = $_SERVER['HTTP_REFERER']; // http request that you come from it [back site]
            $link = 'Pervious Page';
            
        }else {

            $url ='index.php';
            $link = 'Home Page';

        }

    }

    echo $theMsg ;
    
    echo "<div class='alert alert-info'>You will be Redirected To $link After $seconds Seconds </div>";

    header("refresh:$seconds;url=$url");            // refresh [the time will take before going to url]
    exit();

 }


 /*
 * Check Item Function v1.0
 * Function to check item in database [function accept parameters]
 * $select = the item to select [example : user , item , catagery]
 * $from   = the table to select from [example :users , items , catageries]
 * $value  = the value of select [example : mohamed , box , electroincs]
 */

 function checkItem($select , $from , $value ){

    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statement->execute(array($value));

    $count = $statement->rowCount();

    return $count;

 }


 /*
 * Count Number of Items Function v1.0
 * function to count number of items rows
 * $item  = the item to count
 * $table = the table to choose From
 */

 function countItems($item , $table){

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();

 }

 /*
 * get Latest Records Function v1.0
 * Function To get Latest Items From Database [Users , Items , Comments]
 * $select = Field To select
 * $table = the table to choose from
 * $limit = number of records to get
 * $order = the column which you want to order it DESC
 */

 function getLatest($select , $table , $order ,$limit = 5) {

    global $con;

    $getStmt = $con->prepare("SELECT $select FROM $table  ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();

    return $rows;

 }
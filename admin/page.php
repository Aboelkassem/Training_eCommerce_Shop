<?php

    /*
        Categories => [ Mange | Edit | Update | Add | Insert | Delete | Stats ]

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';  // داله if المختصرة  condition ? true : false
    */

    $do = '';

    if (isset($_GET['do'])) {

        $do = $_GET['do'];

    }else {

        $do = 'Manage';

    }

    // if the page is main page

    if ($do == 'Manage' ){

        echo 'Welcome you are in Manage Category Page ';
        echo '<a href="page.php?do=Add"> Add New Category + </a>';

    }elseif ($do == 'Add') {

        echo 'Welcome you are in Add Category Page ';

    }elseif ($do == 'Insert') {

        echo 'Welcome you are in Insert Category Page ';

    }else {
         
        echo 'Error There Is No Page With this Name ';

    }
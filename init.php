<?php

    //* Error Reporting [Show Errors]

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    $sessionUser = '';              // it's to check if the session is registered to put it in any place [Secuirty]
    if(isset($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
    }

    include 'admin/connect.php';         // connect to database

    //* routes [to more control in admin as control panal ]

    $tpl  = 'includes/templates/';   // templete path [directory]
    $lang = 'includes/languages/';   // languages path [directory]
    $func = 'includes/functions//';  // functions path [directory]
    $css  = 'layout/css/';           // css Path [ directory ]
    $js   = 'layout/js/';            // js path [ directory]

    //* include the important Files
    include $func .'functions.php';
    include $lang . 'english.php';  
    include $tpl .  "header.php";

<?php

    include 'connect.php';         // connect to database

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


    // include Navbar on all pages expect The One With $noNavbar Variable

    if(!isset($noNavbar)){
        include $tpl .  "navbar.php";
    }
  
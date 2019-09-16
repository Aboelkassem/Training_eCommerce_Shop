<?php

    $dsn  ='mysql:host=localhost;dbname=shop';
    $user ='root';
    $pass = '01127244130';
    $option = array(
        
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',

    );

    try{

        $con = NEW PDO($dsn , $user , $pass , $option);
        $con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    }
    catch(PDOException $e){
        echo 'Faild To Connect' . $e->getMessage();
    }
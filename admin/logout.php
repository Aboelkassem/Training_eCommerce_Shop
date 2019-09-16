<?php

    session_start();    // Start the session

    session_unset();    // Unset the data

    session_destroy();  // Destory The Session

    header('Location: index.php');

    exit();
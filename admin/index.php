<?php

    session_start();

    $noNavbar = ''; // to delete the navbar from page from init.php inluding
    $pageTitle = 'Login'; // function getTitle();
    
    if (isset($_SESSION['user'])){
        header('Location: dashboard.php');   // if registed redirect to Dashbard Page
    }

    include 'init.php';

    // Check if user coming from http Post Request

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username   = $_POST['user'];
        $password   = $_POST['pass'];
        $hashedPass = sha1($password); // تشفير الباسورد

        // check if the user exist in database

        $stmt = $con-> prepare("SELECT 
                                    UserID , Username , Password 
                                FROM 
                                    `users` 
                                WHERE 
                                    Username = ? 
                                AND 
                                    Password = ? 
                                AND 
                                    GroupID = 1
                                LIMIT 1 ");

        $stmt-> execute(array($username,$hashedPass));
        $row = $stmt-> fetch();         // get values from database and print it in array
        $count = $stmt->rowCount();     // get the record or count that found

        //echo $count;

        // if count > 0 This mean The Database Contain record about this username
        
        if ($count > 0){
            $_SESSION['Username'] = $username;      // Register Session Name
            $_SESSION['ID']       = $row['UserID']; // Register Session ID
            header('Location: dashboard.php');      // if registed redirect to Dashbard Page
            exit();
        }

    }

?>
    
   <form class ='login' action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" >
     <h4 class='text-center'>Admin Login</h4> 
     <input class='form-control' type="text" name ='user' placeholder="Username" autocomplete='off'>
     <input class='form-control' type="password" name='pass' placeholder="Password" autocomplete='new-password'>
     <input class='btn btn-primary btn-block' type="submit" value='Login'>
   </form>


<?php include $tpl . "footer.php"; ?>

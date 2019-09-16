<?php
	ob_start(); // Output Buffering Start
    session_start();
    $pageTitle = 'Login'; // function getTitle();
        
    if (isset($_SESSION['user'])){
        header('Location: index.php');   // if registed redirect to Home Page
    }

   include 'init.php'; 
    
// Check if user coming from http Post Request

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['login'])){

        $user       = $_POST['username'];
        $pass       = $_POST['password'];
        $hashedPass = sha1($pass); // تشفير الباسورد

        // check if the user exist in database

        $stmt = $con-> prepare("SELECT 
                                   UserID , Username , Password 
                                FROM 
                                    users
                                WHERE 
                                    Username = ? 
                                AND 
                                    Password = ? ");

        $stmt-> execute(array($user,$hashedPass));

        $get = $stmt->fetch();

        $count = $stmt->rowCount();     // get the record or count that found

        // echo $count;
        if ($stmt){
            
        }else{
            echo 'There is an Error in Database'.mysql_error();
        }

        // if count > 0 This mean The Database Contain record about this username
        
        if ($count > 0){
            $_SESSION['user'] = $user;           // Register Session Name
            $_SESSION['uid'] = $get['UserID'];  // Register User ID in Session 
            header('Location: index.php');      // if registed redirect to Dashbard Page
            exit();
        }
    }else {

        $formErrors = array();

        $username   = $_POST['username'];
        $password   = $_POST['password'];
        $password2  = $_POST['password2'];
        $email      = $_POST['email'];

        // Backend vailded SingUp Form to insert date into database

        if(isset($username)){                              //* Username Error and valided
            // filter the username

            $filterdUser = filter_var($username , FILTER_SANITIZE_STRING); // عقمه وطلعه استرينج 

            if(strlen($filterdUser) < 4 ){
                $formErrors[] = 'Username Must be Larger Than 4 Characters ';
            }

        }
        if(isset($password) && isset($password2)){ //* Passowrd Error and valided
            // Get the password1 and password 2 and do sha1 function for it

            if(empty($password)){ 

                $formErrors[] = 'Sorry Password Can\'t Be Empty ';

            }

            $pass1 = sha1($password);
            $pass2 = sha1($password2);

            if(sha1($password) !== sha1($password2)){
                
                $formErrors[] = 'Sorry Passwords don\'t Match ';

            }
            // if(empty($pass1)){ // that's wrong becouse the empty value has sha1() function will record in data base .. right to check before doing sha1

            //     $formErrors[] = 'Sorry Password Can\'t Be Empty ';

            // }

        }
        if(isset($email)){                              //* Email Error and valided
            // filter the email

            $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL); // عقمه وطلعه ايميل don't understand go to filter in php course
            // backend check that the email follow the rules of vaild email 

            if( filter_var($filterdEmail ,FILTER_VALIDATE_EMAIL) != true ){ // if you sure if it's email ==> [!= true] mean the email is wrong

                $formErrors[] = 'This Email Is Not Vaild';
                
            }

        }
        // check if there is no error ,, proceed the user add
        if(empty($formErrors)){

            // check if the username is exist in database

            $check = checkItem("Username","users",$username); // SELECT Username FROM users WHERE Username = $user //* look at checkItem function.php in includes 

            if($check == 1){

                $formErrors[] = 'Sorry This User Is Exist , You can\'t add it';

            }else {

                // Insert User Info To Database

                 $stmt = $con->prepare("INSERT INTO 
                                             users(Username, Password , Email , RegStatus, Date)
                                         VALUES( :zuser , :zpass , :zmail , 0, now()) ");    // keys that will use to define array values
                 $stmt->execute(array(

                    'zuser' => $username ,
                    'zpass' => sha1($password),
                    'zmail' => $email 

                ));

                if ($stmt){
                    // echo Sucessful message
                    $successMsg = 'Congrats Your Register Is Successful';
                }else{
                    echo 'There is an Error in Database'.mysql_error();
                }
            }
        }
    }
}

?>

<div class="login-cover">
    <div class="login-card">
        <div class='login-page '>
            <h1 class="text-center">
                <span class="selected" data-class="login" >Login</span> | <span data-class="signup">SignUp</span>
            </h1>
            <!-- Start Login Form -->
            <form class='login' action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" >
                <div class="input-container">
                <i class="glyphicon glyphicon-log-in"></i>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="username" 
                        autocomplete="off" 
                        required="required" 
                        placeholder="Username"/>
                </div>
                <div class="input-container">
                <i class="fa fa-key"></i>
                    <input 
                        class="form-control" 
                        type="password" 
                        name="password" 
                        autocomplete="new-password" 
                        required="required" 
                        placeholder="Password"/>
                </div>
                <input class="btn btn-primary btn-block" name="login" type="submit" value ='Login'/>
            </form>
            <!-- End Login Form -->

            <!-- Start SignUp Form -->
            <form class='signup' action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="input-container">
                    <i class="fa fa-user"></i>
                    <!-- pattern get massage error but need to get required -->
                    <input 
                        pattern=".{4,21}"  
                        title="Username Must Be Between 4 characters at Lest and 21 " 
                        class="form-control" 
                        type="text" 
                        name="username" 
                        autocomplete="off"
                        required
                        placeholder="Username"/>
                </div>
                <div class="input-container">
                <i class="fa fa-key"></i>
                    <input 
                        minlength="8"
                        class="form-control" 
                        type="password" 
                        name="password" 
                        autocomplete="new-password" 
                        required
                        placeholder="Password"/>
                </div>
                <div class="input-container">
                <i class="fa fa-unlock-alt"></i>
                    <input
                        minlength="8" 
                        class="form-control" 
                        type="password" 
                        name="password2" 
                        autocomplete="new-password" 
                        required
                        placeholder="Password again"/>
                </div>
                <div class="input-container">
                <i class="fa fa-envelope"></i>
                    <input 
                        class="form-control" 
                        type="email" 
                        name="email" 
                        required
                        placeholder="Vaild Email"/>
                </div>
                <input class="btn btn-success btn-block" name="signup" type="submit" value ='Sign Up'/>
            </form>
            <!-- End SignUp Form -->
            <div class="the-errors text-center">
                <?php 
                    
                    if(!empty($formErrors)){

                        foreach($formErrors as $error){
                            echo '<div class="msg">' .$error .'</div>' ;
                        }

                    }

                    if(isset($successMsg)){
                        echo '<div class="msg success">' . $successMsg .'</div>';

                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    include $tpl . 'footer.php';
    
    ob_end_flush(); // Release The Output
?>
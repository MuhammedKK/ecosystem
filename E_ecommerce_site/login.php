<?php
session_start();
$pageTitle = 'Login';
include 'init.php';
// Catch Errors
$signupErrors = array();

if(isset($_SESSION['user'])) {
    header("Location: index.php");// Homepage
    exit;
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {// Data Comming From Login Form

    if(isset($_POST['login'])) {
        
        $user =     $_POST['username'];
        $pass =     $_POST['password'];
        $hashPass = sha1($pass);
        
        $query = $con->prepare('SELECT * FROM users WHERE Username= ? AND `Password`= ?');
        $query->execute(array($user, $hashPass));
        $rows = $query->fetch();
        $count = $query->rowCount();
        
        if($count) {
            $_SESSION['user'] = $user;
            $_SESSION['id'] = $rows['Userid'];
            redirect('Great!, You Will Go To Your Profile Now', 3, 'index.php', 'success');
            exit;
        } else {
            redirect('Sorry You Don\'t Loggin Yet , Signup NOW!', 3, 'login.php');
        }
    } else {// Data Comming From Signup Form

        
        
        $username   = $_POST['username'];
        $password1  = $_POST['password1'];
        $password2  = $_POST['password2'];
        $email      = $_POST['email'];
        // Validate username Input 
        // if(! (isset($_POST['username']) && !empty($_POST['username'])) && strlen($_POST['username']) < 5) {
        //     $signupErrors[] = 'username';
        // }
        // if(! (isset($_POST['password1']) && !empty($_POST['username'])) && $_POST['password1'] != $_POST['password2']) {
        //     $signupErrors[] = 'Password';
        // }
        // Validate Username
        if(isset($username)) {
            $vaildUser = filter_var($username, FILTER_SANITIZE_STRING);
            if(strlen($username) < 5) {
                $signupErrors[] = 'Username Canout Be Less Than 5 Chars';
            }
            if(empty($username)) {
                $signupErrors[] = 'Username Canout Be Empty';
            }
        }
        // Validate Password By Hashing And if aMatched Or Not
        if(isset($password1) && isset($password2)) {
            if(empty($password1)) {
                $signupErrors[] = 'Passord Canout Be Empty';
            }
            if(sha1($password1) !== sha1($password2)) {
                $signupErrors[] = 'Password Not Match , Plz Try Again';
            }
        }
        // Validate Email To Get A Valid Email
        if(isset($email)) {
            $vaildEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if(filter_var($vaildEmail, FILTER_VALIDATE_EMAIL) != TRUE) {
                $signupErrors[] = 'Plz Inser A Valid Email';
            }
        }

        if(empty($signupErrors)) { // So Everything Is Vaildated

            $checkUser = checkItem('Username', 'users', $username);

            if($checkUser == 1) {
                $signupErrors[] = 'Sorry This Username Is Exsist, Try Again With Another One';
            } else {

                $stmt = $con->prepare("INSERT INTO users (Username, `Password`, `Email`, Regstatus, `date`) VALUES (?, ?, ?, 0, now())");
                $stmt->execute(array($username, sha1($password1), $email));
                $userInserted = $stmt->rowCount();
                if($userInserted > 0) {
                    echo '<div class="alert alert-success">User Insertd Successfully</div>';
                }

            }

        }

    }
    
}

?>
<div class="login-front">
    <div class="container">
        <h1 class="text-center">
            <span class="active" data-class="login">Login</span> | <span data-class="signup">Signup</span>
        </h1>
        <form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <input type="text" class="form-control input-block" placeholder="Enter Username" required name="username" auto-complete="off">
            <input type="password" class="form-control input-block" placeholder="Enter Password" required name="password" auto-complete="off">
            <input type="submit" class="btn btn-primary btn-block" value="Login" name="login">
        </form>
    </div>
</div>


<div class="login-front">
    <div class="container">
        <form class="signup" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <input type="text" class="form-control input-block" placeholder="Enter Username"  name="username" auto-complete="off">

            <input type="password" class="form-control input-block" placeholder="Enter Password" required name="password1" auto-complete="off">

            <input type="password" class="form-control input-block" placeholder="Confirm Password" required name="password2" auto-complete="off">

            <input type="email" class="form-control input-block" placeholder="Enter email" required name="email" auto-complete="off">
            <input type="submit" class="btn btn-success btn-block" value="Signup" name="signup">
            <?php

                foreach($signupErrors as $err) {
                echo '<div class="alert alert-danger wrong">'. $err .'</div>';
                }

            ?>
        </form>
    </div>
</div>



<?php
include $Tpl . 'footer.php';
?>
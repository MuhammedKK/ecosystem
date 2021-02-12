<?php
    session_start();
    $NO_NAVBAR = '';
    $pageTitle = 'Login';
    include 'init.php';

    // CHECK IF USER COME FROM POST REQUEST
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Transfer The Pass Into Hashing Pass
        $hashedPass = sha1($password);
        // Sql Statement With PDO CLass & Prepare Method With (->) Object Assign
        $stmt = $con->prepare('SELECT Userid,  username, `password` FROM users WHERE username = ? AND `password` = ? AND Groupid = 1 LIMIT 1');
        // Excute Query By (excute) Method Thats Accepted Array Of Data Comes From DB
        $stmt->execute(array($username, $hashedPass));
        // Fetching The Data
        $rows = $stmt->fetch();
        // Count The Rows Thats Returns From DB
        $conut = $stmt->rowCount();
        echo $conut;
        // Check If ount Of Rows Greater Than 0  
        if($conut > 0) {

            // Assign To Session Array The Username
            $_SESSION['Username'] = $username; // Assign The Username To Username In Session
            $_SESSION['ID'] = $rows['Userid']; // Assign The ID To SESSION ID To Make Actions To The Member Through It
            header('Location: dashboard.php'); // Redirect To Dashboard Page 
            exit(); // Exit Script
        } else {
            echo '<div class="container">';
            echo '<div class="alert alert-danger">Sorry This User Is Not Exsist , Plz Try Again</div>';
            echo '</div>';
        }
        
    }
    
?>

<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input type="text" class="form-control input-block" placeholder="Enter Username" required name="username" auto-complete="off">
    <input type="password" class="form-control input-block" placeholder="Enter Password" required name="password" auto-complete="off">
    <input type="submit" class="btn btn-primary btn-block" value="Login" name="submit">
</form>

<?php
    include $Tpl . 'footer.php';
?>
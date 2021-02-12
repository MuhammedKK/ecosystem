<?php
    session_start();
    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Members';
        include 'init.php';

        $action = (isset($_GET['action'])) ? $_GET['action'] : 'manage';

        if($action == 'manage') {// Manage Page

            // Check If GET Request Hava Page With Pending Name To Get Pending Users Just

            $q = '';
            if(isset($_GET['page']) && $_GET['page'] == 'pending') {

                // Get Users By RegStatus
                $q = ' AND Regstatus = 0';

            }

            // Select All Users 
            $stmt = $con->prepare("SELECT * FROM users WHERE Groupid != 1 $q");
            $stmt->execute();
        ?>
            
            <!-- Start Table Manage Members -->
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <table class="table-bordered main-table text-center table table-responsive">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Register Date</td>
                        <td>Actions</td>
                    </tr>
                    <?php 
                    while($rows = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo $rows['Userid'] ?></td>
                        <td><?php echo $rows['Username'] ?></td>
                        <td><?php echo $rows['Email'] ?></td>
                        <td><?php echo $rows['FullName'] ?></td>
                        <td><?php echo $rows['date'] ?></td>
                        <td>
                            <a href="members.php?action=edit&Userid= <?php echo $rows['Userid']; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="members.php?action=delete&Userid= <?php echo $rows['Userid']; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                        <?php
                            if($rows['Regstatus'] == 0) {
                                echo "<a href='?action=approve&Userid=". $rows['Userid'] ."' class='btn btn-primary'><i class='fa fa-info'></i>Approve</a>";
                            }
                        ?>
                        </td>

                    </tr>
                    <?php } ?>
                </table>
                <a href="?action=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
            </div>
            <!-- End Table Manage Members --> 

            

       <?php } elseif ($action == 'add') { // Add Member Page ?>

            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form action="?action=insert" class="form-horizontal" method="POST">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Username</label>
                        <input type="text" name="username" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Password</label>
                        <input type="password" name="password" autocomplete="new-password" class=" password form-control col-sm-10"/>
                        <i class="show-pass fa fa-eye fa-2px"></i>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Full Name</label>
                        <input type="text" name="full"  autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Email</label>
                        <input type="email" name="email" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add Member"class="btn btn-lg btn-primary  col-sm-12"/>
                    </div>
                </form>
            </div>

       <?php } elseif($action == 'edit') { // Edit Member Page 

            // Check If GET REQUEST ID IS numeric & Exsist
            $userid = (isset($_GET['Userid']) && is_numeric($_GET['Userid']) ? intval($_GET['Userid']) : 0);

            // SELECT ALL DATA OF USER IF GET ID IS userid
            $stmt = $con->prepare('SELECT * FROM `users` WHERE `Userid` = ? LIMIT 1');

            // Execute The Query 
            $stmt->execute(array($userid));
            
            // Fetch The User Data 

            $rows = $stmt->fetch();

            // Get The Rows Count To Check If There's Data Coming Or Not

            $count = $stmt->rowCount();

            if ($count > 0) { ?>
            
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form action="?action=update" class="form-horizontal" method="POST">
                    <input type="hidden" value="<?php echo $rows['Userid']; ?>" name="userid" />
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Username</label>
                        <input type="text" name="username" value="<?php echo $rows['Username'] ?>" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Password</label>
                        <input type="hidden" name="oldpassword" autocomplete="new-password" value="<?php echo $rows['Password']; ?>" class="form-control col-sm-10"/>
                        <input type="password" name="newpassword" autocomplete="new-password" class="form-control col-sm-10" placeholder="If You Won't Change The Password Let It Empty"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Full Name</label>
                        <input type="text" name="full" value="<?php echo $rows["FullName"] ?>" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">Email</label>
                        <input type="email" name="email" value="<?php echo $rows["Email"] ?>" autocomplete="off" class="form-control col-sm-10"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save Changes"class="btn btn-lg btn-success col-sm-12"/>
                    </div>
                </form>
            </div>
            
        <?php 
        } else {
            redirect("Sorry User Not Exsists", 4, 'members.php?action=manage');
            
        }    
    }  elseif ($action == 'insert') { /* Start Insert Page */

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<div class="container">';
                echo '<h1 class="text-center">Insert Member</h1>';
                $username = $_POST['username'];
                $email = $_POST['email'];
                $pass = $_POST['password'];
                $fullName = $_POST['full'];
                $hashPass = sha1($pass);
                // Errors Array To Handle Input Errors
                $errors = array();
                // Validate 
                if (strlen($username) < 3) {
                    $errors[] = '<div class="alert alert-danger">Username Cant Be Less Than 3 Chars</div>';
                }
                if (strlen($username) > 20) {
                    $errors[] = '<div class="alert alert-danger">Username Cant Be More Than 20 Chars</div>';
                }
                if (empty($pass)) {
                    $errors[] = '<div class="alert alert-danger">Password Cant Be Empty</div>';
                }
                if (empty($username)) {
                    $errors[] = '<div class="alert alert-danger">Username Cant Be Empty</div>';
                }
                if (empty($email)) {
                    $errors[] = '<div class="alert alert-danger">Email Cant Be Empty</div>';
                }
                if (empty($fullName)) {
                    $errors[] = '<div class="alert alert-danger">fullName Cant Be Empty</div>';
                }

                foreach($errors as $err) {
                    echo $err . '<br>';
                }
                echo '<div>';

                if (empty($errors)) {
                    // Check If User Exsist Before Insert It 

                    $check = checkItem('Username', 'users', $username);
                    if ($check == 1) {
                        redirect('Sorry This Username Is Exsist Please Try Again With Anouther Username', 6, 'members.php?action=add');
                    } else {
                        // Insert Query
                        $stmt = $con->prepare("INSERT INTO `users` (`Username`, `Password`, `Email`, `FullName`, `Regstatus`, `date`) VALUES (?, ?, ?, ?, 1,now())");
                        // Execute Update Query
                        $stmt->execute(array($username, $hashPass, $email, $fullName));

                        $count = $stmt->rowCount();
                        if($count > 0) {
                            redirect($count .' Record Inserted Successfully !', 4, 'members.php?action=manage', 'success');
                        } else {
                            redirect($count .'  Nothing Inserted !', 4, 'members.php?action=manage');
                        }
                    }   
                }

            } else {
                redirect("You Can't Browse This Page Direct", 4, 'members.php?action=manage');
            }       

            /* End Insert Page */

        } elseif ($action == 'update')  { /* Start Update Page */
        echo '<h1 class="text-center">Update Member</h1>';

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $FullName = $_POST['full'];

            // Password Trick
            $pass = (empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']));

            // Update Query
            $stmt = $con->prepare('UPDATE `users` SET Username= ?, `Password`= ?, Email = ? , FullName= ? WHERE Userid= ? ');
            // Execute Update Query
            $stmt->execute(array($username, $pass, $email, $FullName, $id));

            $count = $stmt->rowCount();
            if($count > 0) {
                redirect($count .' Record Updated Successfully !', 4, 'members.php?action=manage', 'success');
                
            } else {
                redirect($count .' Record Nothing Updated !', 4, 'members.php?action=manage', 'success');
            }
        } else {
            redirect('Sorry You Can Browse This Page Direct', 4, 'members.php?action=manage');
        }
        /* End Update Page */
    } elseif($action == 'delete') { /* Start Delete Page */
            // Check If GET REQUEST ID IS numeric & Exsist
            $userid = (isset($_GET['Userid']) && is_numeric($_GET['Userid']) ? intval($_GET['Userid']) : 0);
            // Delete User Depend On His Id By User CheckItem Function
            $check = checkItem('Userid', 'users', $userid);

            if ($check > 0) { 
                // Delete Query 
                $stmt = $con->prepare('DELETE FROM `users` WHERE Userid= ?');
                $stmt->execute(array($userid));
                if($stmt->rowCount() > 0) {
                    redirect(' User Deleted Successfully !', 4, 'members.php?action=manage', 'success');
                }
            } else {
                redirect('Sorry User Not Exsist', 4, 'members.php?action=manage');
            }
        /* Start Delete Page */
    } elseif($action == 'approve') { // Approve Page
        
            // Check If GET REQUEST ID IS numeric & Exsist
            $userid = (isset($_GET['Userid']) && is_numeric($_GET['Userid']) ? intval($_GET['Userid']) : 0);
            // Delete User Depend On His Id By User CheckItem Function
            $check = checkItem('Userid', 'users', $userid);

            if ($check > 0) { 
                // Delete Query 
                $stmt = $con->prepare("UPDATE users SET Regstatus= 1 WHERE Userid= ?");
                $stmt->execute(array($userid));
                if($stmt->rowCount() > 0) {
                    redirect(' User Approved Successfully !', 4, 'dashboard.php', 'success');
                }
            } else {
                redirect('Sorry User Not Exsist', 4, 'members.php?action=manage');
            }

    }

        include $Tpl . 'footer.php';
    }
    
    else {
        header("Location: index.php");
        exit();
    }
?>
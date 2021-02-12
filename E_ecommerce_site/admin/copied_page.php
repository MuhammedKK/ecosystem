<?php
    session_start();
    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Members';
        include 'init.php';

        $action = (isset($_GET['action'])) ? $_GET['action'] : 'manage';

        if($action == 'manage') {// Manage Page
            
        } elseif ($action == 'add') { // Add Member Page 
  
        }  elseif ($action == 'insert') { /* Start Insert Page */

  

            /* End Insert Page */

        } elseif ($action == 'update')  { /* Start Update Page */

        /* End Update Page */
        } elseif($action == 'delete') { /* Start Delete Page */

        /* Start Delete Page */
        } elseif($action == 'approve') { // Approve Page
        

        }

        include $Tpl . 'footer.php';
        }
    
        else {
            header("Location: index.php");
            exit();
        }
?>
<?php
    // Connect file
    include 'connect.php';
    
    // Routes
    $Tpl = 'includes/temps/';
    $CSS = 'layout/css/';
    $js = 'layout/js/';
    $lang = 'includes/langs/';
    $funcs = 'includes/funcs/';

    // SET IMPORTANT FILES
    include $funcs . 'functions.php';
    include $lang . 'eng.php';
    include $Tpl . 'header.php';


    // Check If Variable ($NO_NAVBAR) Is Not Exsist Then Include Navbar File
    if(!isset($NO_NAVBAR)) {
        Include $Tpl . 'navbar.php';
    }
?>
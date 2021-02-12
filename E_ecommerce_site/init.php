<?php
    // Errors Debagging 
    ini_set('dispaly_errors', 'ON');
    error_reporting(E_ALL);

    // Create User Session Variable
    $userSession = '';
    if(isset($_SESSION['user'])) {
        $userSession = $_SESSION['user'];
    }
    // Connect file
    include 'admin/connect.php';
    
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


?>
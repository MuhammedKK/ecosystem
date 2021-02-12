<?php

//Start The Session That U Need To Remove
session_start();
// Delete The Data From The Server
session_unset();
// Destroy The Session From The Server 
session_destroy();
// Redirect To Index Page After Deleting Session 
header('Location: index.php');
// End The Script
exit;
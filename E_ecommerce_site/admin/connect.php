<?php

    $dsn = "mysql:host=localhost;dbname=pharmacy";
    $user = "root";
    $pass = "";

    try {
        $con = new PDO($dsn,$user, $pass);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
        echo "Falid Connection => " . $e;
    }
?>
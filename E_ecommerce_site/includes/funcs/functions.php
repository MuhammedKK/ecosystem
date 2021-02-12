<?php
// Here The Functions That I Needed

/* Start Front Pages Functions */

/* Function To Get Any Data From DB */
function getAll($select, $table, $where = NULL, $and = NULL, $orderBy, $orderType = "DESC") {
    global $con;
    $stmt = $con->prepare("SELECT $select FROM $table $where $and ORDER BY $orderBy $orderType ");
    $stmt->execute();
    $allData = $stmt->fetchAll();
    return $allData;
}


/* Function to get Categories From DB */

function allCats() {
    global $con;
    
    $getCats = $con->prepare('SELECT * FROM categories ORDER BY cat_id DESC');
    $getCats->execute();
    $cats = $getCats->fetchAll();
    return $cats;
}

/* Function to get Items From DB */

function allItems($where, $val) {
    global $con;
    
    $getItmes = $con->prepare("SELECT * FROM items WHERE $where= ? ORDER BY it_id DESC");
    $getItmes->execute(array($val));
    $Items = $getItmes->fetchAll();
    return $Items;
}

/* End Front Pages Functions */



/* Start Back Pages Functions */

// Function To Get The Title From The Page And Set The Title Based On It
// This Function V1.0 

function getTheTitle() {
    global $pageTitle;
    if(isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Administrator';
    }
}

// Custom Function To Redirect To The Index Page If User Come Directly In The Page
// This Function Will Return A Dynamic Error & Dynamic Time To Redirect To Home Page
// This Function V1.0 
// This Function V2.0 // In This Version I Added [$redirectPage] To Refer To The Page You Want To Redirect To It
// This Function V3.0 // In This Version I Added [btbClass] To Make You Choose Your Error Color 

function redirect($theMsg, $sec = 2, $redirectPage = 'index.php', $btnClass = 'danger') {
    echo "<div class='alert alert-$btnClass'>'. $theMsg .'</div>";
    echo "<div class='alert alert-$btnClass'>You Will Redirect To Another Page In ' . $sec . ' Seconds</div>";
    // header(refresh: Seconds; url= Your Page That Redirect To it );
    // Refresh Used Instead Of Location When You Want To redirect To Specific Page After A Few Seconds
    header('refresh:'. $sec .'; url='.$redirectPage .'');
}

// Custom Check Item If Exsist Or Not To Not Insert Two Items Or Users etc With The Same Name Or Details
// Check Item Function  ( It's Name )
// Function Has Parameters(Selected, Table , value That You Check It)
// This Function v1.0 

function checkItem($selected, $table, $value) {

    // Make $con Variable Global To Enable It To Use In All Of The script
    global $con;

    // Query Statment
    $query = $con->prepare("SELECT $selected FROM $table WHERE $selected= ?");
    // Execute The Statment
    $query->execute(array($value));
    // Get The Count Of Selected Records
    $count = $query->rowCount();
    // Return The Value Of $count
    return $count;

}

// Custom Function To Get The Count Of Any Data I Inserted
// CountItems Function (I'ts Name)
// Function Has Parameters ($items, $table)
// This Function v1.0

function countItems($item, $table) {
    // Make $con Global To Identify In The All Of The Script
    global $con;
    // Query Statement
    $stmt = $con->prepare("SELECT COUNT('$item') FROM $table");
    $stmt->execute();
    $colsCount = $stmt->fetchColumn();
    return $colsCount;
}

// Custom Function To Get The Least Items Or Users Or Any Thing Depend On Specific Limit & In The Future Depend On The Date
// GetLeast Function (It's Name)
// Function Have Params
// $select  => refer To What You Will Select
// $tablr   => refer To Which Table U Will Use
// $order   => refer To How You Will Order This Items Depend On Who 
// $limit   => refer To What's Limit Of Your Items

function getLeatest($select, $table, $order, $limit) {
    global $con;
    $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT= $limit ");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}

/* End Back Pages Functions */
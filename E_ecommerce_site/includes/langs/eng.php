<?php
    function lang($word) {
         
        static $lang = array( 

            'MYSHOP' => 'Myshop',
            'ADMIN' => 'Mohamed',
            'CATEGORIES' => 'Categories',
            'ITEMS' => 'Items',
            'MEMBERS' => 'Members',
            'COMMENTS' => 'Comments',
            'STATS' => 'Statistics',
            'LOGS' => 'Logs',
            

        );
        return $lang[$word] ;
    }
?>

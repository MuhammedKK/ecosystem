<?php
    function lang($word) {
         
        static $lang = array( 

            'MESSAGE' => 'مرحبا',

            'ADMIN' => 'محمد'

        );
        return $lang[$word] ;
    }
?>

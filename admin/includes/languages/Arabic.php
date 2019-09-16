<?php 

    function lang( $phrase ){

        static $lang = array(

            'MESSAGE' => 'مرحبا',
            'ADMIN'   => 'ادمن'

        );

        return $lang[$phrase];

    }

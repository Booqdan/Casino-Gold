<?php

class newCards {
    public $bar = "fizzbuzz";
}

class gamingCards {
    static public $array = array();
    static public function init() {
        while ( count( self::$array ) < 3 )
            array_push( self::$array, new newCards() );
    }
}

gamingCards::init();
print_r( gamingCards::$array );
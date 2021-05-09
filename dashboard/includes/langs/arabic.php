<?php

// Key Languages function

function language($phrase){
    
    static $lang = array(

        "Message" => "مرحبا" ,
        "Admin" => "المدير",
    );
    return $lang[$phrase];
}



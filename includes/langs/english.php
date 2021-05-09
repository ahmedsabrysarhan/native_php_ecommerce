<?php

// Key Languages function

function language($phrase){
    
    static $lang = array(
        // NavBar Words 
        "LOGO"          => "NAV LOGO",
        "MainPage"      => "Home" ,
        "about"         => "About Us",
        "members"       => "Members" ,
        "categories"    => "Categories",
        "items"         => "Items",
        "comment"       => "Comments",
        "contact"       => "Contact Us",
        "dashboard"     => "Dashboard",
        "Profile"       => "Edit Profile",
        "settings"      => "Seetings",
        "logout"        => "Log Out",

    );
    return $lang[$phrase];
}



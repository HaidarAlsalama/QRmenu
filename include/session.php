<?php
$options = array (
    'name' => _COOKIE_NAME_.'_Rings',
    'cookie_lifetime' =>  60*60*24*2, // 2 days
    //'cookie_lifetime' =>  60, // 1 hour
    'cookie_path' => '/', // path to use in case of conflicts but preferred not to use
    //'use_only_cookies' => true,
    'use_strict_mode' => true, // Applications are protected from session fixation via session adoption with strict mode
    'sid_length' => 100, // preferred for security max 200 (using more than 200 will not work on some browsers)
    'cookie_secure' => _FORCE_SSL_,     // send over https
    'httponly' => false,    // If true will marks the cookie as accessible only through the HTTP protocol.
    // This means that the cookie won't be accessible by scripting languages, such as JavaScript.
    // This setting can effectively help to reduce identity theft through XSS attacks (although it is not supported by all browsers).
    'cookie_samesite' => 'Strict', // None || Lax // no third party on post  || Strict // no third party on get and post
    'SameSite' => 'Strict' // None || Lax  || Strict // no third party
);


?>
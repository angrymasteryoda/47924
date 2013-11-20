<?php
$DATABASE_LOADED = false;
session_start();
define('SALT', '0acf4539a14b3aa27deeb4cb');
define('SERVER', 'live');
define('APP_NAME', 'survey');
define('APP_URL', '../');
define('MAIL_TO', 'rishermichael@gmail.com');

define('NO_QUOTES', false);
define('ALLOW_HTML', 1);

//database stuffs
if (SERVER == 'localhost') {
    define('DB_NAME', 'survey_local');
    define('DB_HOST', 'localhost');
}
else if (SERVER == 'live') {
    define('DB_NAME', 'survey_live');
    define('DB_HOST', 'ds053838.mongolab.com:53838');
    define('DB_USER', '47924');
    define('DB_PASS', '47924cis12');
    define('DB_PORT', '53838');
}

function mongoConnectionGen($mode = SERVER, $databaseName = DB_NAME){
     switch($mode){
         case 'localhost':
             return 'mongodb://localhost';
         break;
         case 'live':
             return 'mongodb://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . ':' . DB_PORT . '/' . DB_NAME;
         break;
     }
}


function loadClasses(){
    $paths = glob( '../libraries/class.*.php' );
    foreach($paths as $path){
        require_once($path);
    }
}

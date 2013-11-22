<?php
$DATABASE_LOADED = false;
session_start();
define('SALT', '0acf4539a14b3aa27deeb4cb');
define('SERVER', 'local');
define('APP_NAME', 'survey');
define('APP_URL', '../');
define('MAIL_TO', 'rishermichael@gmail.com');

define('ADMIN_RIGHTS', '*');

define('NO_QUOTES', false);
define('ALLOW_HTML', 1);

//database stuffs
if (SERVER == 'localhost' || SERVER == 'local') {
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

loadClasses();

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

function checkLogin(){
    $backend = 'back/';
    $parse = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    //Debug::echoArray( end( explode('/', $parse['path']) ) );
    $ref = end( explode('/', $parse['path']) );
    if ( preg_match( '/back\//', $parse['path'] ) ) {
        $ref = 'back/' . $ref;
        if ( isset( $_SESSION['roles']) ) {
            if ( !Auth::checkPremissions($_SESSION['roles'], ADMIN_RIGHTS) ) {
                header( 'Location: ../back/login.php' . ( (!empty($ref)) ? ('?ref='.$ref) : ('') ) ) ;
            }
        }

        $isBackEnd = true;
    }
    if ( $_SESSION['time'] + 10 * 60 < time()) {
        unset( $_SESSION['time'] );
        unset( $_SESSION['username'] );
        header( 'Location: ../'. ( ($isBackEnd) ? ('back') : ('templates') ) .'/login.php' . ( (!empty($ref)) ? ('?ref='.$ref) : ('') ) ) ;
    } else {
        if( empty( $_SESSION['username'] )){
            header( 'Location: ../'. ( ($isBackEnd) ? ('back') : ('templates') ) .'/login.php' . ( (!empty($ref)) ? ('?ref='.$ref) : ('') ) ) ;
        }
        else{
            $_SESSION['time'] = time();
        }
    }
}

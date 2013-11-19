<?php
$DATABASE_LOADED = false;
session_start();
define('SALT', '0m983vJt9Lb3tvV82yjHdjz8');
define('SERVER', 'localhost');
define('APP_NAME', 'karate');
//define('APP_URL_RELATIVE', $_SERVER['HTTP_HOST'] . '/karate/');
//define('APP_URL', '//' . APP_URL_RELATIVE);
//define('APP_DIR', $_SERVER['DOCUMENT_ROOT'] . '/karate/');
define('APP_URL', '../');
define('MAIL_TO', 'rishermichael@gmail.com');

define('NO_QUOTES', false);

//database stuffs
if (SERVER == 'localhost') {
    define('DB_NAME', '47924');
    Define('DB_USER', 'root');
    Define('DB_PASSWORD', '');
    Define('DB_HOST', SERVER);
}
else if(SERVER == 'live'){
    define('DB_NAME', '47924');
    Define('DB_USER', '47924');
    Define('DB_PASSWORD', '47924cis12');
    Define('DB_HOST', 'localhost');
}


function loadDB($databaseName = null){
    $DATABASE_LOADED = true;
    $r = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

    if (!$r) {
        echo "Could not connect to server\n";
        trigger_error(mysql_error(), E_USER_ERROR);
    }
    if ( $databaseName ) {
        mysql_select_db($databaseName);
    }
}
function closeDB(){
    $DATABASE_LOADED = false;
    return mysql_close();
}

function checkLogin(){
    if ($_SESSION['time'] + 10 * 60 < time()) {
        unset( $_SESSION['time'] );
        unset( $_SESSION['username'] );
        header( 'Location: ../books/login.php' ) ;
    } else {
        if( empty( $_SESSION['username'] )){
            header( 'Location: ../books/login.php' ) ;
        }
        else{
            $_SESSION['time'] = time();
        }
    }
}
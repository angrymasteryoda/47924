<?php
$DATABASE_LOADED = false;
session_start();
define('SALT', '0acf4539a14b3aa27deeb4cb');
define('SERVER', 'local');
define('APP_NAME', 'personal');
define('APP_URL', '../');
define('MAIL_TO', 'rishermichael@gmail.com');

define('ADMIN_RIGHTS', '*');

define('NO_QUOTES', false);
define('ALLOW_HTML', 1);

//database stuffs
if (SERVER == 'localhost' || SERVER == 'local') {
    define('DB_NAME', 'personal_local');
    define('DB_HOST', 'localhost');
}
else if (SERVER == 'live') {
    define('DB_NAME', 'personal_live');
    define('DB_HOST', 'ds053838.mongolab.com');
    define('DB_USER', '47924');
    define('DB_PASS', '47924cis12');
    define('DB_PORT', '53838');
}

loadClasses();

function loadDB($table){
    $dbName = DB_NAME;
    $client = new MongoClient( mongoConnectionGen() );
    $db = $client->$dbName;

    return $collection = $db->$table;
}

function goToError($code){
    switch ($code){
        case 404:
            $url = APP_URL . 'errors/404.php';
            break;
        default:
            $url = APP_URL . 'errors/404.php';
    }
    header('Location: ' . $url);
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

function logout(){
    unset( $_SESSION['rights'] );
    unset( $_SESSION['time'] );
    unset( $_SESSION['username'] );
    session_destroy();
}

function checkLogin($redirect = true){
    $backend = 'back/';
    $parse = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    //Debug::echoArray( end( explode('/', $parse['path']) ) );
    $ref = end( explode('/', $parse['path']) );
    if ( preg_match( '/back\//', $parse['path'] ) ) {
        $ref = 'back/' . $ref;
        if ( isset( $_SESSION['rights']) ) {
            if ( !Auth::checkPermissions(ADMIN_RIGHTS) ) {
                if ( $redirect ) {
                    header( 'Location: ../templates/login.php' . ( (!empty($ref)) ? ('?ref='.$ref) : ('') ) ) ;
                }
                else{
                    return false;
                }

            }
        }

        $isBackEnd = true;
    }
    if ( isset( $_SESSION[ 'time' ] ) ) {
        if ( $_SESSION[ 'time' ] + 10 * 60 < time() ) {
            unset( $_SESSION[ 'time' ] );
            unset( $_SESSION[ 'username' ] );
            if ( $redirect ) {
                header( 'Location: ../templates/login.php' . ( ( !empty( $ref ) ) ? ( '?ref=' . $ref ) : ( '' ) ) );
            } else {
                return false;
            }
        } else {
            if ( empty( $_SESSION[ 'username' ] ) ) {
                if ( $redirect ) {
                    header( 'Location: ../templates/login.php' . ( ( !empty( $ref ) ) ? ( '?ref=' . $ref ) : ( '' ) ) );
                } else {
                    return false;
                }
            } else {
                $_SESSION['time'] = time();
                return true;
            }
        }
    }
    else {
        if ( $redirect ) {
            header( 'Location: ../templates/login.php' . ( ( !empty( $ref ) ) ? ( '?ref=' . $ref ) : ( '' ) ) );
        } else {
            return false;
        }
    }
}

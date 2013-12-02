<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/4/13
 * Time: 3:19 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Auth {
    static function checkPermissions( $required, $roles = null ){
//        Debug::echoArray($_SESSION['roles']);
        if ( $roles == null && isset( $_SESSION['roles'] )) {
            $roles = $_SESSION['roles'];
        }
        else{
            return false;
        }

        if ( isset( $roles ) ) {
            foreach ( $roles as $perm ) {
                if ( $perm == $required || $perm == '*') {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }
}

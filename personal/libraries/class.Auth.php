<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/4/13
 * Time: 3:19 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Auth {
    static function checkPermissions( $required, $rights = null ){
//        Debug::echoArray($_SESSION['rights']);
        if ( $rights == null && isset( $_SESSION['rights'] )) {
            $rights = $_SESSION['rights'];
        }
        else{
            return false;
        }

        if ( isset( $rights ) ) {
            foreach ( $rights as $perm ) {
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

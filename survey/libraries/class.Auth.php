<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/4/13
 * Time: 3:19 PM
 * To change this template use File | Settings | File Templates.
 */ 
class Auth {
    static function checkPremissions($userPerms, $required){
        foreach ( $userPerms as $perm ) {
            if ( $perm == $required ) {
                return true;
            }
            else {
                return false;
            }
        }

    }
}

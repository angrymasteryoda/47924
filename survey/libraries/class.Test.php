<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 11/14/13
 * Time: 6:12 PM
 * To change this template use File | Settings | File Templates.
 */

class Test {

    static function newvalidate( $validatables, $data){
        foreach ( $validatables as $validates ) {
            Debug::echoArray( $validates );

            $field = $validates['field'];
            $validations = explode( ' ', $validates['use'] );

            //to allow for multiple checks
            foreach ( $validations as $validate ) {
                switch ($validate){
                    case 'name' :
                        echo 'do name validate';
                        break;

                    case 'password' :
                        echo 'do password validate';
                        break;
                }
            }


        }

    }


    static function validate($type, $str = ''   ){
        if( is_array($type) ){
            $errors = array();
            foreach($type as $r => $v){
                $errors[$r] = self::validate($r, $v);
            }
            return $errors;
        }
        else{
            $loadRegex = self::getRegex($type);
            if( preg_match( $loadRegex['regex'], $str ) ){
                return true;
            }
            else{
                return false;
            }
        }
    }
}
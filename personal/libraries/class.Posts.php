<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 12/4/13
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */

class Posts {

    public static function summary( $text, $length, $title){
        $text = strip_tags( $text );
        $words = explode( ' ', $text );

        $sum = array_splice($words, 1, $length);

//        Debug::echoArray($sum);
        if ( count( $words ) > $length ) {
            return implode( ' ', $sum ) . ' <a href="'. APP_URL .'templates/view?title='. md5( $title ) .'">Read more...</a>';
        }
        else{
            return $text;
        }
//        return implode( ' ', $sum );
    }

}
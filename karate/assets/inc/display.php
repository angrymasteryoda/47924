<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 12/7/13
 * Time: 4:44 PM
 * To change this template use File | Settings | File Templates.
 */
if ( isset($_GET['src']) ) {
    include_once APP_URL . 'libraries/wideImage/WideImage.php';

    $dimensions = getimagesize( $_GET['src'] ) ;
    $height = $dimensions[1];
    $width = $dimensions[0];
    if ( isset($_GET['h']) ) {
        $height = $_GET['h'];
    }
    if ( isset($_GET['w']) ) {
        $width = $_GET['w'];
    }

    WideImage::load( $_GET['src'] )->resize($width, $height)->saveToFile( $_GET['src'] . '.png' );
    echo $_GET['src'] . '.png';
}
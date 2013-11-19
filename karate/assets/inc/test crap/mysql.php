<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/11/13
 * Time: 5:40 PM
 * To change this template use File | Settings | File Templates.
 */
include $_SERVER['DOCUMENT_ROOT'] . '/karate/assets/inc/meta.php';
t('test queries');
loadDB();

$q = "SELECT `entity_Term`.`term`, `entity_Term`.`meaning`, `enum_sections`.`section` FROM `karate_local`.`entity_Term` AS `entity_Term`, `karate_local`.`enum_sections` AS `enum_sections` WHERE `entity_Term`.`section` = `enum_sections`.`id`;";
$r = mysql_query($q);
$terms = array();
$i = 0;
while( ($row = mysql_fetch_assoc( $r ) ) ){
    $terms[$i] = $row;
    $i++;
}

$q = "SELECT `section` FROM `karate_local`.`enum_sections` AS `enum_sections`;";
$r = mysql_query($q);
$sections = array();
$i = 0;
while( ($row = mysql_fetch_assoc( $r ) ) ){
    $sections[$i] = $row;
    $i++;
}
mysql_close();

foreach($sections as $section){
    $str =  '<div><p class="redHeader aligncenter font14pt">' . $section['section'] . '</p><p>';
    //echo $section['section'];
    foreach( $terms as $term){
        if($term['section'] == $section['section']){
            $str .= '<span class="bold" style="text-transform:capitalize;">' . $term['term'] . '</span>: ' . $term['meaning'] . '<br>';
            //echo $term['term'] . '<br>';
        }
    }
    $str .= '</p></div>';
    echo $str;
}

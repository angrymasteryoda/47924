<?php
include_once '../config/global.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include '../assets/inc/meta.php';
    ?>
</head>
<body>
<div id="wrapper">
    <?php
    include '../assets/inc/header.php';
    loadDB();
    ?>

    <div id="content">
        <p class="pageTitle">
            Terminology
        </p>
        <div>
            <?php
            $q = "SELECT `mr2358174_karate_entity_terms`.`term`, `mr2358174_karate_entity_terms`.`meaning`, `mr2358174_karate_enum_sections`.`section` FROM `47924`.`mr2358174_karate_entity_terms` AS `mr2358174_karate_entity_terms`, `47924`.`mr2358174_karate_enum_sections` AS `mr2358174_karate_enum_sections` WHERE `mr2358174_karate_entity_terms`.`section` = `mr2358174_karate_enum_sections`.`id`;";
            $r = mysql_query($q);
            $terms = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $terms[$i] = $row;
                $i++;
            }

            $q = "SELECT `section` FROM `47924`.`mr2358174_karate_enum_sections` AS `mr2358174_karate_enum_sections`;";
            $r = mysql_query($q);
            $sections = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $sections[$i] = $row;
                $i++;
            }
            mysql_close();
            $printMe = array();
            echo '<p>Table of Contents<br>';
            foreach($sections as $section){
                $printMe[$section['section']] = array();
                echo '<a href="#'. $section['section'] . '">' . $section['section'] . '</a><br>';
            }
            echo '</p>';

            foreach($terms as $term){
                array_push( $printMe[$term['section']], '<span class="bold" style="text-transform:capitalize;">' . $term['term'] . '</span>: ' . $term['meaning'] . '<br>' );
            }

            foreach($sections as $section){
                array_push( $printMe[$section['section']], '<a href="#content">Top</a>' );
            }

            foreach($printMe as $section => $term){
                echo '<div><p id="'.$section.'" class="redHeader aligncenter font17pt">' . $section . '</p><p>';
                foreach($term as $whatever){
                    echo $whatever;
                }
                echo '</p></div>';
            }
//                foreach($sections as $section){
//                    $str =  '<div><p class="redHeader aligncenter font14pt">' . $section['section'] . '</p><p>';
//                    //echo $section['section'];
//                    foreach( $terms as $term){
//                        if($term['section'] == $section['section']){
//                            $str .= '<span class="bold" style="text-transform:capitalize;">' . $term['term'] . '</span>: ' . $term['meaning'] . '<br>';
//                            //echo $term['term'] . '<br>';
//                        }
//                    }
//                    $str .= '</p></div>';
//                    echo $str;
//                }
            ?>

        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>

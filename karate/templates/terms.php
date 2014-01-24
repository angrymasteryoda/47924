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
            echo '<p class="termLinks">Table of Contents<br>';
            foreach($sections as $section){
                $printMe[$section['section']] = array();
                echo '<a href="#'. $section['section'] . '" data-id="' . $section['section'] . '">' . $section['section'] . '</a><br>';
            }
            echo '</p>';

            foreach($terms as $term){
                array_push( $printMe[$term['section']], '<span class="bold" style="text-transform:capitalize;">' . $term['term'] . '</span>: ' . $term['meaning'] . '<br>' );
            }

            foreach($sections as $section){
                array_push( $printMe[$section['section']], '<a href="#content">Top</a>' );
            }

            $i = 0;
            foreach($printMe as $section => $term){
                echo '<div class="'. ( ($i==0) ? ('activeTerms') : ('none') ) .' termDefs" id="'.$section.'"><p class="redHeader aligncenter font17pt">' . $section . '</p><p>';
                foreach($term as $whatever){
                    echo $whatever;
                }
                echo '</p></div>';
                $i++;
            }
            ?>
            <div>
                <p>
                    The following Translations were borrowed with the permission of a great Web Site,  I requested permission to use the "Translations" and received permission... with the request that I would give credit to their site.....Well as we were retyping and editing  and adding and subtracting.......I lost the Site to whom I owe the credit for this page of Translations,......First my apologies....Secondly if anyone that reads this page can Please advise me of the site I will do the following......1. Apologize and 2. Give proper credit where it is due.<br>
                    <br>
                    Sincerely,  With Respect,<br>
                    Arnold R Sandubrae<br>
                </p>
            </div>

        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>

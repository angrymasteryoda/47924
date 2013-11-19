<?php
include_once '../config/global.php';
if ($_SESSION['time'] + 10 * 60 < time()) {
    unset( $_SESSION['time'] );
    unset( $_SESSION['username'] );
    header( 'Location: ../books/login.php' ) ;
} else {
    if( empty( $_SESSION['username'] )){
        header( 'Location: ../books/login.php' ) ;
    }
    else{
        $_SESSION['time'] = time();
    }
}
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
    include APP_URL . 'assets/inc/header.php';
    loadDB();
    ?>

    <div id="content">
        <p class="pageTitle">
            Terminology
        </p>
        <div class="addTerm">
            <p class="margin0_left">Add a Term</p>
            <form class="input addTermForm">
                <label >
                    Term: (max 50 characters)
                    <input  type="text" name="term" />
                </label>
                <label>
                    Meaning: (max 65535 characters)
                    <input  type="text" name="meaning" />
                </label>
                <label>
                    Section:
                    <select  name="section">
                        <?php
                        $q = "SELECT `section` FROM `47924`.`mr2358174_karate_enum_sections` AS `mr2358174_karate_enum_sections`;";
                        $r = mysql_query($q);
                        $sections = array();
                        $i = 0;
                        while( ($row = mysql_fetch_assoc( $r ) ) ){
                            $sections[$i] = $row;
                            $i++;
                        }
                        foreach($sections as $section){
                            echo '<option>' . $section['section'] . '</option>';
                        }
                        ?>
                    </select>
                </label>
                <input class="margin10_top input submit" type="submit" value="Add" />
            </form>
        </div>
        <div class="terms margin15_top">
            <p class="redHeader font14pt margin15_top aligncenter">Delete Existing Terms</p>
            <?php
            $q = "SELECT `mr2358174_karate_entity_terms`.`termId`, `mr2358174_karate_entity_terms`.`term`, `mr2358174_karate_entity_terms`.`meaning`, `mr2358174_karate_enum_sections`.`section` FROM `47924`.`mr2358174_karate_entity_terms` AS `mr2358174_karate_entity_terms`, `47924`.`mr2358174_karate_enum_sections` AS `mr2358174_karate_enum_sections` WHERE `mr2358174_karate_entity_terms`.`section` = `mr2358174_karate_enum_sections`.`id`;";
            $r = mysql_query($q);
            $terms = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $terms[$i] = $row;
                $i++;
            }
            mysql_close();

            $printMe = array();
            foreach($sections as $section){
                $printMe[$section['section']] = array();
            }

            foreach($terms as $term){
                array_push( $printMe[$term['section']], '<p><span class="bold" style="text-transform:capitalize;">' . $term['term'] . '</span>: ' . $term['meaning'] . '<br><span class="termDelete" termId="' . $term['termId'] .'">Delete</span></p>' );
            }


            foreach($printMe as $section => $term){
                echo '<div><p class="redHeader aligncenter font14pt">' . $section . '</p><p>';
                foreach($term as $whatever){
                    echo $whatever;
                }
                echo '</p></div>';
            }
//            echo '<p>';
//            foreach($terms as $term){
//                echo '<p><span class="bold" style="text-transform:capitalize;">' . $term['term'] . '</span>: ' . $term['meaning'] . '<br>';
//                echo 'Section: ' . $term['section'] . '<br>';
//                echo '<span class="termDelete" termId="' . $term['termId'] .'">Delete</span></p>';
//            }
//            echo '</p>';
            ?>

        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>

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
            Active Black Belts
        </p>
        <div class="margin10">
            <?php
            loadDB(DB_NAME);
            $q = "SELECT `blackbelt_id`, `name`, `belt_degree`, `bio`, `image_url` FROM `mr2358174_karate_entity_blackbelts` AS `mr2358174_karate_entity_blackbelts`;";
            $r = mysql_query($q);
            $blackBelts = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $blackBelts[$i] = $row;
                $i++;
            }


//            foreach($blackBelts as $blackBelt){
////                blackBeltId="' . $blackBelt['blackbelt_id'] .'"
//                echo '
//                <div class="margin15_bottom" blackBeltId="' . $blackBelt['blackbelt_id'] .'">
//                    <div class="floatleft width20">
//                        <img src="'.APP_URL.'assets/img/'.$blackBelt['image_url'].'" height="200" width="180"/>
//                    </div>
//                    <div class="floatleft width80">
//                        <p>
//                            '.$blackBelt['name'].'<br>
//                            '.$blackBelt['belt_degree'] . ( ($blackBelt['belt_degree'] > 3) ? ('th degree black belt') : ( ( ($blackBelt['belt_degree'] > 2) ? ('rd degree black belt') : ( ( ($blackBelt['belt_degree'] > 1) ? ('nd degree black belt') : ( 'st degree black belt' ) ) ) ) ) ) .'
//                            <br><br>
//                            '.$blackBelt['bio'].'
//                        <p>
//                    </div>
//                    <div class="clear"></div>
//                </div>
//                ';
//            }
            ?>

        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>

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
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <p class="pageTitle">
            News and Events
        </p>
        <div class="margin10">

            <p>
                Here are some snapshots from our last event.
            </p>
            <?php
            //TODO This is sucking to the extreme look at making better if time allows
            $paths = glob( APP_URL . 'assets/img/event/*.png' );

            echo '<div class=slideShow>';
            for ( $i = 0; $i < count( $paths ); $i++ ) {
                echo '
                <div class="floatleft">
                    <img src="'. $paths[$i] .'" width="275" height="275"/>
                </div>';
                if ( ($i+1)%3 == 0 ) {
                    echo '<div class="clear"></div>';
                }
            }
            echo '<div class="clear"></div>';
            echo '</div>';
            ?>

        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>

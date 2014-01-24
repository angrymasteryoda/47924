<?php
include_once '../config/global.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include '../assets/inc/meta.php';
    session_destroy();
    ?>
</head>
<body>
<div id="wrapper">
    <?php
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <div class="slideShow">

            <?php
            $paths = glob( APP_URL . 'assets/img/event/*.png' );

            echo '<div class="fader" id="fader"><div class="slides">';
            for ( $i = 0; $i < count( $paths ); $i++ ) {
                echo '<img class="slide" src="'. $paths[$i] .'" '. ( ($i==0) ? ('curSlide="0"') : ('') ).' />';
            }
            echo '</div></div>';
            ?>
            <div class='arrows'>
                <span class="leftArrow">&nbsp;</span><span class="rightArrow">&nbsp;</span>
            </div>
        </div>

    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>


</body>
</html>



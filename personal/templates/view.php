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

<?php
include '../assets/inc/header.php';
?>
<!-- end of header -->

<div id="main" class="clear"><span class="tm_bottom"></span>

    <div class="content">

<!--        <div class="content_box">-->
<!--            <h1>Short Info of <span>Web Tech Blog</span></h1>-->
<!---->
<!--            Donec iaculis felis id neque.-->
<!--            Praesent varius egestas velit. Donec a massa ut pede pulvinar vulputate. Nulla et augue. Sed eu nunc-->
<!--            quis pede tristique suscipit. Nam sit amet justo vel libero tincidunt dignissim. Cras magna velit,-->
<!--            pellentesque mattis, faucibus vitae, feugiat vitae, sapien. Fusce ac orci sit amet velit ultrices-->
<!--            condimentum. Integer imperdiet odio ac eros. Ut id massa. Nullam nunc. Vivamus sagittis varius lorem.-->
<!--            </p>-->
<!--        </div>-->

        <?php

        if ( isset($_GET['title']) ) {
            $hasThumb = false;
            if ( isset($message['thumbnail']) ) {
                if ( !empty( $message['thumbnail'] ) ) {
                    $hasThumb = true;
                }
            }

            $collection = loadDB('posts');

            $data = $collection->findOne( array( 'hash' => $_GET['title'] ) );

            echo '
            <div class="post_box">
                <div class="header">
                    <h2><a href="'.APP_URL .'templates/view.php?title='. md5($data['title']) .'">'.$data['title'].'</a></h2>

                    <div class="tag"><strong>Tags: </strong>';
            foreach ( $data[ 'tags' ] as $tag ) {
                echo '<a href="'.APP_URL .'templates/categories.php?cat='.$tag.'">'. $tag .', </a>';
            }

            echo '
                    </div>
                    <span class="posted_date">
                        '.Core::simpleDate( $data['details']['created'] ).'
                    </span>
                </div>

                '.( ($hasThumb) ? ('<img src="'.APP_URL .'assets/img/'.$data['thumbnail'] .'" alt="image"/>') : ('') ) .'


                <div class="'. ( ($hasThumb) ? ('pb_right') : ('') ) .'">
                    <p>
                        '. $data['content'] .'
                    </p>
                </div>
                <div class="clear"></div>
            </div>
            ';
        }
        else {
            Debug::error(404);
        }





        ?>
    </div>

    <?php
    include APP_URL . 'assets/inc/sidebar.php';
    ?>

    <div class="clear"></div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>
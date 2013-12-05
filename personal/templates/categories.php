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

        <div class="content_box">
            <h1>All Posts Tagged <span><?php echo ( (empty($_GET['cat'])) ? ('') : ($_GET['cat']) ) ?></span></h1>
<!--            <p>-->
<!--            Donec iaculis felis id neque.-->
<!--            Praesent varius egestas velit. Donec a massa ut pede pulvinar vulputate. Nulla et augue. Sed eu nunc-->
<!--            quis pede tristique suscipit. Nam sit amet justo vel libero tincidunt dignissim. Cras magna velit,-->
<!--            pellentesque mattis, faucibus vitae, feugiat vitae, sapien. Fusce ac orci sit amet velit ultrices-->
<!--            condimentum. Integer imperdiet odio ac eros. Ut id massa. Nullam nunc. Vivamus sagittis varius lorem.-->
<!--            </p>-->
        </div>

        <?php
        $collection = loadDB('posts');

        $pageData = Core::getPageData('posts');

        $datas = $collection->find( array('tags' => $_GET['cat']) )->sort( array( 'details.created' => MongoCollection::ASCENDING ) )->limit( $pageData['ipp'] )->skip( $pageData['starting'] );
        foreach ( $datas as $x ) {
            $data[] = $x;
        }
        //        Debug::echoArray($data);

        if ( isset($_GET['cat']) ) {
            foreach ( $data as $post ) {
                $hasThumb = false;
                if ( isset($post['thumbnail']) ) {
                    if ( !empty( $post['thumbnail'] ) ) {
                        $hasThumb = true;
                    }
                }

                echo '
            <div class="post_box">
                <div class="header">
                    <h2><a href="'.APP_URL .'templates/view.php?title='. md5($post['title']) .'">'.$post['title'].'</a></h2>

                    <div class="tag"><strong>Tags: </strong>';
                foreach ( $post[ 'tags' ] as $tag ) {
                    echo '<a href="'.APP_URL .'templates/categories.php?cat='.$tag.'">'. $tag .', </a>';
                }

                echo '
                    </div>
                    <span class="posted_date">
                        '.Core::simpleDate( $post['details']['created'] ).'
                    </span>
                </div>

                '.( ($hasThumb) ? ('<img src="'.APP_URL .'assets/img/'.$post['thumbnail'] .'" alt="image"/>') : ('') ) .'


                <div class="'. ( ($hasThumb) ? ('pb_right') : ('') ) .'">
                    <p>
                        '.Posts::summary( $post['content'], 75, $post['title']) .'
                    </p>
                </div>
                <div class="clear"></div>
            </div>
            ';
            }
        }
        else{
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
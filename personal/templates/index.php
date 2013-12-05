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
            <h1>Short Info of <span>Web Tech Blog</span></h1>

                Donec iaculis felis id neque.
                Praesent varius egestas velit. Donec a massa ut pede pulvinar vulputate. Nulla et augue. Sed eu nunc
                quis pede tristique suscipit. Nam sit amet justo vel libero tincidunt dignissim. Cras magna velit,
                pellentesque mattis, faucibus vitae, feugiat vitae, sapien. Fusce ac orci sit amet velit ultrices
                condimentum. Integer imperdiet odio ac eros. Ut id massa. Nullam nunc. Vivamus sagittis varius lorem.
            </p>
        </div>

        <?php
        $collection = loadDB('posts');

        $pageData = Core::getPageData('posts');

        $datas = $collection->find()->sort( array( 'details.created' => MongoCollection::ASCENDING ) )->limit( $pageData['ipp'] )->skip( $pageData['starting'] );
        foreach ( $datas as $x ) {
            $data[] = $x;
        }

        if ( !empty($data) ) {
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
            echo '
            <div class="post_box">
            <div class="header">
                <h2><a href="#">Welcome to your blog</a></h2>
            </div>
            <img src="'.APP_URL.'assets/img/image_01.jpg" alt="image"/>

            <div class="pb_right">
                <p>
                    Login as an admin and make your own posts in the admin section
                </p>

            </div>
            <div class="clear"></div>
            </div>
            ';
        }


        ?>

<!--        <div class="post_box">-->
<!--            <div class="header">-->
<!--                <h2><a href="#">Cum sociis natoque penatibus et magnis</a></h2>-->
<!---->
<!--                <div class="tag"><strong>Tags:</strong> <a href="#">Photography</a>, <a href="#">Free Photos</a>, <a-->
<!--                        href="#">HDR</a></div>-->
<!--                <span class="posted_date">-->
<!--                    25 Feb-->
<!--                    <strong>2010</strong>-->
<!--                </span>-->
<!--            </div>-->
<!--            <img src="--><?php //echo APP_URL ?><!--assets/img/image_01.jpg" alt="image"/>-->
<!---->
<!--            <div class="pb_right">-->
<!--                <p>Praesent mattis varius quam. Vestibulum ullamcorper ipsum nec augue. Vestibulum auctor odio eget-->
<!--                    ante. Nunc commodo, magna pharetra semper vehicula, dui ligula feugiat elit, et euismod nunc orci ut-->
<!--                    libero. Etiam sodales massa vel metus. Mauris et elit quis mauris aliquet luctus.</p>-->
<!---->
<!--                <div class="comment"><a href="#">64 comments</a></div>-->
<!--            </div>-->
<!--            <div class="clear"></div>-->
<!--        </div>-->

<!--        <div class="post_box">-->
<!--            <div class="header">-->
<!--                <h2><a href="#">Quisque dictum pharetra neque</a></h2>-->
<!---->
<!--                <div class="tag"><strong>Tags:</strong> <a href="#">Photography</a>, <a href="#">Free Photos</a>, <a-->
<!--                        href="#">Royalty</a></div>-->
<!--                <span class="posted_date">-->
<!--                    25 Feb-->
<!--                    <strong>2010</strong>-->
<!--                </span>-->
<!--            </div>-->
<!---->
<!--            <p>Aliquam pretium porta odio. Fusce quis diam sit amet tortor luctus pellentesque. Donec accumsan urna-->
<!--                non elit tristique mattis. Vivamus fermentum orci viverra nisl. In nec magna id ipsum aliquam-->
<!--                dictum. Donec euismod enim et risus. Nunc dictum, massa non dignissim commodo, metus quam vehicula-->
<!--                lorem, et dignissim enim augue vitae pede. D</p>-->
<!---->
<!--            <div class="comment"><a href="#">128 comments</a></div>-->
<!--            <div class="clear"></div>-->
<!--        </div>-->
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
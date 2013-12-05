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

        <?php
        if ( isset($_GET['cat'] ) ) {
            echo '
            <div class="content_box">
                <h1>All Posts Tagged <span>'.( (empty($_GET['cat'])) ? ('') : ($_GET['cat']) ).'</span></h1>
            </div>';

            $collection = loadDB('posts');

            $pageData = Core::getPageData('posts');

            $datas = $collection->find( array('tags' => $_GET['cat']) )->sort( array( 'details.created' => MongoCollection::ASCENDING ) )->limit( $pageData['ipp'] )->skip( $pageData['starting'] );
            foreach ( $datas as $x ) {
                $data[] = $x;
            }

            if ( isset($_GET['cat']) && ( !empty($data) || $_GET['cat'] == '*') ) {
                if ( $_GET['cat'] == '*' ) {
                    $tagsC = loadDB('tags');

                    $tags = $tagsC->findOne();
                    echo '<ul class="listMenu">';
                    foreach ( $tags[ 'tags' ] as $tag ) {
                        echo '<li><a href="'. APP_URL .'templates/categories.php?cat='. $tag .'">'. $tag .'</a></li>';
                    }
                    echo '</ul>';

                }
                else{
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
            }
            else{
                Debug::error(1001);
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
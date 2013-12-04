<div class="sidebar">
    <div class="sidebar_box">
        <h3>Categories</h3>

        <div class="sb_content">
            <ul class="sidebar_menu">
                <?php
                if ( isset($data) ) {
                    unset($data);
                }
                $collection = loadDB('tags');
                $tags = $collection->findOne();
                for ( $i = 0; $i < 6; $i++ ) {
                    if ( $i >= count( $tags['tags'] ) ) {
                        break;
                    }
                    echo '<li><a href="'. APP_URL .'templates/categories.php?'. md5($tags['tags'][$i]) .'">'. $tags['tags'][$i] .'</a></li>';
                }
                ?>
<!--                <li><a href="#">Tags</a></li>-->
            </ul>
        </div>
    </div>

<!--    <div class="sidebar_box">-->
<!--        <h3>Archives</h3>-->
<!---->
<!--        <div class="sb_content">-->
<!--            <ul class="sidebar_menu">-->
<!--                <li><a href="#">Tags</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->

</div>
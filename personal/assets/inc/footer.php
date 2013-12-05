<div id="bottom">
    <div class="bottom_box">
        <h5><span>Sed Necest</span> Suspendisse a nibh</h5>

        <p>Duis vitae velit sed dui malesuada dignissim. Donec mollis aliquet ligula. Maecenas adipiscing elementum
            ipsum.</p>
        <a href="#" class="continue">more info</a>
    </div>

    <div class="bottom_box">
        <h5><span>Recently</span> Used Tags</h5>
        <ul class="bottom_box_list">
        <?php
        if ( isset($data) ) {
            unset($data);
        }
        $collection = loadDB('posts');
        $datas = $collection->find()->limit(1)->sort( array( 'details.created' => MongoCollection::DESCENDING ) );

        foreach ( $datas as $x ) {
            $data[] = $x;
        }
        if ( !empty($data) ) {
            foreach ( $data as $recent ) {
                foreach ( $recent[ 'tags' ] as $tag ) {
                    echo '<li><a href="'.APP_URL .'templates/categories.php?cat='. $tag .'">'. $tag .'</a></li>';
                }
//                echo '<li><a href="'.APP_URL .'templates/view.php?title='. md5( $recent['title'] ) .'">'. $recent['tags'] .'</a></li>';
            }
        }
        else{
            echo '<li>No Recent Posts</li>';
        }
        ?>
        </ul>
    </div>

    <div class="bottom_box">
        <h5><span>Recent</span> Blog Post</h5>
        <ul class="bottom_box_list">
            <?php
            if ( isset($data) ) {
                unset($data);
            }
            $collection = loadDB('posts');
            $datas = $collection->find()->limit(3)->sort( array( 'details.created' => MongoCollection::DESCENDING ) );

            foreach ( $datas as $x ) {
                $data[] = $x;
            }
            if ( !empty($data) ) {
                foreach ( $data as $recent ) {
                    echo '<li><a href="'.APP_URL .'templates/view.php?title='. md5( $recent['title'] ) .'">'. $recent['title'] .'</a></li>';
                }
            }
            else{
                echo '<li>No Recent Posts</li>';
            }



            ?>
<!--            <li><a href="#">Duis vitae velit sed lesuada dignissim.</a></li>-->
<!--            <li><a href="#">Donec mollis aliquet ligula.</a></li>-->
<!--            <li><a href="#">Maecenas adipiscing elementum ipsum.</a></li>-->
        </ul>
    </div>

</div>

<footer id="footer">
    <ul class="footer_menu">
        <li class="first"><a href="<?php echo APP_URL ?>templates/">Home</a></li>
        <li><a href="<?php echo APP_URL ?>templates/contact.php">Contact</a></li>
        <li><a href="<?php echo APP_URL ?>templates/categories.php?cat=*">Other Links</a></li>
        <li><a href="#">Other Links</a></li>
        <li><a href="#">Other Links</a></li>
        <li><a href="#">Other Links</a></li>
    </ul>
</footer>

<?php
include_once '../config/global.php';
checkLogin();
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
            <h1><span>Administrator Section</span></h1>

<!--            Donec iaculis felis id neque.-->
<!--            Praesent varius egestas velit. Donec a massa ut pede pulvinar vulputate. Nulla et augue. Sed eu nunc-->
<!--            quis pede tristique suscipit. Nam sit amet justo vel libero tincidunt dignissim. Cras magna velit,-->
<!--            pellentesque mattis, faucibus vitae, feugiat vitae, sapien. Fusce ac orci sit amet velit ultrices-->
<!--            condimentum. Integer imperdiet odio ac eros. Ut id massa. Nullam nunc. Vivamus sagittis varius lorem.-->
            </p>
        </div>

        <div class="post_box">
            <div class="header">
                <h2><a href="#">Create a post</a></h2>
            </div>
            <p>
                <a href="<?php echo APP_URL ?>back/createPost.php" >Create a post</a>
            </p>

            <div class="clear"></div>
        </div>

    </div>
    <div class="clear"></div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>
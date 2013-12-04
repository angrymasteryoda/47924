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

    <div class="largeForm">
        <h1>Create A Post</h1>

        <script type="text/javascript" src="../libraries/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinymce.init({
                selector: "textarea",
                theme: "modern",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor"
                ],
                toolbar1: "insertfile undo redo |styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview media | forecolor backcolor",
                image_advtab: true,
                templates: [
                    {title: 'Test template 1', content: 'Test 1'},
                    {title: 'Test template 2', content: 'Test 2'}
                ]
            });
        </script>
        <form class="postForm">
            <div class="errors"></div>
            <label>Title
                <input type="text" placeholder="Title" name="title" data-type="words" />
            </label>

            <textarea name="content" placeholder="Post content" class="width100 margin20_bottom block" ></textarea>
<!--            <hr>-->
            <label>Tags (separate with spaces)
                <input type="text" placeholder="Tags" name="tags" data-type="words" />
            </label>
            <label>Post Thumbnail (optional)
                <input type="text" placeholder="Thumbnail" name="thumbnail" />
            </label>
            <div class="aligncenter">
                <input type="submit" value="Post it" class="width40"/>
            </div>
        </form>

    </div>
    <div class="clear"></div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>
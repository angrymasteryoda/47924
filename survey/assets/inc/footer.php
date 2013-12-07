<footer id="footer">
    <p>
        <a href="<?php echo APP_URL?>templates/">Home</a> |
        <a href="<?php echo APP_URL?>templates/surveyListing.php">Surveys</a>
        <?php
        if ( checkLogin(false) ){
            echo ' | <a href="'. APP_URL .'templates/logout.php">Log Out</a>';
        }
        else{
            echo ' | <a href="'. APP_URL .'templates/login.php">Log In</a>';
        }
        ?>
    </p>
</footer>
<?php
Core::loadJavascript();
?>
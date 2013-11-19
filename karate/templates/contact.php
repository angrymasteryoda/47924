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
<div id="wrapper">
    <?php
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <p class="pageTitle">
            Contact Us
        </p>

        <div class="width35 centermargin">
            <form class="input contactForm" >
                <p id="errors">

                </p>
                <label>Name:
                    <input name="username" type="text" placeholder="Name" data-type="name"/>
                </label>
                <label>Email:
                    <input name="email" type="text" placeholder="Email" data-type="email"/>
                </label>
                <label class="floatleft phoneInput">Phone Number:
                    <input class="" name="phone" type="text" placeholder="Phone Number" data-type="usphone" />

                </label>
                <label class="floatleft extensionInput">Extension:
                    <input class="" name="extension" type="text" placeholder="Extension" data-type="extension" />
                </label>
                <div class="clear"></div>
                <label>Message:
                    <textarea placeholder="Message" name="message" data-type="length-7"></textarea>
                </label>
                <input class="submit" type="submit" value="Send" />
            </form>
        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>


</body>
</html>



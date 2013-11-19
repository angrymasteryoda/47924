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
            Sign up for lessons
        </p>

        <div class="width35 centermargin">
            <form class="input signUpForm" >
                <p id="errors">

                </p>
                <label>Name:
                    <input name="name" type="text" placeholder="Name" data-type="name"/>
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
                <label>Age:
                    <input name="age" type="text" placeholder="Age" data-type="numbers"/>
                </label>
                <label>Who are the lessons for<br/>
                    Child: <input name="whoFor" type="radio" value="Child" data-type="whoFor" data-pass="true" />
                    Self:<input name="whoFor" type="radio" value="Self" data-type="whoFor" data-pass="false" /><br/>
                </label>
                <div class="none whoFor margin10_top">
                    <label>Parent Name
                        <input type="text" placeholder="Parent Name" name="pName" data-type="pName" data-parent="whoFor"/>
                    </label>
                    <label>Parent Cell Phone
                        <input type="text" placeholder="Parent Cell Phone" name="pCell" data-type="pCell" data-parent="whoFor"/>
                    </label>
                </div>
                <label>Past Experience<br/>
                    Yes: <input name="experience" type="radio" value="Yes" data-type="experience" data-pass="true" />
                    No:<input name="experience" type="radio" value="No" data-type="experience" data-pass="false" /><br/>
                </label>
                <div class="none experience margin10_top">
                    <label>Current Belt
                        <input type="text" placeholder="Current Belt" name="belt" data-type="belt" data-parent="experience"/>
                    </label>
                </div>
                <label>Other Comments:
                    <textarea placeholder="Comments" name="comments" ></textarea>
                </label>
                <input class="submit" type="submit" value="Sign Up" />
            </form>
        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>


</body>
</html>



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
        loadDB(DB_NAME);
    ?>

    <div id="content">
        <div class="aligncenter redHeader font14pt">
            Welcome to our Dojo in Cathedral City California<br/>
            <br/>
        </div>
        <div class="floatleft homeLeftCol">
            <p>
                Conveniently located in the Inland Empire, the dojo is a great place to learn and practice the sport of Isshinryu. Led by Sensei Arnold R Sandubrae (8th Degree Black Belt).
            </p>
            <p>
                We  have enough room to do Sparring and Self Defense in 5 full sized permanent rings, Kata and Weapons comfortably with mirrors, We also have 6 hanging bags, rubber floors, a room for consultation and counseling, as well as a room with about a 12 inch thick foam floor for beginners to learn to fall properly without fear of hurting themselves, storage space for our required supplies. As well as seperate Men's and Women's rest rooms with  Lockers. We also have a Knife Throwing, and Zen Archery Range.
            </p>
        </div>
        <div class="floatleft homeRightCol">
            <h3 class="aligncenter redHeader">Events</h3>
            <div>
                <?php include APP_URL . 'assets/inc/calender.php';//echo Core::drawDivCalendar('calendar', 13)?>
            </div>
        </div>
        <div class="clear"></div>

        <div class="floatleft width45">
            <p class="redHeader font13pt">
                Classes And Schedules
            </p>
            <p>
                Kid's, 4 years old and older<br/>
                5:00 Pm. to 6:00 Pm. Tuesday's and Thursday's<br/>
                6:00 Pm. to 7:00 Pm. Tuesday's and Thursday's<br/>
                Parents can decide either one class or both for no extra cost<br>
                <br>
                Adults only<br>
                7:00 Pm. to 8:00 Pm. Tuesday's and Thursday's<br>
                <br>
                New Students Start Weekly<br>
            </p>
        </div>
        <div class="floatleft width25">
            <p class="redHeader font13pt">
                Costs
            </p>
            <p>
                99.00 per Month<br>
                Free Uniform<br>
                Free Trial Class<br>
                No Contracts<br>
                Veterans get 3 Months Free<br>
                Ask about special family rates<br>
            </p>
        </div>
        <div class="floatleft map aligncenter">
            <p class="redHeader margin20_bottom font14pt padding5">Where to find us</p>
            <iframe style="margin-left: 12px;" width="308" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=68225+Ramon+Road+at+Whispering+Palms++Cathedral+City&amp;hl=en&amp;sll=37.269174,-119.306607&amp;sspn=11.374613,19.753418&amp;hnear=68225+Ramon+Rd,+Cathedral+City,+California+92234&amp;t=m&amp;ie=UTF8&amp;hq=&amp;ll=33.816593,-116.473103&amp;spn=0.014262,0.025663&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br />
        </div>
        <div class="clear"></div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>


</body>
</html>



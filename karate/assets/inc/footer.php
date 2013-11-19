<div id="footer">
    <p class="floatleft">
    68225 Ramon Road at Whispering Palms <br>
    Cathedral City, Ca 92234<br>
    (760) 568-0961
    </p>

    <p class="floatright margin15_right">
        <a class="margin5_right" href="<?php echo APP_URL?>templates/contact.php">Contact</a>
        <a target="_blank" class="margin5_right" href="https://maps.google.com/maps?q=68225+Ramon+Road+at+Whispering+Palms++Cathedral+City&hl=en&sll=37.269174,-119.306607&sspn=11.374613,19.753418&hnear=68225+Ramon+Rd,+Cathedral+City,+California+92234&t=m&z=16&iwloc=A">
            Map</a>
        <a href="#content">Top</a>
    </p>
    <p class="clear"></p>
</div>
<?php
if($DATABASE_LOADED){
    mysql_close();
}
?>
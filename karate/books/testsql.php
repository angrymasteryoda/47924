<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/11/13
 * Time: 3:04 PM
 * To change this template use File | Settings | File Templates.
 */

    Define('DB_USER', 'root');
    Define('DB_PASSWORD', '');
    Define('DB_HOST', 'localhost');
    Define('DB_NAME', 'karate_local');

    $r = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

    if (!$r) {
        echo "Could not connect to server\n";
        trigger_error(mysql_error(), E_USER_ERROR);
    } else {
        echo "Connection established\n";
    }

    echo mysql_get_server_info() . "\n";

    $r2 = mysql_select_db(DB_NAME);
if (!$r2) {
    echo "Cannot select database\n";
    trigger_error(mysql_error(), E_USER_ERROR);
} else {
    echo "Database selected\n";
}

$query = "INSERT INTO entityterm VALUES(1,'age uke','Upward block.','Blocks')";
mysql_query($query);

    mysql_close();

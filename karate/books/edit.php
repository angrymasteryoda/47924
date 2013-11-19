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
<div id="wrapper">
    <?php
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <?php
        if(isset($_SESSION['editConfirm']) && isset($_SESSION['editId']) && isset($_SESSION['editingId'])){
            if ( $_GET['e'] == $_SESSION['editConfirm'] && md5($_GET['id']) == $_SESSION['editId'] && md5($_GET['sid']) == $_SESSION['editingId'] ) {
                $canProceed = true;
                unset($_SESSION['editConfirm']);
                unset($_SESSION['editId']);
                unset($_SESSION['editingId']);

                //begin making the page since we are the real user (most likely)
                loadDB(DB_NAME);
                $q = "SELECT `mr2358174_karate_entity_edit`.`edit_id`, `mr2358174_karate_entity_edit`.`title`, `mr2358174_karate_entity_edit`.`form`,
                `mr2358174_karate_enum_tables`.`table`, `mr2358174_karate_entity_edit`.`allowed_parents`, `mr2358174_karate_entity_edit`.`editing_id` FROM `47924`.`mr2358174_karate_enum_tables`
                AS `mr2358174_karate_enum_tables`, `47924`.`mr2358174_karate_entity_edit` AS `mr2358174_karate_entity_edit`
                WHERE `mr2358174_karate_enum_tables`.`table_id` = `mr2358174_karate_entity_edit`.`table` AND `mr2358174_karate_entity_edit`.`edit_id` = '" . $_GET['id'] . "';";
                //$q = "SELECT `edit_id`, `title`, `form` FROM `mr2358174_karate_entity_edit` WHERE `edit_id` = '" . $_GET['id'] . "';";
                $r = mysql_query($q);
                $edits = array();
                $i = 0;
                while( ($row = mysql_fetch_assoc( $r ) ) ){
                    $edits[$i++] = $row;
                }
                $edit = $edits[0];

                $q ="SELECT * FROM `" . $edit['table'] . "` WHERE `" . $edit['editing_id'] . "` = '" . $_GET['sid'] . "';";
                $r = mysql_query($q);
                $data = array();
                $i = 0;
                while( ($row = mysql_fetch_assoc( $r ) ) ){
                    $data[$i++] = $row;
                }
                //Debug::echoArray($data);

                echo '
                <p class="pageTitle">'.
                    $edit['title'] .'
                </p>
                ';

                //print the form out
                echo '<div class="width35 centermargin">';
                include APP_URL . $edit['form'];
                echo '</div>';
                closeDB();
            }
            else{
                echo '<div class="width35 centermargin aligncenter">
                    This Form Has Expired Please Try Again
                </div>';
            }
        }
        else{
            echo '<div class="width35 centermargin aligncenter">
                This Form Has Expired Please Try Again
            </div>';
        }
        ?>

<!--        <div class="width35 centermargin">-->
<!--            <form class="input signUpForm" >-->
<!---->
<!--            </form>-->
<!--        </div>-->
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>


</body>
</html>



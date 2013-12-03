<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/4/13
 * Time: 4:39 PM
 * To change this template use File | Settings | File Templates.
 */
include '../config/global.php';

loadClasses();
switch( Security::sanitize( $_POST['header'] ) ){
    case 'signUp':

        break;
    case 'login':
        $errors = Validation::validate(array(
            array('field' => 'username', 'type' => 'username'),
            array('field' => 'password', 'type' => 'password')
        ), $_POST);

        $canProceed = $errors['pass'];

        if($canProceed){
            $collection = loadDB('users');

            $username = strtolower( Security::sanitize( $_POST['username'] ) );
            $found = $collection->findOne( array(
                'username' => $username,
                'password' =>  md5( Security::sanitize( $_POST['password'] ) ),
            ) );

            if ( !empty( $found ) ) {
                if ( !$found['active'] ) {
                    $errors['banned'] = true;
                    $errors['login'] = false;
                }
                else{
                    $collection->update( array(
                        'username' => $username,
                        'active' => true
                    ), array( '$set' => array(
                        'details.browser' => Core::parseUserAgent(),
                        'details.lastIp' => Core::getClientIP()
                    ) ) );

                    $errors['login'] = true;
                    $_SESSION['time'] = time();
                    $_SESSION['username'] = $found['username'];
                    $_SESSION['sessionId'] = md5( $found['username'] );
                    $_SESSION['rights'] = $found['rights'];
                }
            }
            else{
                $errors['login'] = false;
            }
        }
        echo json_encode($errors);
        break;
    default:
        echo 'I derpped sorry';
}
flush();
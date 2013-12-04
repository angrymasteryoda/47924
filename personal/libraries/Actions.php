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
        $username = strtolower( Security::sanitize($_POST['username']) );

        $errors = Validation::validate(array(
            array( 'field' => 'username', 'type' => 'username'),
            array( 'field' => 'password', 'type' => 'password, match', 'matchId' => 1),
            array( 'field' => 'confPassword', 'type' => 'password, match', 'matchId' => 1),
            array( 'field' => 'email', 'type' => 'email, match', 'matchId' => 2),
            array( 'field' => 'confEmail', 'type' => 'email, match', 'matchId' => 2),
        ),$_POST);

        $canProceed = $errors['pass'];

        if ( $canProceed ) {
            $collection = loadDB('users');

            $input = array(
                'username' => $username,
                'password' => Security::md5( Security::sanitize( $_POST['password'] ) ),
                'email' => Security::sanitize( $_POST['email'] ),
                'rights' => array(
                    'comment'
                ),
                'details' => array(
                    'created' => time('NOW'),
                    'browser' => Core::parseUserAgent(),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'lastIp' => $_SERVER['REMOTE_ADDR']
                ),
                'active' => true
            );

            $usernameTaken = $collection->count( array('username' => $username) );
            $emailTaken = $collection->count( array('email' =>  Security::sanitize( $_POST['email'] )) );
            $canSubmit = true;

            if( $usernameTaken == 1 ){
                $canSubmit = false;
                $errors['usernameTaken'] = true;
            }
            if( $emailTaken == 1){
                $canSubmit = false;
                $errors['emailTaken'] = true;
            }

            if( $canSubmit ){
                $collection->insert($input);
            }
        }

        echo json_encode($errors);
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

    case 'createPost' :
        $errors = Validation::validate(array(
            array('field' => 'title', 'type' => 'words'),
            array('field' => 'tags', 'type' => 'words'),
        ), $_POST);

        $canProceed = $errors['pass'];

        if($canProceed){
            $collection = loadDB('posts');
            $tagsColl = loadDB('tags');

            $input = array(
                'title' => Security::sanitize( $_POST['title'] ),
                'tags' => explode( ' ', Security::sanitize( $_POST['tags'] ) ) ,
                'content' => $_POST['content'],
                'thumbnail' => $_POST['thumbnail'],
                'keywords' => array(),
                'details' => array(
                    'created' => time('NOW'),
                    'username' => $_SESSION['username'],
                    'allowComments' => true
                ),
                'comments' => array()
            );

            //insert new tags if any into master list
            $existingTags = $tagsColl->findOne( array('masterTagList' => true) );
            $allTags = array_merge( $existingTags['tags'], explode( ' ', Security::sanitize( $_POST['tags'] ) ) );
            $allTags = array_unique($allTags);

            $titleTaken = $collection->count( array('title' => Security::sanitize( $_POST['title'] )) );
            $canSubmit = true;

            if ( $titleTaken == 1 ) {
                $errors['titleTaken'] = true;
                $canSubmit = false;
            }

            if ( $canSubmit ) {
                $collection->insert($input);
                $tagsColl->update( array('masterTagList' => true), array( '$set' => array('tags' => $allTags) ) );
//                Debug::echoArray($input);
            }
        }
        echo json_encode($errors);
        break;

    default:
        echo 'I derpped sorry';
}
flush();
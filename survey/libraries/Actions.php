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
    case 'signup':
        $username = Security::sanitize($_POST['username']);
        $canProceed = true;


        $errors = Validation::validate(array(
            array( 'field' => 'username', 'type' => 'username'),
            array( 'field' => 'password', 'type' => 'complex-password, match', 'matchId' => 1),
            array( 'field' => 'confirmPassword', 'type' => 'complex-password, match', 'matchId' => 1),
            array( 'field' => 'email', 'type' => 'email, match', 'matchId' => 2),
            array( 'field' => 'confirmEmail', 'type' => 'email, match', 'matchId' => 2),
        ),$_POST);

        $canProceed = $errors['pass'];

        if ( $canProceed ) {
            $collection = loadDB('users');

            $input = array(
                'username' => $username,
                'password' => Security::md5( Security::sanitize( $_POST['password'] ) ),
                'email' => Security::sanitize( $_POST['email'] ),
                'roles' => array(
                    'take',
                    'results'
                ),
                'details' => array(
                    'created' => time('NOW'),
                    'browser' => Core::parseUserAgent(),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'lastIp' => $_SERVER['REMOTE_ADDR']
                ),
                'surveys' => array(
                    'created' => array(),
                    'taken' => array()
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
            if( $emailTaken ){
                $canSubmit = false;
                $errors['emailTaken'] = true;
            }

            if( $canSubmit ){
                $collection->insert($input);
            }
        }

        echo json_encode( $errors );

        break;

    case 'login' :
        $errors = Validation::validate(array(
            array('field' => 'username', 'type' => 'username'),
            array('field' => 'password', 'type' => 'complex-password',)
        ), $_POST);

        $canProceed = $errors['pass'];

        if($canProceed){
            $dbName = DB_NAME;
            $connection = new Mongo(DB_HOST);
            $db = $connection->$dbName;
            $collection = $db->users;

//            $connection->close();

            $username = Security::sanitize( $_POST['username'] );
            $found = $collection->findOne( array(
                'username' => Security::sanitize( $_POST['username'] ),
                'password' =>  md5( Security::sanitize( $_POST['password'] ) )
            ) );

            if ( !empty( $found ) ) {
                $collection->update( array(
                    'username' => $username
                ), array( '$set' => array(
                    'browser' => Core::parseUserAgent(),
                    'lastIp' => Core::getClientIP()
                ) ) );

                if ( Security::sanitize( $_POST['back'] ) == 'true' ) {
                    ( $found['roles'][0] == '*' ) ? ( $errors['perm'] = true ) : ( $errors['perm'] = false );
                    if ( $errors['perm'] ) {
                        $errors['login'] = true;
                        $_SESSION['time'] = time();
                        $_SESSION['username'] = $found['username'];
                        $_SESSION['sessionId'] = md5( $found['username'] );
                        $_SESSION['roles'] = $found['roles'];
                        $errors['a'] = true;
                    }
                }
                else{
                    $errors['login'] = true;
                    $_SESSION['time'] = time();
                    $_SESSION['username'] = $found['username'];
                    $_SESSION['sessionId'] = md5( $found['username'] );
                    $_SESSION['roles'] = $found['roles'];
                }
            }
            else{
                $errors['login'] = false;
            }
        }
        echo json_encode($errors);
        break;

    case 'createSurvey' :
//        Debug::echoArray($_POST);
//        return;
        $title = Security::sanitize( $_POST['title'] );
        $doValidate = array(
            array( 'field' => 'title', 'type' => 'words'),
            array( 'isQuestions' => true, 'type' => 'words')
        );

        $errors = Validation::validate($doValidate, $_POST, true);

        $canProceed = $errors['pass'];

        if($canProceed){
            $dbName = DB_NAME;
            $connection = new Mongo(DB_HOST);
            $db = $connection->$dbName;
            $collection = $db->surveys;
            
            $input = array(
                'title' => $title,
                'hash' => md5($title),
                'questions' => $_POST['questions'],
                'details' => array(
                    'createdBy' => $_SESSION['username'],
                    'createdTime' => time(),
                    'taken' => 0
                )
            );

            $titleTaken = $collection->count( array('title' => $title) );

            $canSubmit = true;
            if( $titleTaken ){
                $canSubmit = false;
                $errors['titleTaken'] = true;
            }

            if( $canSubmit ){
                $collection->insert($input);
            }
        }
        else{

        }
        echo json_encode($errors);
        break;

    case 'takeSurvey':
        Debug::echoArray($_POST);
        break;


    default:
        echo 'I derpped sorry';
}

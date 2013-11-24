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

        $errors = Validation::oldValidate( array(
            'username' => $username,
            'password' => Security::sanitize( $_POST['password'] ),
            'email' => Security::sanitize( $_POST['email'] )
        ));

        foreach( $errors as $err){
            if ( !$err ) {
                $canProceed = false;
            }
        }
        //do validation here
        /*'username'
        'pass'
        'email'*/
        if ( $canProceed ) {
            $dbName = DB_NAME;
//            $connection = new MongoClient(DB_HOST);
            $connection = new MongoClient( mongoConnectionGen() );
            $db = $connection->$dbName;

            $collection = $db->users;

            $input = array(
                'username' => $username,
                'password' => Security::md5( Security::sanitize( $_POST['password'] ) ),
                'email' => Security::sanitize( $_POST['email'] ),
                'roles' => array(
                    'take',
                    'edit'
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
                $errors['username'] = 'taken';
            }
            if( $emailTaken ){
                $canSubmit = false;
                $errors['email'] = 'taken';
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

//        Debug::echoArray($errors);
//        $errors = Validation::oldValidate( array(
//            'username' => Security::sanitize( $_POST['username'] ),
//            'password' => Security::sanitize( $_POST['password'] )
//        ));

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
//                        $_SESSION['time'] = time();
//                        $_SESSION['username'] = $found['username'];
//                        $_SESSION['sessionId'] = md5( $found['username'] );
//                        $_SESSION['roles'] = $found['roles'];
                        $errors['a'] = true;
                    }
                }
                else{
                    $errors['login'] = true;
//                    $_SESSION['time'] = time();
//                    $_SESSION['username'] = $found['username'];
//                    $_SESSION['sessionId'] = md5( $found['username'] );
//                    $_SESSION['roles'] = $found['roles'];
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
//        $errors = Validation::validate( array(
//            //TODO vaildate questions
//        ));

        $canProceed = true;

//        foreach( $errors as $err){
//            if ( !$err ) {
//                $canProceed = false;
//            }
//        }

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
                $errors['title'] = 'taken';
            }

            if( $canSubmit ){
                $collection->insert($input);
            }

//            Debug::echoArray($input);
        }
        else{

        }
        echo $errors;
        break;


    default:
        echo 'I derpped sorry';
}

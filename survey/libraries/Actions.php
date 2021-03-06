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
        $username = strtolower( Security::sanitize($_POST['username']) );
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
            array('field' => 'password', 'type' => 'complex-password')
        ), $_POST);

        $canProceed = $errors['pass'];

        if($canProceed){
            $collection = loadDB('users');

//            $connection->close();

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
            array( 'isQuestions' => true, 'type' => 'longWords')
        );

        $errors = Validation::validate($doValidate, $_POST, true);

        $canProceed = $errors['pass'];

        if($canProceed){
            $collection = loadDB('surveys');

            $userCollection = loadDB('users');
            $user = $userCollection->findOne( array('username' => $_SESSION['username'], 'active' => true) );
            array_push( $user['surveys']['created'] , md5($title) );

            //update the created
            $userCollection->update( array('username' => $_SESSION['username'], 'active' => true), array('$set' => array('surveys.created' => $user['surveys']['created']) ) );

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
        echo json_encode($errors);
        break;

    case 'takeSurvey':
        $doValidate = array(
            array( 'field' => 'title', 'type' => 'words'),
            array( 'isAnswers' => true, 'type' => 'longWords')
        );

        $errors = Validation::validate($doValidate, $_POST, true);

        $canProceed = $errors['pass'];

        if($canProceed){
            $collection = loadDB('results');

            $foundSurvey = $collection->findOne( array('hash' => $_POST['hash']) );
//            Debug::echoArray($_POST);

//            return;

            if ( isset( $foundSurvey['hash'] ) ) {
                if ( $foundSurvey['hash'] == $_POST['hash'] ) {
                    $createNew = false;
                }
                else{
                    echo 'this is a huge problem do nothing report this';
                }
            }
            else{
                $createNew = true;
            }

            if ( $createNew ) {
                $answers = array();

                $i = 1;
                foreach ( $_POST['answers'] as $answer ) {
                    $answers[$i++] = array( Security::sanitize( $answer['answer'] ) );
                }


                $input = array(
                    'title' => Security::sanitize( $_POST['title'] ),
                    'hash' => $_POST['hash'],
                    'answers' => $answers,
                    'details' => array(
                        'takenBy' => array($_SESSION['username']),
                        'takenTime' => array(time())
                    )
                );

                $collection->insert( $input );
            }
            else{
                $i = 1;
                foreach ( $_POST['answers'] as $answer ) {
                    array_push( $foundSurvey['answers'][$i++], Security::sanitize( $answer['answer'] ) );
                }

                //set the taken by now
                array_push( $foundSurvey['details']['takenBy'], $_SESSION['username'] );
                //set the taken by now
                array_push( $foundSurvey['details']['takenTime'], time() );

                $collection->update( array( 'hash' => $foundSurvey['hash'] ), array( '$set' => array(
                    'answers' => $foundSurvey['answers'],
                    'details.takenBy' => $foundSurvey['details']['takenBy'],
                    'details.takenTime' => $foundSurvey['details']['takenTime']
                )) );
            }


            //update the user and add the survey to taken
            $userColl = loadDB('users');
            $user = $userColl->findOne( array('username' => $_SESSION['username'], 'active' => true) );
            array_push( $user['surveys']['taken'], $_POST['hash'] );
            $userColl->update( array('username' => $_SESSION['username'], 'active' => true), array( '$set' => array( 'surveys.taken' => $user['surveys']['taken']) ) );

            //update the survey and add the survey to taken
            $surveyColl = loadDB('surveys');
            $survey = $surveyColl->findOne( array( 'hash' => $_POST['hash'] ) );
//            Debug::echoArray($survey);
            $surveyColl->update( array('hash' => $_POST['hash']), array( '$set' => array( 'details.taken' => ++$survey['details']['taken'] ) ) );
        }
//        ++$survey['details']['taken']
        echo json_encode($errors);
        break;

    case 'rights':
//        Debug::echoArray( $_POST );

        $userColl = loadDB('users');

        $user = $userColl->findone( array( 'username' => $_POST[ 'username' ] ) );
        if ( isset($user) ) {

            $rights = array();
            foreach ( $_POST[ 'rights' ] as $right ) {
                array_push( $rights, $right[ 'value' ] );
            }

            $userColl->update( array( 'username' => $_POST[ 'username' ] ), array('$set' => array( 'roles' => $rights )) );

            echo json_encode( array('pass' => true) );
        }
        else{
            echo json_encode( array('pass' => false) );
        }

        break;

    case 'deleteUser':
        $userColl = loadDB('users');

        $user = $userColl->findone( array( 'username' => $_POST[ 'username' ] ) );
        if ( isset($user) ) {

            $userColl->update( array( 'username' => $_POST[ 'username' ] ), array('$set' => array( 'active' => false )) );

            echo json_encode( array('pass' => true) );
        }
        else{
            echo json_encode( array('pass' => false) );
        }

        break;

    case 'deleteSurvey' :
        $collection = loadDB('surveys');
        $resColl = loadDB('results');

        $failed = true;
        if ( !Auth::checkPermissions(SURVEY_DELETE_RIGHTS) ) {
            echo json_encode( array('pass' => false) );
            break;
        }
        $survey = $collection->findOne( array( 'hash' => $_POST['hash'] ) );
        $result = $resColl->findOne( array( 'hash' => $_POST['hash'] ) );

        if ( isset($survey) ) {
            $collection->remove( array( 'hash' => $_POST['hash'] ) );
            if ( isset($result) ) {
                $resColl->remove( array( 'hash' => $_POST['hash'] ) );
            }
            $failed = false;
            $redirect = false;
            if ( isset( $_POST['redirect'] ) ) {
                if ( $_POST['redirect'] == 'true' ) {
                    $redirect = true;
                }
            }
            if ( $redirect ){
                header('Location: ' . APP_URL . 'back/');
            }
            echo json_encode( array('pass' => true) );
        }
        else {
            $failed = true;
        }

        if ( $failed ) {
            echo json_encode( array('pass' => false) );
        }
        break;

    case 'deleteResponse':
        $username = Security::sanitize( $_POST['username'] );
        $hash = Security::sanitize( $_POST['hash'] );
        $exploded = explode('~~', $username);
        $id = $exploded[1];
        $_POST['username'] = $exploded[0];
        $username = $exploded[0];

        $errors = Validation::validate( array(
            array( 'field' => 'username', 'type' => 'username'),
        ), $_POST
        );

        if ( $errors['pass'] ) {
            $collection = loadDB('users');
            $user = $collection->findOne( array( 'username' => $username ) );
            //check if deleting an admin
            if ( Auth::checkPermissions( ADMIN_RIGHTS, $user['roles'] ) && $username != $_SESSION['username'] ) {
                $errors['isAdmin'] = true;
            }
            else {
                $errors['isAdmin'] = false;
                //delete the results
                $resultsColl = loadDB('results');
                $result = $resultsColl->findOne( array( 'hash' => $hash ) );

                for ( $i = 1; $i <= count( $result['answers'] ); $i++ ) {
                    unset( $result['answers'][$i][$id] );
                    $result['answers'][$i] = array_values( $result['answers'][$i] );
                }
                unset( $result['details']['takenBy'][$id] );
                unset( $result['details']['takenTime'][$id] );

                $result['details']['takenBy'] = array_values( $result['details']['takenBy'] );
                $result['details']['takenTime'] = array_values( $result['details']['takenTime'] );

                $newData =  array('$set' => array( 'answers' => $result['answers'], 'details.takenBy' => $result['details']['takenBy'], 'details.takenTime' => $result['details']['takenTime']) );
                $resultsColl->update( array( 'hash' => $hash ), $newData );

                //remove survey from user record
                for ( $i = 0; $i < count( $user['surveys']['taken'] ); $i++ ) {
                    if ( $user['surveys']['taken'][$i] == $hash ) {
                        unset( $user['surveys']['taken'][$i] );
                        break;
                    }
                }

                $user['surveys']['taken'] = array_values($user['surveys']['taken']);
                $collection->update( array( 'username' => $username ), array( '$set' => array( 'surveys.taken' => $user['surveys']['taken'] ) ) );
//                Debug::echoArray($user);
            }
            
        }
        echo json_encode($errors);
        break;

    case 'editPage':
        $collection = loadDB('surveys');

        $data = $collection->findOne( array('hash' => $_POST['hash']) );

        $redirect = false;
        if ( isset( $_POST['redirect'] ) ) {
            if ( $_POST['redirect'] == 'true' ) {
                $redirect = true;
            }
        }


        $errors = array();

        if ( !empty($data) ) {
            $_SESSION['editHash'] = $data['hash'];
            $errors['pass'] = true;
            if ( $redirect ){
                header('Location: ' . APP_URL . 'back/edit.php');
            }
        }
        else{
            $errors['pass'] = false;
        }

        echo json_encode($errors);
        break;

    case 'editSurvey':

        $title = Security::sanitize( $_POST['title'] );
        $doValidate = array(
            array( 'field' => 'title', 'type' => 'words'),
            array( 'isQuestions' => true, 'type' => 'longWords')
        );

        $errors = Validation::validate($doValidate, $_POST, true);

        $canProceed = $errors['pass'];

        if($canProceed){
            $collection = loadDB('surveys');

            $foundSurvey = $collection->findOne( array('hash' => Security::sanitize( $_POST['hash'] ) ) );

            //reset the fields
            $foundSurvey['title'] = $title;
            $foundSurvey['hash'] = md5($title);
            $foundSurvey['questions'] = $_POST['questions'];



            $canSubmit = true;

            if( $canSubmit ){
                $collection->update( array( 'hash' => Security::sanitize( $_POST['hash'] ) ), array( '$set' => array('title' => $foundSurvey['title'], 'hash' => $foundSurvey['hash'], 'questions' => $foundSurvey['questions'] ) ) );
            }
        }
        unset( $_SESSION['editHash'] );
        echo json_encode($errors);
        break;
    default:
        echo 'I derpped sorry';
}
flush();
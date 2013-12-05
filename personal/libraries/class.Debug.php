<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/29/13
 * Time: 3:56 PM
 * To change this template use File | Settings | File Templates.
 */
class Debug {
    static function echoArray($arr){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    static function error($errorCode){
        switch($errorCode){
            case 404 :
                $randomString = array ('There is nothing to see here.', 'This is not the post you were looking for.', 'Huston we have a problem.',
                    'Ahh its dark in here and im lost, quick escape with the link below.', 'Hi i\'m not here at the moment but you can leave me a message below');
                shuffle($randomString);
                $randomResult = $randomString[0] . '';
                echo '
                    <div class="pageTitle">
                        <div class="font23pt">404</div>
                        '.$randomResult.'<br>
                        <a onClick="history.go(-1);" class="underline">Move along now.</a>
                    </div>
                    <hr class="errorHr" />';
                break;


            case 1001 :
//                $randomString = array ('There is nothing to see here.', 'This is not the post you were looking for.', 'Huston we have a problem.',
//                    'Ahh its dark in here and im lost, quick escape with the link below.', 'Hi i\'m not here at the moment but you can leave me a message below');
//                shuffle($randomString);
//                $randomResult = $randomString[0] . '';
                echo '
                    <div class="pageTitle">
                        <div class="font23pt">Oh No!</div>
                        We seemed to have lost that category, so sorry please enjoy this bunny instead<br>
                        <pre>(\__/)
(=\'.\'=)
(")_(")</pre>
                        <a onClick="history.go(-1);" class="underline">Try this page instead.</a>
                    </div>
                    <hr class="errorHr" />';
                break;
        }
    }
}
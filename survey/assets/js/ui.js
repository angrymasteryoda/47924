/**************************************************************************/
/************************************|*************************************/
/**************************************************************************/
/**
 * @author Michael Risher
 */
var APP_URL = '';
$(document).ready(function(){
    APP_URL = $('meta[name="url"]').attr('content');
})

/**************************************************************************/
/*************************************sign up******************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parent = $('.signUpForm');
    var errorBox = $('#errors', parent);
    $('[type=text], [type=password]', parent).on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType ) ){
                if( $(this).hasClass('errorInput') ){
                    $(this).removeClass('errorInput');
                }
            }
            else{
                $(this).addClass('errorInput');
            }
        }
    });

    parent.submit(function(e){
        e.preventDefault();
        var inputs = $('[type=text], [type=password]', '.signUpForm');
        var hasError = false;
        $('.signUpForm #errors').html('');

        for(var i = 0; i < inputs.length; i++){
            var input = inputs.eq(i);
            if( checkRegex( input.val(), input.attr('data-type') ) ){
                if( input.hasClass('errorInput') ){
                    input.removeClass('errorInput');
                }
            }
            else{
                hasError = true;
                input.addClass('errorInput');
                if( input.attr('data-type').match(/conf/) ){
                    $('.signUpForm #errors').append( input.attr('placeholder') +' '+ 'has to match ' + input.attr('data-type').split( 'conf' )[1].toLowerCase() + '<br/>');
                }
                else{
                    $('.signUpForm #errors').append( input.attr('placeholder') +' '+  regex[input.attr('data-type')]['error'] + '<br/>')
                }
            }
        }
        if( hasError ){
            $('.signUpForm #errors').slideDown('slow');
        }
        else{
            $('input[type=submit]').val('Signing up...');
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'signup',
                    'username' : inputs.filter('[name=username]').val(),
                    'password' : inputs.filter('[name=password]').val(),
                    'email' : inputs.filter('[name=email]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    for(var x in data){
                        if(data[x] == 'taken' ){
                            $('.signUpForm #errors').append( x +' has been used already<br/>')
                        }
                        else if(data[x] == false ){
                            e = true;
                            $('.signUpForm #errors').append( x +' ' + regex[x]['error'] + '<br/>')
                        }
                    }

                    if ( e ) {
                        $('.signUpForm #errors').slideDown('slow');
                        $('input[type=submit]').val('Sign up');
                    }
                    else{
                        var Get = $_GET();
                        var goto;
                        try{
                            goto = ( (Get == null) ? ( getApp_Dir( 'templates/' + Get['ref'] ) ) : ( getApp_Dir( "templates/surveyListing.php" ) ) );
                        }
                        catch(e){
                            goto = getApp_Dir( "templates/" );
                        }

                        setTimeout( function(){goTo( goto )}, 250);
                    }
                }
            });
        }
    });

});
//</editor-fold>

/**************************************************************************/
/********************************Log in************************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parent = $('.loginForm');
    var errorBox = $('#errors', parent);

    $('[type=text], [type=password]', parent).on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType ) ){
                if( $(this).hasClass('errorInput') ){
                    $(this).removeClass('errorInput');
                }
            }
            else{
                $(this).addClass('errorInput');
            }
        }
    });

    parent.submit(function(e){
        e.preventDefault();
        var inputs = $('[type=text], [type=password]', parent);
        var hasError = false;
        errorBox.html('');

        for ( var  i = 0; i < inputs.length; i++ ) {
            var input = inputs.eq(i);
            if( checkRegex( input.val(), input.attr('data-type'), parent ) ){
                if( input.hasClass('errorInput') ){
                    input.removeClass('errorInput');
                }
            }
            else{
                hasError = true;
                input.addClass('errorInput');
                if( input.attr('data-type').match(/conf/) ){
                    errorBox.append( input.attr('placeholder') +' '+ 'has to match ' + input.attr('data-type').split( 'conf' )[1].toLowerCase() + '<br/>');
                }
                else{
                    errorBox.append( input.attr('placeholder') +' '+  regex[input.attr('data-type')]['error'] + '<br/>')
                }
            }
        }

        if( hasError ){
            errorBox.slideDown('slow');
        }
        else{
            //ajax
            $('input[type=submit]').val('Logging in...');
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'login',
                    'username' : inputs.filter('[name=username]').val(),
                    'password' : inputs.filter('[name=password]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    for(var x in data){
                        if( x == 'login' ){
                            if ( data[x] ) {
                                //clearForm(inputs);
                                createSuccessBanner('Login Successful');
                                var Get = $_GET();
                                var goto;
                                try{
                                    goto = getApp_Dir( 'templates/' + Get['ref'] );
                                }
                                catch(e){
                                    if ( data['a'] ) {
                                        goto = getApp_Dir( 'back/' );
                                    }
                                    else{
                                        goto = getApp_Dir( 'templates/surveyListing.php' );
                                    }
                                }

                                setTimeout( function(){goTo( goto )}, 250);
                            }
                            else {
                                e = true;
                                errorBox.append( 'Username or Password wrong <br/>')
                            }
                        }
                        else {
                            if( !data[x] ){
                                e = true;
                                errorBox.append( x + getRegex(x)['error'] +' <br/>')
                            }
                        }
                    }

                    if ( e ) {
                        $('input[type=submit]').val('Logging in');
                        errorBox.slideDown('slow');
                    }
                }
            });
        }
    });
});
//</editor-fold>

/**************************************************************************/
/*****************************Admin Log in*********************************/
/**************************************************************************/

/**************************************************************************/
/*********************************Utilities********************************/
/**************************************************************************/
//function checkRegex(str, type, pattern) {
//    if ( type.match(/conf/) ) {
//        var parent = type.split( 'conf' )[1].toLowerCase();
//
//        if( $('[data-type=' + parent + ']', '.verify').val() != ''){
//            if ( $('[data-type=' + parent + ']', '.verify').val() != str ) {
//                return null;
//            }
//        }
//
//        type = parent;
//    }
//    if(type == 'custom'){
//        return pattern.test(str);
//    }
//    else{
//        return str.match(regex[type].regex);
//    }
//}
//
//function getApp_Dir(path){
//    if(path){
//        return APP_URL + path;
//    }
//    else{
//        return APP_URL;
//    }
//
//}
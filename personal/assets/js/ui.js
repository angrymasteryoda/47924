/**************************************************************************/
/************************************|*************************************/
/**************************************************************************/
/**
 * @author Michael Risher
 */
var APP_URL = '';
$(document).ready(function(){
    APP_URL = $('meta[name="url"]').attr('content');
});

var debug = false;

/**************************************************************************/
/******************************Login/SignUp********************************/
/**************************************************************************/

//Login
$(document).ready(function(){
    var parent = $('.loginForm');
    var errorBox = $('.errors', parent);

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

    parent.submit(function(event){
        event.preventDefault();
        var inputs = $('[type=text], [type=password]', parent);
        var hasError = false;
        var isBackend = false;
        if ( $('.adminLogin').length > 0 ) {
            isBackend = true;
        }
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
                    errorBox.append( input.attr('placeholder') +' '+  getRegex(input.attr('data-type'))['error'] + '<br/>')
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

                    //if validation fail
                    if ( !data['pass'] ) {
                        errorBox.html('');
                        for ( var  i = 0; i < data['msg'].length; i++ ) {
                            for(var x in data['msg'][i]){
                                errorBox.append( $('[name='+x+']').attr('placeholder')+ ' ' + data['msg'][i][x] + '<br/>')
                            }
                        }
                        e = true;
                    }

                    //did we log in?
                    if ( !data['login'] && !isset(data['perm']) ) {
                        e = true;
                        if ( data['banned'] ) {
                            errorBox.append( 'You are banned!<br/>');
                        }
                        else{
                            errorBox.append( 'Username or Password wrong <br/>');
                        }
                    }

                    //did we do good
                    if ( e ) {
                        $('input[type=submit]').val('Logging in');
                        errorBox.slideDown('slow');
                    }
                    else{
                        //passed all tests
                        goto = redirectToRef(data);
                        if(!debug)setTimeout( function(){goTo( goto )}, 250);
                        if(debug)clog(goto);
                        if(debug)parent.append('<a href="'+goto+'">redirect to here</a>')
                    }
                }
            });
        }
    })
});
//Sign Up
$(document).ready(function(){
    var parent = $('.signUpForm');
    var errorBox = $('.errors', parent);

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

    parent.submit(function(event){
        event.preventDefault();
        var inputs = $('[type=text], [type=password]', parent);
        var hasError = false;
        var isBackend = false;
        if ( $('.adminLogin').length > 0 ) {
            isBackend = true;
        }
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
                    errorBox.append( input.attr('placeholder') +' '+  getRegex(input.attr('data-type'))['error'] + '<br/>')
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
                    'header' : 'signUp',
                    'username' : inputs.filter('[name=username]').val(),
                    'password' : inputs.filter('[name=password]').val(),
                    'confPassword' : inputs.filter('[name=confPassword]').val(),
                    'email' : inputs.filter('[name=email]').val(),
                    'confEmail' : inputs.filter('[name=confEmail]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;

                    //if validation fail
                    if ( !data['pass'] ) {
                        errorBox.html('');
                        for ( var  i = 0; i < data['msg'].length; i++ ) {
                            for(var x in data['msg'][i]){
                                errorBox.append( $('[name='+x+']').attr('placeholder')+ ' ' + data['msg'][i][x] + '<br/>')
                            }
                        }
                        e = true;
                    }

                    //was the account name taken?
                    if ( data['usernameTaken'] ) {
                        e = true;
                        errorBox.append( 'Username is taken <br/>');
                        $('[name=username]').addClass('errorInput')
                    }

                    if ( data['emailTaken'] ) {
                        e = true;
                        errorBox.append( 'Email is taken <br/>');
                        $('[name=email]').addClass('errorInput')
                    }

                    //did we do good
                    if ( e ) {
                        errorBox.slideDown('slow');
                        $('input[type=submit]').val('Sign up');
                    }
                    else{
                        goto = redirectToRef(data);
                        if(!debug)setTimeout( function(){goTo( goto )}, 250);
                    }
                }
            });
        }
    })
});

/**************************************************************************/
/*********************************post********************************/
/**************************************************************************/
$(document).ready(function(){
    var parent = $('.postForm');
    var errorBox = $('.errors', parent);

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

    parent.submit(function(event){
        event.preventDefault();
        var inputs = $('[type=text], [type=password], textarea', parent);
        var hasError = false;
        var isBackend = false;
        if ( $('.adminLogin').length > 0 ) {
            isBackend = true;
        }
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
                    errorBox.append( input.attr('placeholder') +' '+  getRegex(input.attr('data-type'))['error'] + '<br/>')
                }
            }
        }

        clog( $('[name=content]', parent).html());
        if( hasError ){
            errorBox.slideDown('slow');
        }
        else{
            //ajax
            $('input[type=submit]').val('Posting...');
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'createPost',
                    'title' : inputs.filter('[name=title]').val(),
                    'content' : inputs.filter('[name=content]').val(),
                    'tags' : inputs.filter('[name=tags]').val(),
                    'thumbnail' : inputs.filter('[name=thumbnail]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;

                    //if validation fail
                    if ( !data['pass'] ) {
                        errorBox.html('');
                        for ( var  i = 0; i < data['msg'].length; i++ ) {
                            for(var x in data['msg'][i]){
                                errorBox.append( $('[name='+x+']').attr('placeholder')+ ' ' + data['msg'][i][x] + '<br/>')
                            }
                        }
                        e = true;
                    }

                    if ( data['titleTaken'] ) {
                        e = true;
                        errorBox.append( 'Title has been taken <br/>');
                        $('[name=title]').addClass('errorInput')
                    }

                    //did we do good
                    if ( e ) {
                        $('input[type=submit]').val('Post it');
                        errorBox.slideDown('slow');
                    }
                    else{
                        //passed all tests
//                        goto = redirectToRef(data);
//                        if(!debug)setTimeout( function(){goTo( goto )}, 250);
//                        if(debug)clog(goto);
//                        if(debug)parent.append('<a href="'+goto+'">redirect to here</a>')
                    }

                }
            });
        }
    })
});
adminHeartbeat();
/**************************************************************************/
/*********************************Utilities********************************/
/**************************************************************************/


//make the sortable buttons print there location also
$(document).ready(function(){
    if( $('.sortable, .pagesLinks .pageNum').length > 0){
        var elements = $('.sortable, .pagesLinks .pageNum');
        var pos = elements.eq(0).position();

        for(var i = 0; i < elements.length; i++){
            var x = elements.eq(i).attr('href');
            x = x.replace( /\&pos=\d+/, '');
            elements.eq(i).attr('href', (x + '&pos=' + pos.top));
        }
    }
    var get = $_GET(location.href);
    if( isset( get ) ){
        if( isset( get['pos'] ) ){
            $("html, body").animate({ scrollTop: get['pos'] }, 0);
        }
    }

});

//countdown

$(document).ready(function(){
    if ( $('.countDown').length > 0 ) {
        var counter = $('.countDown');
        var count = parseInt( counter.html() );

        setInterval(function(){
            if ( count > 0 ) {
                counter.html(--count);
            }
            else if( count == 0){
                if ( $('[goto]').length > 0 ) {
                    if(!debug)goTo(  $('[goto]').attr('href') );
                }
                else{
                    if(!debug)goTo( getApp_Dir('templates') );
                }

            }
        }, 1000);
    }
});
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
                        if ( isset(Get['ref']) ) {
                            if ( Get['ref'].match( /.+\/.+/ ) ) {
                                goto = getApp_Dir( Get['ref'] );
                            }
                            else{
                                goto = getApp_Dir( 'templates/' + Get['ref'] );
                            }
                        }
                        else{
                            if ( data['a'] ) {
                                goto = getApp_Dir( 'back/' );
                            }
                            else{
                                goto = getApp_Dir( 'templates/surveyListing.php' );
                            }
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
                    'password' : inputs.filter('[name=password]').val(),
                    'back' : isBackend
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    for(var x in data){
                        if( x == 'login' ){
                            if ( !data[x] ) {
                                e = true;
                                errorBox.append( 'Username or Password wrong <br/>')
                            }
                        }
                        else {
                            if ( x == 'perm' && data[x] == false ) {
                                createSuccessBanner('Login Failed', 'Not an administrator!');
                                e = true;
                                errorBox.append( 'You don\'t have premissions to login here<br>' );
                            }
                            else if( !data[x] ){
                                e = true;
                                errorBox.append( x + getRegex(x)['error'] +' <br/>')
                            }
                        }
                    }

                    if ( e ) {
                        $('input[type=submit]').val('Logging in');
                        errorBox.slideDown('slow');
                    }
                    else{
                        //passed all tests
                        var Get = $_GET();
                        var goto;

                        if ( isset(Get['ref']) ) {
                            if ( Get['ref'].match( /.+\/.+/ ) ) {
                                goto = getApp_Dir( Get['ref'] );
                            }
                            else{
                                goto = getApp_Dir( 'templates/' + Get['ref'] );
                            }
                        }
                        else{
                            if ( data['a'] ) {
                                goto = getApp_Dir( 'back/' );
                            }
                            else{
                                goto = getApp_Dir( 'templates/surveyListing.php' );
                            }
                        }

                        createSuccessBanner('Login Successful');
                        setTimeout( function(){goTo( goto )}, 250);
                    }
                }
            });
        }
    });
});
//</editor-fold>

/**************************************************************************/
/***************************** create survey ******************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">

$(document).ready(function(){

    var parent = $('.createSurveyForm');
    var errorBox = $('#errors', parent);

    $('.addQuestion', parent).click(function(){
        newQuestion();
    });

    parent.on('change' , '.answerType', function(){
        var clicked = $(this);
        $('.answer', clicked.parent() ).fadeOut('normal', function(){
            addAnswer( clicked.val(),  clicked.parent().find('.answer')  );
            $('.answer', clicked.parent() ).slideDown();
        });

    });

    parent.submit(function(e){
        e.preventDefault();
        deleteCookie('qnum');

        toggleThinker(true);


    });


    function toggleThinker(hide){
        if(hide){
            parent.children('table').fadeOut('normal', function(){
                parent.find('#waiting').fadeIn();
            });
        }
        else{
            parent.find('#waiting').fadeOut('normal', function(){
                parent.children('table').fadeIn();
            });
        }

    }

    function newQuestion(){
        var cookies = getCookies();
        if ( !isset(cookies['qnum']) ) {
            var num = 2;
            makeCookie('qnum', '2', 1);
        }
        else{
            num = parseInt( '0' + cookies['qnum'], 10 );
            clog(true)
            makeCookie('qnum', ++num + '', 1);
        }
        $('.addButton', parent).before('<tr data-question="'+ num +'"><td><div class="question none">' +
            '<label>Enter the question <span class="questionNumber">'+num+'</span>.<br>' +
            '<textarea placeholder="Question '+num+'"></textarea></label><br>' +
            'Answer Type: <select class="answerType"><option value="single">Single Answer</option><option value="multi">Multi Answer</option><option value="write">Write In</option><option value="t/f">True/False</option></select>' +
            '<div class="answer none"></div></div></td></tr>');
        $('[data-question='+ num +'] .question').slideDown();
    }

    function addAnswer( type, p ){
        var str = '';
        switch ( type ){
            case 'single':
                break;
            case 'multi' :
                str = '<label>Enter options (seprate with commas)<br><input type="text" name="multiAnswer" placeholder="Enter Options"/></label>'
                break;
        }
        p.html(str);
    }

});
//</editor-fold>
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
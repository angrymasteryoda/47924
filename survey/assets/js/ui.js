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
                    'confirmPassword' : inputs.filter('[name=confirmPassword]').val(),
                    'email' : inputs.filter('[name=email]').val(),
                    'confirmEmail' : inputs.filter('[name=confirmEmail]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    if ( !data['pass'] ) {
                        errorBox.html('');
                        for ( var  i = 0; i < data['msg'].length; i++ ) {
                            for(var x in data['msg'][i]){
                                errorBox.append( $('[name='+x+']').attr('placeholder')+ ' ' + data['msg'][i][x] + '<br/>')
                                $('[name='+x+']').addClass('errorInput')
                            }
                        }
                        e = true;
                    }
                    //was the account name taken?
                    if ( data['usernameTaken'] ) {
                        e = true;
                        errorBox.append( 'Username is taken <br/>')
                        $('[name=username]').addClass('errorInput')
                    }

                    if ( data['emailTaken'] ) {
                        e = true;
                        errorBox.append( 'Email is taken <br/>')
                        $('[name=email]').addClass('errorInput')
                    }


                    //did we do good
                    if ( e ) {
                        $('.signUpForm #errors').slideDown('slow');
                        $('input[type=submit]').val('Sign up');
                    }
                    else{
                        goto = redirectToRef(data);
                        if(!debug)setTimeout( function(){goTo( goto )}, 250);
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
                        errorBox.append( 'Username or Password wrong <br/>')
                    }

                    //do we need permission
                    if ( isset(data['perm']) ) {
                        //do we have permission
                        if ( !data['perm'] ) {
                            e = true;
                            errorBox.append( 'You don\'t have premissions to login here<br>' );
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
//        for ( var  i = 0; i < 9; i++ ) {
//            newQuestion();
//        }

        newQuestion();
    });

    parent.on('change' , '.answerType', function(){
        var clicked = $(this);
        $('.answer', clicked.parent() ).fadeOut('normal', function(){
            addAnswer( clicked.val(),  clicked.parent()  );
            $('.answer', clicked.parent() ).slideDown();
        });

    });

    parent.submit(function(e){
        e.preventDefault();
        return false;
    });

    //form validate
    //$()

    //submit button clicked
    $('.createSurveyButton',parent).click(function(){
        deleteCookie('qnum');

        //toggleThinker(true);

        var inputs = $('[type=text], textarea, .answerType', parent);

        clog(inputs);

        //pack the questions
        var questions = {};
        $.each($('[name^="question\\["]').serializeArray(), function() {
            var i = this.name.replace(/question/, '' ).replace(/^\[([0-9]*)\]$/, "$1");
            questions[i] = {};
            questions[i]['question'] = this.value;
        });

        $.each($('[name^="ansType\\["]').serializeArray(), function(i, v) {
            var i = this.name.replace(/ansType/, '' ).replace(/^\[([0-9]*)\]$/, "$1");
            questions[i]['answerType'] =  $( '[name="' + v.name + '"] option:selected').val();
        });

        $.each($('[name^="multiAnswer\\["]').serializeArray(), function(i, v) {
            var i = this.name.replace(/multiAnswer/, '' ).replace(/^\[([0-9]*)\]$/, "$1");
            questions[i]['multiAnswer'] =  $( '[name="' + v.name + '"]').val();
        });

        clog(questions);

        $.ajax({
            'url' : getApp_Dir('libraries/Actions.php'),
            'type' : 'post',
            'dataType' : 'json',
            'data' : {
                'header' : 'createSurvey',
//                'form' : parent.serialize()
                'title' : inputs.filter('[name=title]').val(),
                'questions' : questions
            },
            'success' : function(data, textStatus, jqXHR){

            }
        });

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

        $('.addButton', parent).before('<tr data-question="'+ num +'"><td><div data-question="'+ num +'" class="question none">' +
            '<label>Enter the question <span class="questionNumber">'+num+'</span>.<br>' +
            '<textarea name="question['+num+']" placeholder="Question '+num+'"></textarea></label><br>' +
            'Answer Type: <select name="ansType['+num+']" class="answerType"><option value="single" title="Single answer is given to survey taker">Single Answer</option><option value="multi" title="Multiple choice are given to survey taker">Multi Answer</option><option value="write" title="A short answer is given to survey taker">Write In</option><option value="t/f" title="A true false option is given to survey taker">True/False</option></select>' +
            '<div class="answer none"></div><hr></div></td></tr>');
        $('[data-question='+ num +'] .question').slideDown();
    }

    function addAnswer( type, p ){

        var str = '';
        switch ( type ){
            case 'single':
                break;
            case 'multi' :
                str = '<label>Enter options (seprate with commas)<br><input type="text" name="multiAnswer['+p.attr('data-question')+']" placeholder="Enter Options"/></label>'
                break;
        }
        p.find('.answer').html(str);
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
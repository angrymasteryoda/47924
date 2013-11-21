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
                            if ( data[x] ) {
                                //clearForm(inputs);


//                                if ( isBackend && !isset(data['a']) ) {
//                                    errorBox.append( 'You don\'t have premissions to login<br>' );
//                                    errorBox.slideDown('slow');
//                                }
//                                else{
//                                    createSuccessBanner('Login Successful');
//                                    setTimeout( function(){goTo( goto )}, 250);
//                                }

                            }
                            else {
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

    $('select').on('change' , function(){
        alert( $(this).val() );
        clog(true);
    });

    $('.addQuestion2', parent).click(function(){
        addNewQuestion();
    });

    function newQuestion(){
        var nums = $('.questionNumber').eq(0);
        var current = parseInt( nums.html() );
        ++current;
        nums.html(current)
        $('tr .addQuestion', parent).before('' +
            '<label>Enter the question <span class="questionNumber">'+current+'</span>.<br>' +
            '<textarea></textarea></label><br>' +
            'Answer Type: <select class="answerType"><option value="2">Single Answer</option><option value="1">Multi Answer</option><option value="3">aSingle Answer</option><option value="4">Write In</option></select>');

    }
    function addNewQuestion(){
        var rows = document.getElementById("createSurveyTable").rows.length;
        clog(rows)

        var nums = document.getElementsByClassName("questionNumber");
        var current = 0;
        current = nums[0].innerHTML;
        nums[0].innerHTML = ++current;

        var x = document.getElementById("createSurveyTable").insertRow(rows-2);
        var y = x.insertCell(0);
        y.innerHTML = "<label>Enter the question <span class='questionNumber'>"+current+"</span>.</label><br /><textarea> </textarea>";

        var x1 = document.getElementById("createSurveyTable").insertRow(rows-1);
        var y1 = x1.insertCell(0);
        y1.innerHTML = '<label>Enter the options. (seperate with commas)</label><br /><input class="options" type="text" />';
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
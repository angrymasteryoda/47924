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
/**********************************delete terms****************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.termDelete').submit(function(event){
        event.preventDefault();
        var clicked = $(this);
        var proceed = confirm('Are you sure you want to delete the term "' + clicked.parent('p').find('span').html() + '"');

        if(proceed){
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'termDelete',
                    'termId' : $(this).attr('termId')
                },
                'success' : function(data, x, s){
                    if( !data['errors'] ){
                        createSuccessBanner('Term has been deleted successfully!');
                        clicked.parent('p').slideUp(550, function(){
                            $(this).remove();
                        });
                    }
                    else{
                        createSuccessBanner('Term can\'t be deleted try again later', data['info']);
                    }
                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/************************************add terms*****************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.addTermForm').submit(function(event){
        event.preventDefault();
        var p = $('.addTermForm');
        var term = p.find('[name=term]').val();
        var meaning = p.find('[name=meaning]').val();

        var input = [ ( (checkRegex( term, 'custom',  /^(.){1,50}/g) == null) ? (false) : (true) ), ( ((checkRegex( meaning, 'custom',  /^(.){1,50}/g) == null) ? (false) : (true) ) )];
        var proceed = false;
        if( input[0] ){
            p.find('[name=term]').removeClass('errorInput');

        }
        else{
            p.find('[name=term]').addClass('errorInput');
        }
        if ( input[1] ) {
            p.find('[name=meaning]').removeClass('errorInput');
        }
        else {
            p.find('[name=meaning]').addClass('errorInput');
        }

        if( input[0] && input[1]){
            proceed=true;
        }


        //TODO do checks
        //console.log(true);

        if(proceed){
            console.log(p.find('[name=term]'));
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'addTerm',
                    'term' : p.find('[name=term]').val(),
                    'meaning' : p.find('[name=meaning]').val(),
                    'section' : p.find('[name=section]').val()
                },
                'success' : function(data, x, s){
                    if( !data['errors'] ){
                        createSuccessBanner('Term has been added successfully!');
                        p.find('[name=term]').val('');
                        p.find('[name=meaning]').val('');
                        var str = '<p><span class="bold" style="text-transform:capitalize;">' + data['term'] + '</span>: ' + data['meaning'] + '<br>';
                        str += 'Section: ' + data['section'] + '<br>';
                        str += '<span class="termDelete">Can\'t delete refresh page first</span>';
                        $('.terms').append(str);
                    }
                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/*********************************Calendar*********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.day').on('mouseenter', function(){
        if ( $(this).hasClass('event') ) {
            $(this).css('backgroundColor', 'rgba(163, 3, 3, 0.89)');
        }
        else{
            $(this).css('backgroundColor', '#353434');
        }
    });

    $('.day').on('mouseleave', function(){
        if ( $(this).hasClass('event') ) {
            $(this).css('backgroundColor', 'rgba(128, 1, 1, 0.89)');
        }
        else {
            $(this).css('backgroundColor', '#201F1F');
        }
    });

    var changeTooltipPosition = function(event) {
        var tooltipX = event.pageX - 40;
        var tooltipY = event.pageY + 8;
        $('div.tooltip').css({top: tooltipY, left: tooltipX});
    };

    var showTooltip = function(event) {
        var id = $(this).attr('eventId');
        $('div.tooltip').remove();
        $('body').append('<div class="tooltip">' +
            'Event: ' + EVENTS[id]['name'] + '<br>' +
            'Start time: ' + getHumanTime( EVENTS[id]['start_time'] ) + '<br>' +
            'End time: ' + getHumanTime( EVENTS[id]['end_time'] ) + '<br>' +
//            'Double click for info' +
            '</div>');
        changeTooltipPosition(event);
    };

    var hideTooltip = function() {
        $('div.tooltip').remove();
    };

    $('.day.event').bind({
        mousemove : changeTooltipPosition,
        mouseenter : showTooltip,
        mouseleave: hideTooltip
    });

    $('.day.event').on({
        'click' : function(){
            goTo( getApp_Dir('templates/event.php?id=' + $(this).attr('eventId')) );

        }
    })
});
//</editor-fold>
/**************************************************************************/
/********************************Login Form********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.loginForm input').on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType, $('.loginForm') ) ){
                if( $(this).hasClass('errorInput') ){
                    $(this).removeClass('errorInput');
                }
            }
            else{
                $(this).addClass('errorInput');
            }
        }
    });

    $('.loginForm').submit(function(event){
        event.preventDefault();
        var inputs = $('[type=text], [type=password]', '.loginForm');
        var hasError = false;
        var errorBox = $('.loginForm #errors');
        errorBox.html('');

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
                    console.log(typeof data);
                    var e = false;
                    for(var x in data){
                        if(x != 'login' ){
                            if(data[x] == false ){
                                errorBox.append( x +' ' + regex[x]['error'] + '<br/>');
                                e = true;
                            }
                        }
                        else {
                            if( !data[x]){
                                errorBox.append( 'Username or Password was wrong<br/>');
                                e = true;
                            }

                        }
                    }
                    if (e) {
                        errorBox.slideDown('slow');
                    }
                    else{
                        goTo(getApp_Dir('books/'))
                    }
                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/******************************Contact Form********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('input, textarea', '.contactForm').on({
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

    $('[name=phone]', '.contactForm').blur(function() {
        var returned = checkRegex( $(this).val(), $(this).attr('data-type') );
        if( returned ){
            clog( returned );
            var str = ( (returned[2]=='') ? ('1') : (returned[2]) ) + ' (' + returned[3] + ') ' + returned[4] + '-' + returned[5];
            $(this).val(str);
        }
    });

    $('.contactForm').submit(function(event){
        event.preventDefault();
        var inputs = $('[type=text], [type=password], textarea', '.contactForm');
        var hasError = false;
        var errorBox = $('.contactForm #errors');
        errorBox.html('');

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
                    errorBox.append( input.attr('placeholder') +' '+ 'has to match ' + input.attr('data-type').split( 'conf' )[1].toLowerCase() + '<br/>');
                }
                else{
                    errorBox.append( input.attr('placeholder') +' '+  getRegex( input.attr('data-type') )['error'] + '<br/>')
                }
            }
        }
        if( hasError ){
            errorBox.slideDown('slow');
        }
        else{
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'contact',
                    'name' : inputs.filter('[name=username]').val(),
                    'email' : inputs.filter('[name=email]').val(),
                    'phone' : inputs.filter('[name=phone]').val(),
                    'extension' : inputs.filter('[name=extension]').val(),
                    'message' : inputs.filter('[name=message]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    console.log(typeof data);
                    var e = false;
                    for(var x in data){
                        if(data[x] == false ){
                            errorBox.append( x +' ' + regex[x]['error'] + '<br/>');
                            e = true;
                        }
                    }
                    if (e) {
                        errorBox.slideDown('slow');
                    }
                    else{
                        createSuccessBanner('Sent your message successfully.<br>We will get back to you as soon as possible.');
                        clearForm(inputs);
                    }
                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/***************************Add admin users********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.addUserForm input').on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType, $('.addUserForm') ) ){
                if( $(this).hasClass('errorInput') ){
                    $(this).removeClass('errorInput');
                }
            }
            else{
                $(this).addClass('errorInput');
            }
        }
    });

    $('.addUserForm').submit(function(event){
    event.preventDefault();
        var inputs = $('[type=text], [type=password]', '.addUserForm');
        var hasError = false;
        var errorBox = $('.addUserForm #errors');
        errorBox.html('');

        for(var i = 0; i < inputs.length; i++){
            var input = inputs.eq(i);
            if( checkRegex( input.val(), input.attr('data-type'), $('.addUserForm') ) ){
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
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'addUser',
                    'username' : inputs.filter('[name=username]').val(),
                    'password' : inputs.filter('[name=password]').val(),
                    'email' : inputs.filter('[name=email]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    console.log(typeof data);
                    var e = false;
                    for(var x in data){
                        if(x != 'login' ){
                            if(data[x] == false ){
                                errorBox.append( x +' ' + regex[x]['error'] + '<br/>');
                                e = true;
                            }
                        }
                        else {
                            if( !data[x]){
                                errorBox.append( 'Username already exists<br/>');
                                inputs.filter('[name=username]').addClass('errorInput');
                                e = true;
                            }

                        }
                    }
                    if (e) {
                        errorBox.slideDown('slow');
                    }
                    else{
                        createSuccessBanner('User added successfully');
                        $('.users').append('<tr class="data user">' +
                            '<td>' + inputs.filter('[name=username]').val() + '</td>' +
                            '<td>' + inputs.filter('[name=email]').val() + '</td>'+
                            '<td>None</td>'+
                            '<td>None</td>'+
                            '<td>None</td>'+
                            '<td></td>'+
                            '</tr>'
                        );
//                        $('.users').append('<div class="user">'+
//                            '<div class="floatleft width20" >' +  inputs.filter('[name=username]').val() + '</div>'+
//                            '<div class="floatleft width25" >' +  inputs.filter('[name=email]').val() + '</div>'+
//                            '<div class="floatleft width10" >None</div>'+
//                            '<div class="floatleft width15" >None</div>'+
//                            '<div class="floatleft width15" >None</div>'+
//                            '<div class="floatleft width5" ></div>'+
//                            '<div class="clear"></div>'+
//                            '</div>');
                        for(var i = 0; i < inputs.length; i++){
                            inputs.eq(i).val('');
                        }

                    }
                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/**********************************delete users****************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.userDelete').on({
        'click' : function(){
            var clicked = $(this);
            var proceed = confirm('Are you sure you want to delete the user "' + clicked.attr('data-name') + '"');

            if(proceed){
                $.ajax({
                    'url' : getApp_Dir('libraries/Actions.php'),
                    'type' : 'post',
                    'dataType' : 'json',
                    'data' : {
                        'header' : 'userDelete',
                        'userId' : $(this).attr('userId')
                    },
                    'success' : function(data, x, s){
                        if( !data['errors'] ){
                            createSuccessBanner('User has been deleted successfully!');
                            clicked.parent().fadeOut(550, function(){
                                $(this).remove();
                            });
                        }
                        else{
                            createSuccessBanner('User can\'t be deleted try again later', data['info']);
                        }
                    }
                });
            }
        }
    });
});
//</editor-fold>
/**************************************************************************/
/******************************Add Events**********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parent = $('.addEventForm');
    $('input, textarea', parent).on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType, parent ) ){
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
        var inputs = $('[type=text], [type=password], [type=date], [type=time], textarea', parent);
        var hasError = false;
        var errorBox = $('.addEventForm #errors');
        errorBox.html('');

        for(var i = 0; i < inputs.length; i++){
            var input = inputs.eq(i);
            if( checkRegex( input.val(), input.attr('data-type'), parent ) ){
                clog(input.val());
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
                    errorBox.append( input.attr('placeholder') +' '+  getRegex( input.attr('data-type') )['error'] + '<br/>')
                }
            }
        }
        if( hasError ){
            errorBox.slideDown('slow');
        }
        else{
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'addEvent',
                    'name' : inputs.filter('[name=name]').val(),
                    'description' : inputs.filter('[name=description]').val(),
                    'date' : inputs.filter('[name=date]').val(),
                    'endTime' : inputs.filter('[name=endTime]').val(),
                    'startTime' : inputs.filter('[name=startTime]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    for(var x in data){
                        if(x != 'login' ){
                            if(data[x] == false ){
                                errorBox.append( x +' ' + regex[x]['error'] + '<br/>');
                                e = true;
                            }
                        }
                        else {
                            if( !data[x]){
                                errorBox.append( 'Username already exists<br/>');
                                inputs.filter('[name=username]').addClass('errorInput');
                                e = true;
                            }

                        }
                    }
                    if (e) {
                        errorBox.slideDown('slow');
                    }
                    else{
                        $('.events').append('<tr class="data event">' +
                            '<td>' + inputs.filter('[name=name]').val() + '</td>' +
                            '<td>' +  inputs.filter('[name=description]').val() + '</td>'+
                            '<td>' +  inputs.filter('[name=date]').val() + '</td>'+
                            '<td>' +  inputs.filter('[name=startTime]').val() + '</td>'+
                            '<td>' +  inputs.filter('[name=endTime]').val() + '</td>'+
                            '<td></td>'+
                            '</tr>'
                        );
                        createSuccessBanner('Event added successfully');
                        for(var i = 0; i < inputs.length; i++){
                            inputs.eq(i).val('');
                        }

                    }
                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/*********************************delete events****************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.eventDelete').on({
        'click' : function(){
            var clicked = $(this);
            var proceed = confirm('Are you sure you want to delete the event "' + clicked.attr('data-name') + '"');

            if(proceed){
                $.ajax({
                    'url' : getApp_Dir('libraries/Actions.php'),
                    'type' : 'post',
                    'dataType' : 'json',
                    'data' : {
                        'header' : 'eventDelete',
                        'eventId' : $(this).attr('eventId')
                    },
                    'success' : function(data, x, s){
                        if( !data['errors'] ){
                            createSuccessBanner('Event has been deleted successfully!');
                            clicked.parent().fadeOut(550, function(){
                                $(this).remove();
                            });
                        }
                        else{
                            createSuccessBanner('Event can\'t be deleted try again later', data['info']);
                        }
                    }
                });
            }
        }
    });
});
//</editor-fold>
/**************************************************************************/
/******************************view messages*******************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parentTable = $('.messages');
    $('.message', parentTable).click(function(){
        var id = $(this).attr('messageId');
        parentTable.find('.fullMessage[messageid=' + id + ']').slideToggle();
    });


    $('.markRead').on({
        'click' : function(){
            var clicked = $(this);
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'readMessage',
                    'messageId' : $(this).parent().attr('messageid')
                },
                'success' : function(data, x, s){
                    createSuccessBanner('Marked as Read');
                    $('[messageid=' +  clicked.parent().attr('messageid') + ']').filter('tr').removeClass('unread').addClass('read')
                }
            });
        }
    });

    $('.messageDelete').on({
        'click' : function(){
            var clicked = $(this);
            var proceed = confirm('Are you sure you want to delete the message');
            if ( proceed ) {
                $.ajax({
                    'url' : getApp_Dir('libraries/Actions.php'),
                    'type' : 'post',
                    'dataType' : 'json',
                    'data' : {
                        'header' : 'deleteMessage',
                        'messageId' : $(this).parent().attr('messageid')
                    },
                    'success' : function(data, x, s){
                        createSuccessBanner('Message Deleted');
                        $('[messageid=' +  clicked.parent().attr('messageid') + ']').fadeOut(520, function(){
                            $(this).remove();
                        });

                    }
                });
            }
        }
    });
});
//</editor-fold>
/**************************************************************************/
/******************************add student ********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parent = $('.signUpForm');
    $('input, textarea', parent).on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType, parent ) ){
                if( $(this).hasClass('errorInput') ){
                    $(this).removeClass('errorInput');
                }
            }
            else{
                $(this).addClass('errorInput');
            }
        }
    });

    //experience
    $('[name=experience]', parent).click(function(){
        if( $('[name=experience]:checked', parent).val() == 'Yes' ){
            $('.experience').slideDown('slow');
        }
        else if( $('[name=experience]:checked', parent).val() == 'No' ){
            $('.experience').slideUp('slow');
        }

    });

    //Who for
    $('[name=whoFor]', parent).click(function(){
        if( $('[name=whoFor]:checked', parent).val() == 'Child' ){
            $('.whoFor').slideDown('slow');
        }
        else if( $('[name=whoFor]:checked', parent).val() == 'Self' ){
            $('.whoFor').slideUp('slow');
        }

    });

    //phone field formatter
    $('[name=phone], [name=pCell]', parent).blur(function() {
        var returned = checkRegex( $(this).val(), $(this).attr('data-type') );
        if( returned ){
            clog( returned );
            var str = ( (returned[2]=='') ? ('1') : (returned[2]) ) + ' (' + returned[3] + ') ' + returned[4] + '-' + returned[5];
            $(this).val(str);
        }
    });

    parent.submit(function(event){
        event.preventDefault();
        var inputs = $('input, textarea', parent);
        var hasError = false;
        var errorBox = $('#errors', parent);
        errorBox.html('');

        for(var i = 0; i < inputs.length; i++){
            var input = inputs.eq(i);
            if ( input.attr('type') != 'radio' && input.attr('type') != 'checkbox') {
                if( checkRegex( input.val(), input.attr('data-type'), $('.addEventForm') ) ){
                    clog(input.val());
                    if( input.hasClass('errorInput') ){
                        input.removeClass('errorInput');
                    }
                }
                else{
                    if ( checkParentRadio(input, parent) ) {
                        hasError = true;
                        input.addClass('errorInput');
                        if( input.attr('data-type').match(/conf/) ){
                            errorBox.append( input.attr('placeholder') +' '+ 'has to match ' + input.attr('data-type').split( 'conf' )[1].toLowerCase() + '<br/>');
                        }
                        else{
                            errorBox.append( input.attr('placeholder') +' '+  getRegex( input.attr('data-type') )['error'] + '<br/>')
                        }
                    }
                }
            }
        }

        //check radio is selected
        if( typeof inputs.filter('[name=whoFor]:checked').val() == 'undefined'){
            hasError = true;
            errorBox.append( getRegex( inputs.filter('[name=whoFor]').eq(0).attr('data-type') )['error'] + '<br/>');
            inputs.filter('[name=whoFor]').addClass('errorInput');
        }
        else{
            inputs.filter('[name=whoFor]').removeClass('errorInput');
        }
        if( typeof inputs.filter('[name=experience]:checked').val() == 'undefined'){
            hasError = true;
            errorBox.append( getRegex( inputs.filter('[name=experience]').eq(0).attr('data-type') )['error'] + '<br/>');
            inputs.filter('[name=experience]').addClass('errorInput');
        }
        else{
            inputs.filter('[name=experience]').removeClass('errorInput');
        }

        if( hasError ){
            errorBox.slideDown('slow');
        }
        else{
            var whoFor = '';
            var experience = '';
            var pName = '';
            var pCell = '';
            var belt = '';

            if( typeof inputs.filter('[name=whoFor]:checked').val() != 'undefined'){
                whoFor = inputs.filter('[name=whoFor]:checked').val();
                pName = inputs.filter('[name=pName]').val();
                pCell = inputs.filter('[name=pCell]').val();
            }

            if( typeof inputs.filter('[name=experience]:checked').val() != 'undefined'){
                experience = inputs.filter('[name=experience]:checked').val();
                belt = inputs.filter('[name=belt]').val();
            }
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'signUp',
                    'name' : inputs.filter('[name=name]').val(),
                    'email' : inputs.filter('[name=email]').val(),
                    'phone' : inputs.filter('[name=phone]').val(),
                    'extension' : inputs.filter('[name=extension]').val(),
                    'age' : inputs.filter('[name=age]').val(),
                    'whoFor' : whoFor,
                    'pName' : pName,
                    'pCell' : pCell,
                    'experience' : experience,
                    'belt' : belt,
                    'comments' : inputs.filter('[name=comments]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    for(var x in data){
                        if(data[x] == false ){
                            console.log(x);
                            errorBox.append( x +' ' + getRegex(x)['error'] + '<br/>');
                            e = true;
                        }
                    }
                    if (e) {
                        errorBox.slideDown('slow');
                    }
                    else{
                        createSuccessBanner('Signed Up Successfully');
                        clearForm(inputs);
                        fillFormWithHappiness(parent, 'Signed Up Successfully<br/>We will confirm this will shortly')
                    }
                }
            });
        }
    });

});
//</editor-fold>
/**************************************************************************/
/*****************************view students********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parentTable = $('.students');
    $('.student', parentTable).click(function(){
        var id = $(this).attr('studentId');
        parentTable.find('.fullStudent[studentId=' + id + ']').slideToggle();
    });

    $('.markStudentAs', parentTable).on({
        'click' : function(){
            var clicked = $(this);
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'markStudentAs',
                    'studentId' : $(this).parent().attr('studentId'),
                    'markAs' : clicked.attr('data-state')
                },
                'success' : function(data, x, s){
                    createSuccessBanner('Marked as '+ ( (clicked.attr('data-state') == 1) ? ('New') : ('Current') ) +' Student');
                    $('[studentId=' +  clicked.parent().attr('studentId') + ']').filter('tr').removeClass('unread').addClass('read')
                }
            });
        }
    });

    $('.studentDelete', parentTable).on({
        'click' : function(){
            var clicked = $(this);
            var id = $(this).parent().attr('studentId');
            var name = parentTable.find('.student[studentId=' + id + '] td').eq(0).html();
            var proceed = confirm('Are you sure you want to delete "' + name +'" \nyou Can Not undo this they will have to register');
            if ( proceed ) {
                $.ajax({
                    'url' : getApp_Dir('libraries/Actions.php'),
                    'type' : 'post',
                    'dataType' : 'json',
                    'data' : {
                        'header' : 'deleteStudent',
                        'studentId' : $(this).parent().attr('studentId')
                    },
                    'success' : function(data, x, s){
                        createSuccessBanner('Student Deleted');
                        $('[studentId=' +  clicked.parent().attr('studentId') + ']').fadeOut(520, function(){
                            $(this).remove();
                        });

                    }
                });
            }
        }
    });

    $('.studentEdit', parentTable).on({
        'click' : function(){
            var clicked = $(this);
            var student = clicked.parent().attr('studentId');
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'getEditPerm',
                    'editId' : clicked.attr('data-edit'),
                    'page' : location.href,
                    'editingId' : student
                },
                'success' : function(data, x, s){
                    goTo( getApp_Dir( 'books/edit.php?id=' + clicked.attr('data-edit') + '&sid=' + student + '&e=' + data['hash'] ) );

                }
            });
        }
    });
});
//</editor-fold>
/**************************************************************************/
/*****************************edit students********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    var parent = $('.editStudentForm');
    $('input, textarea', parent).on({
        'keyup' : function(){
            var regexType = $(this).attr('data-type');
            if( checkRegex( $(this).val(), regexType, parent ) ){
                if( $(this).hasClass('errorInput') ){
                    $(this).removeClass('errorInput');
                }
            }
            else{
                $(this).addClass('errorInput');
            }
        }
    });

    //experience
    $('[name=experience]', parent).click(function(){
        if( $('[name=experience]:checked', parent).val() == 'Yes' ){
            $('.experience').slideDown('slow');
        }
        else if( $('[name=experience]:checked', parent).val() == 'No' ){
            $('.experience').slideUp('slow');
        }

    });

    //Who for
    $('[name=whoFor]', parent).click(function(){
        if( $('[name=whoFor]:checked', parent).val() == 'Child' ){
            $('.whoFor').slideDown('slow');
        }
        else if( $('[name=whoFor]:checked', parent).val() == 'Self' ){
            $('.whoFor').slideUp('slow');
        }

    });

    //phone field formatter
    $('[name=phone], [name=pCell]', parent).blur(function() {
        var returned = checkRegex( $(this).val(), $(this).attr('data-type') );
        if( returned ){
            clog( returned );
            var str = ( (returned[2]=='') ? ('1') : (returned[2]) ) + ' (' + returned[3] + ') ' + returned[4] + '-' + returned[5];
            $(this).val(str);
        }
    });

    parent.submit(function(event){
        event.preventDefault();
        var inputs = $('input, textarea', parent);
        var hasError = false;
        var errorBox = $('#errors', parent);
        errorBox.html('');

        for(var i = 0; i < inputs.length; i++){
            var input = inputs.eq(i);
            if ( input.attr('type') != 'radio' && input.attr('type') != 'checkbox') {
                if( checkRegex( input.val(), input.attr('data-type'), $('.addEventForm') ) ){
                    clog(input.val());
                    if( input.hasClass('errorInput') ){
                        input.removeClass('errorInput');
                    }
                }
                else{
                    if ( checkParentRadio(input, parent) ) {
                        hasError = true;
                        input.addClass('errorInput');
                        if( input.attr('data-type').match(/conf/) ){
                            errorBox.append( input.attr('placeholder') +' '+ 'has to match ' + input.attr('data-type').split( 'conf' )[1].toLowerCase() + '<br/>');
                        }
                        else{
                            errorBox.append( input.attr('placeholder') +' '+  getRegex( input.attr('data-type') )['error'] + '<br/>')
                        }
                    }
                }
            }
        }

        //check radio is selected
        if( typeof inputs.filter('[name=whoFor]:checked').val() == 'undefined'){
            hasError = true;
            errorBox.append( getRegex( inputs.filter('[name=whoFor]').eq(0).attr('data-type') )['error'] + '<br/>');
            inputs.filter('[name=whoFor]').addClass('errorInput');
        }
        else{
            inputs.filter('[name=whoFor]').removeClass('errorInput');
        }
        if( typeof inputs.filter('[name=experience]:checked').val() == 'undefined'){
            hasError = true;
            errorBox.append( getRegex( inputs.filter('[name=experience]').eq(0).attr('data-type') )['error'] + '<br/>');
            inputs.filter('[name=experience]').addClass('errorInput');
        }
        else{
            inputs.filter('[name=experience]').removeClass('errorInput');
        }

        if( hasError ){
            errorBox.slideDown('slow');
        }
        else{
            var whoFor = '';
            var experience = '';
            var pName = '';
            var pCell = '';
            var belt = '';

            if( typeof inputs.filter('[name=whoFor]:checked').val() != 'undefined'){
                whoFor = inputs.filter('[name=whoFor]:checked').val();
                pName = inputs.filter('[name=pName]').val();
                pCell = inputs.filter('[name=pCell]').val();
            }

            if( typeof inputs.filter('[name=experience]:checked').val() != 'undefined'){
                experience = inputs.filter('[name=experience]:checked').val();
                belt = inputs.filter('[name=belt]').val();
            }
            $.ajax({
                'url' : getApp_Dir('libraries/Actions.php'),
                'type' : 'post',
                'dataType' : 'json',
                'data' : {
                    'header' : 'editStudent',
                    'id' : $_GET(location.href)['sid'],
                    'name' : inputs.filter('[name=name]').val(),
                    'email' : inputs.filter('[name=email]').val(),
                    'phone' : inputs.filter('[name=phone]').val(),
                    'extension' : inputs.filter('[name=extension]').val(),
                    'age' : inputs.filter('[name=age]').val(),
                    'whoFor' : whoFor,
                    'pName' : pName,
                    'pCell' : pCell,
                    'experience' : experience,
                    'belt' : belt,
                    'comments' : inputs.filter('[name=comments]').val()
                },
                'success' : function(data, textStatus, jqXHR){
                    var e = false;
                    for(var x in data){
                        if(data[x] == false ){
                            console.log(x);
                            errorBox.append( x +' ' + getRegex(x)['error'] + '<br/>');
                            e = true;
                        }
                    }
                    if (e) {
                        errorBox.slideDown('slow');
                    }
                    else{
                        createSuccessBanner('Student Edited Successfully');
                        clearForm(inputs);
                        fillFormWithHappiness(parent, 'Student Edited Successfully<br><a href="'+ getApp_Dir('books/viewStudents.php') + '">Back to Student Page</a>')
                    }
                }
            });
        }
    });

    $('.cancel', parent).click(function(){
        window.history.back()
    });
});
//</editor-fold>
/**************************************************************************/
/***************************** Term links ********************************/
/**************************************************************************/
//<editor-fold defaultstate="collapsed">
$(document).ready(function(){
    $('.termLinks a').on({
        'click' : function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            console.log(id);
            $('.activeTerms').fadeOut('fast', function(){
                $(this).removeClass('activeTerms');


                clog($('[id="' + id + '"]'));
                $('[id="' + id + '"]').addClass('activeTerms').fadeIn();
            });
//            $('.termDefs:not(.none)').fadeOut('fast', function(){
//                $('.termDefs:not(.none)').removeClass('none');
//                $('#' + id).fadeIn('fast');
//            });
        }
    });
});
//</editor-fold>

/**************************************************************************/
/*********************************Fader slide******************************/
/**************************************************************************/

$(document).ready(function(){
    var parent = $('#fader');
    var slideDur = 8000;
    var ss = $('.slides', parent);
    var slide = $('.slide', ss).first();

    if ( parent.length > 0  ) {
        var slideShow = setInterval( function(){
            slide = fadeNext( slide );
        }, slideDur);


        function fadeNext( slide, backwards ){
            var next = ( (slide.next().length > 0 ) ? (slide.next()) : (slide.parent().children().first()) );
            var index = next.index();

            if ( backwards ) {
                index-=2;
                next = $('.slides .slide').eq( index );
            }

            console.log(next);

            slide.css({
                'z-index' : 2
            });

            next.css({
                'display' : 'none',
                'left' : '0px',
                'z-index' : 1
            });

            slide.fadeOut( 50, function(){
                $( this ).removeAttr( "style" ).removeAttr( "curSlide" ).css( {
                    "left" : "100%",
                    'display' : 'none'
                } );
            });

            next.fadeIn( 1250 );

            next.attr('curSlide', index);

            return next;
        }

        $('.rightArrow').click(function(){
            if ( $('.slide[curSlide]:animated').length >= 1 ) {
                return false
            }
            slide = fadeNext( slide );
            clearInterval( slideShow );
            var timeout = setTimeout( function(){
                slideShow = setInterval( function(){
                    slide = fadeNext( slide );
                }, slideDur);
                clearTimeout(timeout);
            }, slideDur-3000 );
        });

        $('.leftArrow').click(function(){
            if ( $('.slide[curSlide]:animated').length >= 1 ) {
                return false
            }
            slide = fadeNext( slide, true );
            clearInterval( slideShow );
            var timeout = setTimeout( function(){
                slideShow = setInterval( function(){
                    slide = fadeNext( slide );
                }, slideDur);
                (timeout);
            }, slideDur-3000 );
        });
    }
});

$( ".mobile_slideshow" ).each( function() {
    var SS = $( "ul", this );
    function fade_next( slide ) { // possibly move this outside the loop... might need the seperated instances anyway though...
        // slide = the <li>
        var next = $( slide ).next().length > 0 ? $( slide ).next() : $( slide ).parent().children().first();
        var idx = next.index();

        $( slide ).css( {
            "z-index" : "2"
        } );
        next.css( {
            "display" : "none",
            "left" : "0px",
            "z-index" : 1
        } )

        $( slide ).fadeOut( 50, function() {
            $( this ).removeAttr( "style" ).css( {
                "left" : "100%"
            } );
        } );
        next.fadeIn( 1250, function() {
        } );
        var dots = next.parent().next(); // <ul class="ssdots">
        $( ".active", dots ).removeClass( "active" );
        $( "span", $( "li", dots ).eq( idx ) ).addClass( "active" );

        return next;
    } // end fn slide_next

    var slide = $( "li", SS ).first();
    setInterval( function() {
        slide = fade_next( slide );
    }, mobile_ani_wait );
} );


/**************************************************************************/
/*********************************Utilities********************************/
/**************************************************************************/

//make the sortable buttons print there location also
$(document).ready(function(){
    if( $('.sortable, .pagesLinks .pageNum').length > 0){
        var elements = $('.sortable, .pagesLinks .pageNum');

        for(var i = 0; i < elements.length; i++){
            var pos = elements.eq(i).position();
            var x = elements.eq(i).attr('href');
            x = x.replace( /\&pos=\d+/, '');
            elements.eq(i).attr('href', (x + '&pos=' + pos.top));
        }
    }
    var get = $_GET(location.href);
    try {
        if (get['pos']) {
            $("html, body").animate({ scrollTop: get['pos'] }, 0);
        }
    } catch (e) {
    }
});

$(document).ready(function(){
    if ( $('.countDown').length > 0 ) {
        var counter = $('.countDown');
        var count = parseInt( counter.html() );

        setInterval(function(){
            console.log(count);
            if ( count > 0 ) {
                counter.html(--count);
            }
            else if( count == 0){
                goTo( getApp_Dir('templates') );
            }
        }, 1000);
    }
});


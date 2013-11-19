var APP_URL = '';
$(document).ready(function(){
    APP_URL = $('meta[name="url"]').attr('content');
});

function clearForm(inputs){
    for ( var  i = 0; i < inputs.length; i++ ) {
        inputs.eq(i).val('');
    }
}

function clog(array){
    console.log(array);
}

function createSuccessBanner(str, error, time){
    var className = 'greenHat';
    var addTo = '';
    if(!time){
        time = 5000;
    }
    console.log(typeof error);
    if(typeof error != 'undefined'){
        className = 'redHat';
        addTo = '<br>Debug Info:  ' + error;
        time = 10000;
    }

    $('body').append('<div style="display: none;" class="successBanner"><div class="'+className+'"></div>' + str + addTo + '</div>');
    $('.successBanner').slideDown(550, function(){
        setTimeout("$('.successBanner').slideUp(550, function(){$('.successBanner').remove();})", time);
    });
}

function fillFormWithHappiness(parent, message){
    if(!message){
        var message = 'Form Sent Successfully<br/>';
    }
    parent.fadeOut('slow', function(){
        parent.empty();
        var str =  ''+
            '<div id="actionFinished" class="aligncenter">'+
            '<img class="width25" src="' + getApp_Dir('assets/img/green.png') +'" />'+
            '<br/>'+
            message +
        '</div>';
        parent.append(str);
        parent.fadeIn('slow');
    });
}

function checkRegex(str, type, formClass, pattern) {
    if(typeof type == 'undefined'){/*clog('not testing ' + type);*/return [];}//hacky way to bypass if no test required
    if ( type.match(/conf/) ) {
        var parent = type.split( 'conf' )[1].toLowerCase();

        if( $('[data-type=' + parent + ']', formClass).val() != ''){
            if ( $('[data-type=' + parent + ']', formClass).val() != str ) {
                return null;
            }
        }

        type = parent;
    }
    if(type == 'custom'){
        return str.match(pattern);
    }
    else{
        return str.match( getRegex(type).regex );
    }
}

function getApp_Dir(path){
    if(path){
        return APP_URL + path;
    }
    else{
        return APP_URL;
    }
}

function getHumanTime(time){
    timeSplit = time.split(':');
    if(timeSplit[0] > 12){
        return (timeSplit[0]%12) + ':' + timeSplit[1] + 'Pm';
    }
    else{
        return timeSplit[0] + ':' + timeSplit[1] + 'Am';
    }
}

function goTo(url) {
    location.href = url;
}

/**
 * @return true if parent is not checked meaning check
 *
 */
function checkParentRadio(input, parent){
    var parentInput = $('[name=' + input.attr('data-parent') + ']', parent);
    var passIndex;//index of the passing input
    for ( var  i = 0; i < parentInput.length; i++ ) {
        if ( typeof parentInput.eq(i).attr('data-pass') != 'undefined' ) {
            passIndex = i;
        }
        else{
            clog('No data-pass attr');
            return true;
            //TODO what if no data-pass attr dev error but still want to catch
        }
    }

    return !parentInput.eq(passIndex).prop('checked');
}

function adminHeartbeat(){
    setInterval(function(){
        $.ajax({
            'url' : getApp_Dir('books/'),
            'type' : 'get'
        });
    }, 1000 * (8*60))
}

function $_GET(url) {
    var info = url.split("?");
    var nameValuePair = info[1].split("&");

    var $_GET = new Object();
    for (var i = 0; i < nameValuePair.length; i++) {
        var map = nameValuePair[i].split("=");
        var name = map[0];
        var value = map[1];

        $_GET[name] = value;
    }
    return $_GET;
}

function getRegex(type){
    if ( type.match(/length-/) ) {
        var len = type.split( '-' )[1];
        return {'regex' : new RegExp('\(.){' + len + '}'), 'error' : 'has to be ' + len + ' long'};
    }
    else {
        return regex[type];
    }
}

var regex = {
    'username' : {
        'regex' : /^[a-zA-Z]{2,50}$/g,
        'error' : 'has to be 2-50 letters only'
    },
    'password' : {
        'regex' : /^[0-9a-zA-Z]{6,50}$/g,
        'error' : 'has to be 6-50 long alphanumerical'
    },
    'email' : {
        'regex' : /^[a-zA-Z0-9-\.]{1,}@([a-zA-Z\.])?[a-zA-Z]{1,}\.[a-zA-Z]{1,4}$/gi,
        'error' : 'has to be valid email'
    },
    'complex-password' : {
        'regex' : /^(?!.*(.)\1{3})((?=.*[\d])(?=.*[a-z])(?=.*[A-Z])|(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s])|(?=.*[\d])(?=.*[A-Z])(?=.*[^\w\d\s])|(?=.*[\d])(?=.*[a-z])(?=.*[^\w\d\s])).{7,30}$/g,
        'error' : 'has to be 7-30 must contain capital letter, and number or symbol'
    },
    'date' : {
        'regex' : /^(\d{4})\-(\d{2})\-(\d{2})$/g,
        'error' : 'has to be a valid date'
    },
    'time' : {
        'regex' : /^(\d{2})\:(\d{2})$/g,
        'error' : 'has to be a valid time'
    },
    'usphone' : {
        'regex' :  /^(\+?(\d?)[-\s.]?\(?(\d{3})\)?[-\s.]?(\d{3})[-\s.]?(\d{4})){1}$/,
        'error' : 'has to be a valid North American phone number'
    },
    'extension' : {
        'regex' :  /^(\d+)*$/,
        'error' : 'has to be a number'
    },
    'numbers' : {
        'regex' : /^\d+$/,
        'error' : 'has to be a number'
    },
    'name' : {
        'regex' : /^[A-Za-z ]{2,50}$/g,
        'error' : 'has to be 2-50 letters long'
    },
    'whoFor' : {
        'error' : 'must check who the lessons are for'
    },
    'experience' : {
        'error' : 'must check if you have experience'
    },
    'pName' : {
        'regex' : /^[A-Za-z ]{2,50}$/g,
        'error' : 'has to be 2-50 letters long'
    },
    'pCell' : {
        'regex' :  /^(\+?(\d?)[-\s.]?\(?(\d{3})\)?[-\s.]?(\d{3})[-\s.]?(\d{4})){1}$/,
        'error' : 'has to be a valid North American phone number'
    },
    'belt' : {
        'regex' : /^[a-zA-Z]{2,15}$/g,
        'error' : 'has to be 2-15 letters only'
    }

};
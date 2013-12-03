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
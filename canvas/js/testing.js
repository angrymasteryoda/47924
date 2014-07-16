
var CANVAS = $('#canvas').get(0);

var SCREEN_WIDTH = CANVAS.width;
var SCREEN_HEIGHT = CANVAS.height;

var mouseX = ( window.innerWidth - SCREEN_WIDTH);
var mouseY = ( window.innerHeight - SCREEN_HEIGHT);

var contxt;
var images;

function init(){

    if ( CANVAS && CANVAS.getContext ) {
        contxt = CANVAS.getContext( '2d' );

        $( CANVAS ).mousemove( mouseMoveEvent );
    }
    loadImages( createImages(), imageLoaded );

    setInterval( loop, 1000 / 60 );
}

function draw(){
    for ( var i = 0; i < images.length; i++ ) {
        contxt.drawImage( images[i], ( i * 100), ( i * 50 ) );
    }
}

function loadImages(imgs, callBack){
    var asyncCount = 0;
    images = new Array();
    for ( i in imgs){
        images[i] = new Image();
        images[i].src = imgs[i];
        images[i].onload = function(){
            asyncCount++;
            if ( asyncCount == imgs.length ) {
                callBack( images );
            }
        };
    }
}

function imageLoaded(img){
    for ( i in images ){
        contxt.drawImage( img[i], (i*100) , (i*5) );
    }
}

function createImages() {
    var src = [ "http://distilleryimage10.s3.amazonaws.com/ff4c6bbe853311e39c500ee695acc826_8.jpg", "http://distilleryimage10.s3.amazonaws.com/c6a05fac853b11e398f612e173ceb082_8.jpg", "http://distilleryimage7.s3.amazonaws.com/27b454d4853b11e3a5b90e97db96c68f_8.jpg", "http://distilleryimage0.s3.amazonaws.com/121add0a853b11e3be80129f677308a4_8.jpg", "http://distilleryimage3.s3.amazonaws.com/26ad5122853a11e3aa520e5d6fd881bf_8.jpg" ];
    return src;
}


function mouseMoveEvent( e ){
    var rect = CANVAS.getBoundingClientRect();
    mouseX =  e.clientX - rect.left;
    mouseY =  e.clientY - rect.top;
}

function loop(){


}


function clog(){
    var args = Array.prototype.slice.call(arguments);
    console.log( arguments );
}

$(document).ready(function(){
    init();
});
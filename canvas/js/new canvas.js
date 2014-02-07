var canvas,
    ctx,
    W,
    H,
    mouseX,
    mouseY,
    PAUSE;

function init() {
    canvas = $("#canvas").get(0);
    ctx = canvas.getContext('2d');

    W = window.innerWidth, H = window.innerHeight;
    canvas.width = W;
    canvas.height = H;

    $( canvas ).mousemove( mouseMoveEvent );

    setInterval(draw, 1000 / 30);
}

function draw() {
    drawDebug();
}

function drawDebug( fields, sx, sy, width, height ){
    sx = sx | 10;
    sy = sy | 10;
    width = width | 150;
    var len = 1;
    for( x in fields ){
        len++;
    }

    height = height | 24 + ( 16 * ( len -1 ) );
    ctx.globalCompositeOperation = 'source-over';
    ctx.fillStyle = "rgba( 30, 30, 30, .5)"
    ctx.fillRect( sx, sy, width, height);
    ctx.font = '11pt Arial';
    ctx.fillStyle = 'white';
    ctx.fillText("Debug", 12, 24);


    var i = 1;
    for( x in fields ){
        ctx.fillText( x + ': ' + fields[x], 12, 24 + ( 16 * i ));
        i++;
    }
}

function mouseMoveEvent( e ){
    var rect = canvas.getBoundingClientRect();
    mouseX =  e.clientX - rect.left;
    mouseY =  e.clientY - rect.top;
}

function pause(){
    PAUSE = true
}

function unpause(){
    PAUSE = false
}

$(document).ready(function () {
    init();
});
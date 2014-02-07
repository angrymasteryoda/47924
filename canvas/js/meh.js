
var CANVAS = $('#canvas').get(0);

//radius around the mouse
var MIN_RADIUS = 20;
var RADIUS = 100;

var SCREEN_WIDTH = CANVAS.width;
var SCREEN_HEIGHT = CANVAS.height;
var NUM_PARTICLES = 500;

var CLOCKWISE = false;
var COUNTER_CLOCKWISE = false;
var BOTH = true;

var JAGGED_ORBIT = false;
var RANDOM_ORBIT = true;
var STANDARD_ORBIT = false;

var particles;
var mouseX = ( window.innerWidth - SCREEN_WIDTH);
var mouseY = ( window.innerHeight - SCREEN_HEIGHT);
var contxt;

function init(){
    resizeEvent();

    if ( CANVAS && CANVAS.getContext ) {
        contxt = CANVAS.getContext( '2d' );

        $( CANVAS ).mousemove( mouseMoveEvent );
        $( window ).resize( resizeEvent );

    }

//    var bg = new Image();
//    bg.onload = function(){
//        contxt.drawImage( bg, 0, 0)
//    }
//    bg.src = "http://bigbackground.com/wp-content/uploads/2013/07/space-wallpapers-1600x900.jpg";

    createParticles();
    clog(particles);

    setInterval( loop, 1000 / 60 );
}

function resizeEvent(){
    CANVAS.height = ( $(window).height() );
    CANVAS.width = ( $(window).width() );

    SCREEN_WIDTH = CANVAS.width;
    SCREEN_HEIGHT = CANVAS.height;
}

function createParticles(){
    particles = [];

    for ( var  i = 0; i < NUM_PARTICLES; i++ ) {
        var x = ( Math.random() * SCREEN_WIDTH );
        var y = ( Math.random() * SCREEN_HEIGHT );

        var r = parseInt( Math.random() * 25 + 10 ).toString(16);
        var g = parseInt( Math.random() * 10 ).toString(16);
        var b = parseInt( Math.random() * 0 ).toString(16);

        var bw = parseInt( Math.random() * 255 | 0).toString(16);

        var particle = {
            position: {
                x: x,
                y: y
            },
            shift: {
                x: x,
                y: y
            },
            size: 2,
            angle: 0,
            speed: 1 + Math.random() * 3,
            targetSize: 1,
//            color: '#' + bw + bw + bw,
            color: '#' + r + g + b,
            orbit: Math.random() * RADIUS + MIN_RADIUS,
            targetOrbit: Math.random() * RADIUS + MIN_RADIUS
        };
        console.log('#' + bw + bw + bw);

        particles.push( particle );

    }
}

function mouseMoveEvent( e ){
    var rect = CANVAS.getBoundingClientRect();
    mouseX =  e.clientX - rect.left;
    mouseY =  e.clientY - rect.top;
}


function fadeCanvas(canvas, alpha, mode) {
    mode = mode || 'destination-out';
    ctx = canvas.getContext('2d');
    ctx.globalCompositeOperation = mode;
    ctx.fillStyle = "rgba(255, 255, 255, " + alpha + ")";
    ctx.beginPath();
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fill();
    ctx.globalCompositeOperation = 'source-over';
}

function loop(){


    contxt.fillStyle = 'rgba(0,0,0,0.05)';
    contxt.fillRect(0, 0, contxt.canvas.width, contxt.canvas.height);



    for ( var i = 0; i < particles.length; i++ ) {
        var particle = particles[i];

        var lp = {
            x: particle.position.x,
            y: particle.position.y
        };

        particle.position.x+= 1 * (particle.speed);
        particle.position.y+= 1 * (particle.speed);
        

        var draw = true;
        //if collided
        if ( Math.max( Math.min( particle.position.x, SCREEN_WIDTH ) ) == SCREEN_WIDTH ) {
            particle.position.x = 0;
            draw = false;
//            particles.splice( i, 1 );
        }

        if ( Math.max( Math.min( particle.position.y, SCREEN_HEIGHT ) ) == SCREEN_HEIGHT ) {
            particle.position.y = 0;
            draw = false;
//            particles.splice( i, 1 );
        }


        if ( draw ) {
            contxt.beginPath();
            contxt.fillStyle = particle.color;
            contxt.strokeStyle = particle.color;
            contxt.lineWidth = particle.size;
            contxt.moveTo( lp.x , lp.y );
            contxt.lineTo( particle.position.x, particle.position.y );
            contxt.stroke();
            contxt.arc( particle.position.x, particle.position.y, particle.size / 2, 0, Math.PI * 2, true );
            contxt.fill();
        }
    }


/*        {
            contxt.beginPath();
            contxt.fillStyle = particle.color;
            contxt.strokeStyle = particle.color;
            contxt.lineWidth = particle.size;
            contxt.moveTo( lp.x, lp.y);
            contxt.lineTo( particle.position.x, particle.position.y );
            contxt.stroke();
            contxt.arc( particle.position.x, particle.position.y, particle.size / 2, 0, Math.PI * 2, true );
            contxt.fill();
        }*/
}

function changeParticles( num ){
    NUM_PARTICLES = num;
    createParticles();
}

function changeRadius( num ){
    RADIUS = num;
    createParticles();
}

function changeMinRadius( num ){
    MIN_RADIUS = num;
    createParticles();
}

function setOrbitType( type ){
    resetOrbit();
    switch (type ){
        case 1:
            JAGGED_ORBIT=true; break;
        case 2:
            RANDOM_ORBIT = true; break;
        default :
            STANDARD_ORBIT = true;
    }
}

function setDirection( type ){
    resetDirection();
    switch ( type ){
        case 1:
            COUNTER_CLOCKWISE = true; break;
        case 2:
            BOTH = true; break;
        default:
            CLOCKWISE = true;
    }
}

function resetDirection(){
    COUNTER_CLOCKWISE = false;
    BOTH = false;
    CLOCKWISE = true;
}

function resetOrbit(){
    JAGGED_ORBIT = false;
    RANDOM_ORBIT = false;
    STANDARD_ORBIT = true;
}

function clog(){
    var args = Array.prototype.slice.call(arguments);
    console.log( arguments );
}

$(document).ready(function(){
    init();
});
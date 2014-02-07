
var CANVAS = $('#canvas').get(0);

//radius around the mouse
var MIN_RADIUS = 25;
var RADIUS = 200;

var SCREEN_WIDTH = CANVAS.width;
var SCREEN_HEIGHT = CANVAS.height;
var NUM_PARTICLES = 45;

var CLOCKWISE = false;
var COUNTER_CLOCKWISE = false;
var BOTH = true;

var JAGGED_ORBIT = true;
var RANDOM_ORBIT = false;
var STANDARD_ORBIT = false;

var SPIROGRAPH = false;

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

        var r = ( Math.random() * 256 );
        var g = ( Math.random() * 256 );
        var b = ( Math.random() * 256 );

        var particle = {
            position: {
                x: x,
                y: y
            },
            shift: {
                x: x,
                y: y
            },
            size: 1,
            angle: 0,
            speed: 0.009 + Math.random() * 0.05,
            targetSize: 1,
            color: '#' + ( Math.random() * 0x010101 + 0xfefefe | 0).toString(16),
            orbit: Math.random() * RADIUS + MIN_RADIUS,
            targetOrbit: Math.random() * RADIUS + MIN_RADIUS
        };

        particles.push( particle );

    }
}

function mouseMoveEvent( e ){
    var rect = CANVAS.getBoundingClientRect();
    mouseX =  e.clientX - rect.left;
    mouseY =  e.clientY - rect.top;
}

function loop(){

    // Fade out the lines slowly by drawing a rectangle over the entire canvas
    contxt.fillStyle = 'rgba(0,0,0,0.05)';
    contxt.fillRect(0, 0, contxt.canvas.width, contxt.canvas.height);
    
    for ( var  i = 0; i < particles.length; i++ ) {
        var particle = particles[i];

        var lp = {
            x: particle.position.x,
            y: particle.position.y
        };

        // Offset the angle to keep the spin going
        if ( BOTH ) {
            if ( i % 2 == 0) {
                particle.angle += particle.speed;
            }
            else{
                particle.angle -= particle.speed;
            }
        }
        else if ( COUNTER_CLOCKWISE ) {
            particle.angle -= particle.speed;
        }
        else{
            particle.angle += particle.speed;
        }

//        particle.angle += particle.speed;

        // Follow mouse with some lag
        particle.shift.x += ( /*750*/mouseX - particle.shift.x) * (particle.speed);
        particle.shift.y += ( /*550*/mouseY - particle.shift.y) * (particle.speed);

        // Apply position
        particle.position.x = particle.shift.x + Math.cos(i + particle.angle) * ( particle.orbit );
        particle.position.y = particle.shift.y + Math.sin(i + particle.angle) * ( particle.orbit );

        //randomly change teh orbit
        if ( JAGGED_ORBIT ) {
            if ( Math.floor( Math.random() * 100) == 0 ){
                particles[i].orbit = Math.random() * RADIUS + MIN_RADIUS
            }
        }
        else if ( RANDOM_ORBIT ){
            particles[i].orbit += Math.sin( i*2 + particle.angle);
//            if ( particle.orbit > particle.targetOrbit ) {
//                particles[i].orbit -= Math.sin( i*2 + particle.angle);
//            }
//            else if ( particle.orbit < particle.targetOrbit ) {
//                particles[i].orbit += Math.sin( i*2 + particle.angle);
//            }
//            else{
//                particles[i].targetOrbit = Math.random() * RADIUS + MIN_RADIUS;
//                console.log( i, particles[i].targetOrbit, 3 );
//            }
        }


        if ( SPIROGRAPH ) {
            var connections = 0;
            var p = particle;
            for (var j = 0; j < particles.length; j++) {
                if (j != i && connections < 2) {
                    var p2 = particles[j],
                        xd = p2.position.x - p.position.x,
                        yd = p2.position.y - p.position.y;

                    var d = Math.sqrt(xd * xd + yd * yd);

                    var CONNECT_DISTANCE = 50;
                    var connections = 0;
                    if (d < CONNECT_DISTANCE) {
                        connections++;
                        contxt.beginPath();
                        contxt.lineWidth = 1;
                        contxt.lineCap = 'round';
                        contxt.moveTo(p.position.x, p.position.y);
                        contxt.lineTo(p2.position.x, p2.position.y);
                        contxt.strokeStyle = p.color;
                        contxt.stroke();
                    }
                }
            }
        }


        //if we hit the walls
        particle.position.x = Math.max( Math.min( particle.position.x, SCREEN_WIDTH ), 0);
        particle.position.y = Math.max( Math.min( particle.position.y, SCREEN_HEIGHT ), 0);


        particle.size += (particle.targetSize - particle.size) * 0.05;


        if (Math.round(particle.size) == Math.round(particle.targetSize)) {
            particle.targetSize = 1 + Math.random() * 7;
        }

        {
            contxt.beginPath();
            contxt.fillStyle = particle.color;
            contxt.strokeStyle = particle.color;
            contxt.lineWidth = particle.size;
            contxt.moveTo( lp.x, lp.y);
            contxt.lineTo( particle.position.x, particle.position.y );
            contxt.stroke();
            contxt.arc( particle.position.x, particle.position.y, particle.size / 2, 0, Math.PI * 2, true );
            contxt.fill();
        }
    }
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
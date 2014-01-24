
var CANVAS = $('#canvas').get(0);

//radius around the mouse
var MIN_RADIUS = 20;
var RADIUS = 100;

var SCREEN_WIDTH = CANVAS.width;
var SCREEN_HEIGHT = CANVAS.height;
var NUM_PARTICLES = 25;

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

    if ( CANVAS && CANVAS.getContext ) {
        contxt = CANVAS.getContext( '2d' );

        $( CANVAS ).mousemove( mouseMoveEvent );
    }

    createParticles();
    clog(particles);

    setInterval( loop, 1000 / 60 );
}

function createParticles(){
    particles = [];

    for ( var  i = 0; i < NUM_PARTICLES; i++ ) {
        var x = ( Math.random() * SCREEN_WIDTH );
        var y = ( Math.random() * SCREEN_HEIGHT );
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
            color: '#' + ( Math.random() * 0x404040 + 0xaaaaaa | 0).toString(16),
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

function clog(){
    var args = Array.prototype.slice.call(arguments);
    console.log( args );
}

$(document).ready(function(){
    init();
});
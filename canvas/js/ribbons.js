var canvas;
var W, H;
var ctx;
var particles = [];
var STARING_PARTICLES = 80;
var NUM_PARTICLES = 0;
var CONNECT_DISTANCE = 200;
var COLOR_CHANGE_RATE = 1;
var mouseX ;
var mouseY;
var DRAW_POINTS = true;
var DRAW_LINES = false;
var DEBUG = true;
var PARTICLE_LIMIT = 800;
var PARTICLE_RADIUS = 2;

var PAUSE = false;

function init() {
    canvas = $("#canvas").get(0);
    ctx = canvas.getContext('2d');

    W = window.innerWidth, H = window.innerHeight;
    canvas.width = W;
    canvas.height = H;

    $( canvas ).mousemove( mouseMoveEvent );
    $( window ).resize( resizeEvent );

//    createParticle( 100, 200, 45, 3);
//    createParticle( 100, 500, 315, 3);
    createParticles();
//    pause();
    setInterval(draw, 1000 / 30);


//    draw();
}
function createColor() {
    var r = parseInt(Math.random() * 255);
    var g = parseInt(Math.random() * 255);
    var b = parseInt(Math.random() * 255);
    var a = parseInt(Math.random() * 255 + 100);
    return {
        r: r,
        g: g,
        b: b,
        a: a,
        rgba: 'rgba( ' + r + ',' + g + ',' + b + ',' + a + ')'
    }
}
function createParticles() {
    for (var i = 0; i < STARING_PARTICLES; i++) {
        createParticle();
    }
}

function createParticle( x, y, angle, radius, collid, collidedTime) {
    if( NUM_PARTICLES >= PARTICLE_LIMIT){
        return;
    }
    NUM_PARTICLES++;
    var c1 = createColor(), c2 = createColor();

    x = x || Math.random() * W;
    y = y || Math.random() * H;
    angle = ( ( typeof angle == 'number' ) ? ( angle ) : ( Math.random() * 360 ) );
    radius = radius || PARTICLE_RADIUS;
    collid = collid || false;
    collidedTime = collidedTime || 0;

    var particle = {
        x: x,
        y: y,
        radius: radius,
        speed: /*Math.random() * 5 + 2*/3,
        angle: angle,
        color: {
            goal: {
                r: c1.r,
                g: c1.g,
                b: c1.b,
                a: c1.a,
                rgba: c1.rgba
            },
            current: {
                r: c2.r,
                g: c2.g,
                b: c2.b,
                a: c2.a,
                rgba: c2.rgba
            }
        },
        hasCollided: collid,
        collideTime: collidedTime,
        connections: []
    }
    particles.push(particle);
}

function transitionColor(particleIndex) {
    var p = particles[particleIndex];
    var g = p.color.goal;
    var c = p.color.current;
//    console.log(c,g);
    if (c.r > g.r) {
        c.r -= COLOR_CHANGE_RATE;
    }
    if (c.g > g.g) {
        c.g -= COLOR_CHANGE_RATE;
    }
    if (c.b > g.b) {
        c.b -= COLOR_CHANGE_RATE;
    }


    if (c.r < g.r) {
        c.r += COLOR_CHANGE_RATE;
    }
    if (c.g < g.g) {
        c.g += COLOR_CHANGE_RATE;
    }
    if (c.b < g.b) {
        c.b += COLOR_CHANGE_RATE;
    }


    if (c.b == g.b && c.r == g.r && c.g == g.g) {
        g = createColor();
        particles[particleIndex].color.goal = g;
    }

    c.rgba = 'rgba( ' + c.r + ',' + c.g + ',' + c.b + ',' + c.a + ')'
    particles[particleIndex].color.current = c;
}

function draw() {
    //black over fade
    ctx.fillStyle = ('rgba(0, 0, 0, 1)');
    ctx.globalCompositeOperation = 'source-over';
    ctx.fillStyle = ('rgba(0, 0, 0, .02)');
    ctx.fillRect(0, 0, W, H);
    ctx.globalCompositeOperation = 'lighter';

    for (var i = 0; i < particles.length; i++) {
        var p = particles[i];
        if ( !PAUSE ) transitionColor(i);

        ctx.beginPath();
        ctx.fillStyle = p.color.current.rgba;
        ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, false);
        if ( DRAW_POINTS /*&& !PAUSE*/ ) {
            ctx.fill();
        }

        disMouseX = mouseX - p.x,
        disMouseY = mouseY - p.y;

        if ( ( disMouseX <= 15 && disMouseX >= -15 ) && ( disMouseY <= 15 && disMouseY >= -15 )  ) {
            drawParticleInfo( i );
        }

        var connections = 0;
        var hasCollided = false;
        for (var j = 0; j < particles.length; j++) {
            if (j != i && connections < 2 && !PAUSE) {
                var p2 = particles[j],
                    xd = p2.x - p.x,
                    yd = p2.y - p.y;

                var d = Math.sqrt(xd * xd + yd * yd);

                if (d < CONNECT_DISTANCE) {
                    if ( ( checkCollision( i, j ) ) && ( xd <= 5 && xd >= 0 ) && ( yd <= 5 && yd >= 0 )){
                        createParticle( p.x, p.y, ( ( p.angle ) + ( p2.angle ) ) / 2, PARTICLE_RADIUS, true, Date.now() );

                        drawDebugDots(p, p2);
                        collide( i, j );
//                        pause();
                    }
//                    p.connections.push( j );
                    connections++;
                    ctx.beginPath();
                    ctx.lineWidth = 1;
                    ctx.lineCap = 'round';
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.strokeStyle = p.color.current.rgba;
                    if( DRAW_LINES ) ctx.stroke();
                }
            }
        }

        //movement
        if ( !PAUSE ) {
            p.x = p.x + p.speed * Math.cos(p.angle * Math.PI / 180);
            p.y = p.y + p.speed * Math.sin(p.angle * Math.PI / 180);
        }


        //boundry check
        if (p.x < 0) p.x = W;
        if (p.x > W) p.x = 0;

        if (p.y < 0) p.y = H;
        if (p.y > H) p.y = 0;

    }
    drawDebug({
        particles: NUM_PARTICLES,
        'mouse x': mouseX,
        'mouse y': mouseY,
        connectD: CONNECT_DISTANCE
    });

}

/**
 * return true if can collide again
 * @param i 1st particle index
 * @param j 2nd particle index
 */
function checkCollision( i, j ){
    var p1 = particles[i];
    var p2 = particles[j];

    if ( p1.hasCollided && p2.hasCollided ) {
//        if ( p2.collideTime - p1.collideTime < 10000 && p1.collideTime - p2.collideTime < 10000 ) {
        if ( Date.now() - p1.collideTime > 10000 && Date.now() - p2.collideTime > 10000 ) {
            return true;
        }
        else{
            return false;
        }
    }
    else {
        return true;
    }
}
function collide( i, j ){
    particles[i].hasCollided = true;
    particles[j].hasCollided = true;

    particles[i].collideTime = Date.now();
    particles[j].collideTime = Date.now();

}

function drawParticleInfo( i ){
    var p = particles[i];
    if ( DEBUG ) {
        drawDebug({
            x: Math.round( p.x ),
            y: Math.round( p.y ),
            angle: Math.round( p.angle ),
            speed: p.speed,
            color: p.color.current.rgba,
            collideTime: p.collideTime
        }, p.x + 15, p.y + 15, 200)
    }
}

function drawDebugDots( p1, p2 ){
    ctx.globalCompositeOperation = 'source-over';
    ctx.beginPath();
    ctx.fillStyle = '#ff0000';
    ctx.arc(p1.x, p1.y, p1.radius, 0, 2 * Math.PI, false);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = '#ff0000';
    ctx.arc(p2.x, p2.y, p2.radius, 0, 2 * Math.PI, false);
    ctx.fill();

    ctx.strokeStyle = 'red';
    var squareRadius = 30;
    ctx.rect( p1.x - squareRadius/2, p1.y - squareRadius / 2 , p1.radius + squareRadius, p1.radius+ squareRadius)
    ctx.stroke();

    ctx.globalCompositeOperation = 'lighter';
}

function drawDebug( fields, sx, sy, width, height ){
    if(!DEBUG){return}
    sx = sx || 10;
    sy = sy || 10;
    width = width || 150;
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
    ctx.fillText("Debug", sx + 2, sy +14);


    var i = 1;
    for( x in fields ){
        ctx.fillText( x + ': ' + fields[x], sx+2, sy + 14 + ( 16 * i ));
        i++;
    }

}

function echoParticles(){
    console.log( particles );
}

function mouseMoveEvent( e ){
    var rect = canvas.getBoundingClientRect();
    mouseX =  e.clientX - rect.left;
    mouseY =  e.clientY - rect.top;
}

function resizeEvent(){
    canvas.height = ( $(window).height() );
    canvas.width = ( $(window).width() );

    W = canvas.width;
    H = canvas.height;
}

function clearParticles(){
    particles = [];
    NUM_PARTICLES = 0;
}

function cpp(){
    PAUSE = true;
    particles = [];
    NUM_PARTICLES = 0;
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


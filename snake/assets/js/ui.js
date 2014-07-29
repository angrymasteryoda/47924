/**************************************************************************/
/************************************|*************************************/
/**************************************************************************/
/**
 * @author Michael Risher
 */

var canvas = $( '#canvas' )[0];
var ctx = canvas.getContext( '2d' );
var width = $( canvas ).width();
var height = $( canvas).height();

var cellW = 10;
var dir;
var food;
var score;
var snake;

//get it done
init();

function init(){
	dir = 'right';
	initSnake();
	//start loop
	if ( typeof gameLoop != 'undefined'  ) {
		clearInterval( gameLoop );
	}
	gameLoop = setInterval( paint, 60 );
}

function initSnake(){
	var len = 5;
	snake = [];
	for( var i = len - 1; i >= 0; i-- ){
		snake.push( { x : i, y : 0 } );
	}
}

function paint(){
	ctx.fillStyle = "white";
	ctx.fillRect( 0, 0, width, height );
	ctx.strokeStyle = "black";
	ctx.strokeRect(0, 0, width, height );
}

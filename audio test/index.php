<!DOCTYPE html>
<html>
<head>
	<title>Example - audioJS</title>
	<meta charset="utf-8">
</head>
<body>
<style>
	/*.play-button {*/
		/*height: 40px;*/
		/*width: 65px;*/
		/*background-color: rgba(0, 0, 0, 0.7);*/
		/*border-radius: 5px;*/
		/*position: relative;*/
		/*float: left;*/
	/*}*/

	/*.play-button:hover {*/
		/*background-color: rgba(0, 173, 239, 0.9);*/
	/*}*/

	/*.play-button:after {*/
		/*content: "";*/
		/*display: block;*/
		/*position: absolute;*/
		/*top: 10.5px;*/
		/*left: 24px;*/
		/*margin: 0 auto;*/
		/*border-style: solid;*/
		/*border-width: 9.5px 0 9.5px 17px;*/
		/*border-color: transparent transparent transparent rgba(255, 255, 255, 1);*/
	/*}*/

	/*.stop-button {*/
		/*height: 40px;*/
		/*width: 65px;*/
		/*background-color: rgba(0, 0, 0, 0.7);*/
		/*border-radius: 5px;*/
		/*position: relative;*/
		/*float: left;*/
		/*margin-left:20px;*/
	/*}*/

	/*.stop-button:hover {*/
		/*background-color: rgba(0, 173, 239, 0.9);*/
	/*}*/

	/*.stop-button:after {*/
		/*content: "";*/
		/*display: block;*/
		/*position: absolute;*/
		/*top: 10.5px;*/
		/*left: 24px;*/
		/*margin: 0 auto;*/
		/*border-style: solid;*/
		/*border-width: 9.5px;*/
		/*border-color: #fff;*/
	/*}*/

	/*.container{*/
		/*position: relative;*/
		/*width:500px;*/
		/*height:40px;*/
		/*border:2px solid #000;*/
	/*}*/
	#play, #stop{
		padding: 20px;
		display: inline-block;
		background: #000;
		color: cyan;
		height: 30px;
		width: 30px;
		text-align: center;
		font-weight: bold;
		cursor: pointer;
	}
</style>

<div class="container">
	<div id="play" class="play-button">P</div>
	<div id="stop" class="stop-button">S</div>
</div>

<script src="soundmanager2.js"></script>
<script src="core.js"></script>
<script>
	$( document ).ready( function(){
		var mySound = null;

		soundManager.setup({
			url : "soundmanager/swf",
			onready : function(){
				mySound = soundManager.createSound({
					url: 'audio.mp3'
				});
			}
		});


		$('#play' ).click( function(){
			mySound.play();
		} )

		$('#stop' ).click( function(){
			mySound.pause();
		} )
	} );
</script>
</body>
</html>
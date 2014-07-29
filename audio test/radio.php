<!DOCTYPE html>
<html>
<head>
	<title>Example - audioJS</title>
	<link href="assets/css/style.css" rel="stylesheet" type="text/css" />
	<meta charset="utf-8">
</head>
<body>
<div id="radio_player">
	<div class="listing">
		<span class="title bold">Episode Title</span>
		<span class="guest bold">Guest</span>
		<span class="date bold">Date</span>
		<ul></ul>
	</div>
	<div class="controls">
		<span class="control" control="play">P</span>
		<span class="control" control="mute">M</span>
		<span class="control" control="open">O</span>
			<span class="timer">
				<span class="current">00:00</span> / <span class="length">00:00</span>
			</span>

		<div class="progress"><b></b></div>
	</div>
</div>
<script src="assets/soundmanager/script/soundmanager2.js"></script>
<script src="assets/js/core.js"></script>
<script src="assets/js/ui.js"></script>
</body>
</html>
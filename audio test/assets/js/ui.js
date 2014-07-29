var nowPlaying = null;

$( document ).ready( function () {
	var media_player = null;
	var player_type = 0; // 0 means HTML5 "audio" tag. 1 means SoundJS plugin.
	var auto_play_next = true;

	function init_radio_player() {
		$.get( 'http://192.168.6.98/OmniWeaver/omniweaver/content/getRadioShow&site=greenriverside', function ( response ) {
			var items = $( response ).find( 'item' ).slice( 0, 50 );
			var listing = $( '#radio_player .listing ul' );

			listing.empty();

			for ( var i = 0; i < items.length; i++ ) {
				var title = $( items[i] ).find( 'title' ).text();
				var guest = $( items[i] ).children( ':eq(3)' ).text();
				var date = $( items[i] ).find( 'pubDate' ).text();
				var url = $( items[i] ).find( 'enclosure' ).attr( 'url' );

				date = new Date( date );

				listing.append(
					'<li url="' + url + '" key="' + Math.floor( Math.random() * 10000 + 1 ) + '_' + title + '">' +
						'<span class="title" title="' + title + '">' + title + '</span>' +
						'<span class="guest" title="' + guest + '">' + guest + '</span>' +
						'<span class="date">' + ('0' + date.getMonth()).slice( -2 ) + '/' + ('0' + date.getDate()).slice( -2 ) + '/' + date.getFullYear().toString().substr( 2, 2 ) + '</span>' +
				'</li>' );
			}
		} );

		try { // if your browser has native <audio> tags that can play music for you.
			var A = new Audio();
			player_type = 0;
			A = null;
		} catch ( fail ) { // else it can't, so we will try to simulate it...
			fail = null;
			player_type = 1;

		} // end catch
	}
	init_radio_player();

	$('body').on('click', '#radio_player li', function () {
		var mySound = null;
		var url = $(this).attr('url');
		soundManager.stopAll();
		soundManager.setup({
			url : "soundmanager/swf",
			debugMode: false,
			onready : function(){
				mySound = soundManager.createSound({
					id : $(this).attr( 'key' ),
					url: url,
					autoPlay: false
				});
				mySound.play();
			}
		});

//		mySound = soundManager.createSound({
//			id : $(this).attr( 'key' ),
//			url: url,
//			autoPlay: false
//		});
//
//		mySound.play();

		$( '.control[control="play"]' ).click( function(){
			mySound.play();
		} );

		$('#stop' ).click( function(){
			mySound.pause();
		} )

	} );

/*
	$('body').on('click', '#radio_player li', function () {
		var url = $(this).attr('url');

		console.log(url);

		if (window.nowPlaying) {
			window.nowPlaying.pause();
		}

		$(this).addClass('playing').siblings().removeClass('playing');

		window.nowPlaying = new Audio(url);
		window.nowPlaying.play();

		window.nowPlaying.addEventListener('timeupdate', function () {
			var duration = window.nowPlaying.duration;
			var current = window.nowPlaying.currentTime;
			var percentage = (100 / duration) * current;

			if (current > 0) {
				current = ('0' + Math.floor(current / 60)).toString().slice(-2) + ':' + ('0' + Math.floor(current % 60)).toString().slice(-2);
				duration = ('0' + Math.floor(duration / 60)).toString().slice(-2) + ':' + ('0' + Math.floor(duration % 60)).toString().slice(-2);
			} else {
				current = '00:00';
				duration = '00:00';
			}

			$('#radio_player .current').text(current);
			$('#radio_player .length').text(duration);

			$('#radio_player .progress b').width(percentage + '%');
		}, false);
	});

	$('body').on('click', '#radio_player [control="pause"]', function () {
		window.nowPlaying.pause();

		$(this).attr('control', 'play');
	});

	$('body').on('click', '#radio_player [control="play"]', function () {
		window.nowPlaying.play();

		$(this).attr('control', 'pause');
	});

	$('body').on('click', '#radio_player [control="mute"]', function () {
		window.nowPlaying.muted = true;

		$(this).attr('control', 'unmute');
	});

	$('body').on('click', '#radio_player [control="unmute"]', function () {
		window.nowPlaying.muted = false;

		$(this).attr('control', 'mute');
	});
	*/
} );
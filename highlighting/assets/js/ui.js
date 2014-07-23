$( document ).ready( function(){
	$( '.expand' ).on( {
		'click' : function(){
			if ( $( this ).hasClass( 'plus') ) {
				$( this ).removeClass( 'plus' ).addClass( 'minus' ).attr( 'src', "http://www.lavote.net/Images/icon_collapse.gif" );
			}
			else if ( $( this ).hasClass( 'minus') ) {
				$( this ).removeClass( 'minus' ).addClass( 'plus' ).attr( 'src', "http://www.lavote.net/Images/icon_expand.gif" );
			}
			$( this ).siblings( 'ul' ).slideToggle( 300 );
		}
	} );

	$('pre.highlight' ).on({
		keyup : function(){

		}
	} );

	$('.controls #save' ).on({
		click : function(){
			if( localStorage ) {
				localStorage.code = $( this ).parent().parent().find( 'pre.highlight' ).text();
			}

		}
	});

	$('.controls #load' ).on({
		click : function(){
			if( localStorage ) {
				$( this ).parent().parent().find( 'pre.highlight' ).text( localStorage.code );
			}

		}
	});
} );


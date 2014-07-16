$( document ).ready( function(){
	$( '.expand' ).on( {
		'click' : function(){
			if ( $( this ).hasClass( 'plus') ) {
				$( this ).removeClass( 'plus' ).addClass( 'minus' ).attr( 'src', "../img/icon_collapse.gif" );
			}
			else if ( $( this ).hasClass( 'minus') ) {
				$( this ).removeClass( 'minus' ).addClass( 'plus' ).attr( 'src', "../img/icon_expand.gif" );
			}
			$( this ).siblings( 'ul' ).slideToggle( 300 );
		}
	} );
} );
'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( '.fsp-settings-collapser' ).on( 'click', function () {
			let _this = $( this );

			if ( ! _this.parent().hasClass( 'fsp-is-open' ) )
			{
				_this.parent().find( '.fsp-settings-collapse' ).slideToggle();
				_this.find( '.fsp-settings-collapse-state' ).toggleClass( 'fsp-is-rotated' );
			}
		} );
	} );
} )( jQuery );
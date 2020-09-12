'use strict';

( function ( $ ) {
	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
		let cookie_sid = $( '#fspCookie_sid' ).val().trim();
		let cookie_hsid = $( '#fspCookie_hsid' ).val().trim();
		let cookie_ssid = $( '#fspCookie_ssid' ).val().trim();
		let proxy = $( '#fspProxy' ).val().trim();

		if ( cookie_sid === '' || cookie_hsid === '' || cookie_ssid === '' )
		{
			FSPoster.toast( fsp__( 'Please, enter your cookies!' ), 'warning' );

			return;
		}

		FSPoster.ajax( 'add_google_b_account', { cookie_sid, cookie_hsid, cookie_ssid, proxy }, function () {
			accountAdded();
		} );
	} );
} )( jQuery );
'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		doc.on( 'click', '#fspUpdateBtn', function () {
			let purchaseKey = $( '#fspPurchaseKey' ).val().trim();

			if ( purchaseKey === '' )
			{
				FSPoster.toast( fsp__( 'Please enter the purchase code!' ), 'warning' );

				return;
			}

			FSPoster.ajax( 'update_app', { 'code': purchaseKey }, function () {
				FSPoster.toast( fsp__( 'Plugin updated!' ), 'success' );
				FSPoster.loading( true );
				window.location.reload();
			} );
		} );
	} );
} )( jQuery );
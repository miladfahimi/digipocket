'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		doc.on( 'click', '#fspInstallBtn', function () {
			let purchaseKey = $( '#fspPurchaseKey' ).val().trim();
			let email = $( '#fspEmail' ).val().trim();
			let marketingStatistics = $( '#fspMarketingStatistics' ).val();

			if ( purchaseKey === '' )
			{
				FSPoster.toast( fsp__( 'Please enter the purchase code!' ), 'warning' );

				return;
			}
			else if ( ! marketingStatistics )
			{
				FSPoster.toast( fsp__( 'Please, let us know how did you find us!' ), 'warning' );

				return;
			}

			FSPoster.ajax( 'activate_app', { 'code': purchaseKey, 'statistic': marketingStatistics, 'email': email }, function () {
				FSPoster.toast( fsp__( 'Plugin installed!' ), 'success' );
				FSPoster.loading( true );
				window.location.reload();
			} );
		} );
	} );
} )( jQuery );
'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( '#fspSaveSettings' ).on( 'click', function () {
			let data = FSPoster.serialize( $( '#fspSettingsForm' ) );

			FSPoster.ajax( 'settings_url_save', data, function () {
				FSPoster.toast( fsp__( 'Saved successfully!' ) , 'success');
			} );
		} );

		$( '#fspURLShortener' ).on( 'change', function () {
			if ( $( this ).is( ':checked' ) )
			{
				$( '#fspShortenerRow' ).slideDown();
			}
			else
			{
				$( '#fspShortenerRow' ).slideUp();
			}
		} ).trigger( 'change' );

		$( '#fspShortenerSelector' ).on( 'change', function () {
			if ( $( this ).val() === 'bitly' )
			{
				$( '#fspBitly' ).slideDown();
			}
			else
			{
				$( '#fspBitly' ).slideUp();
			}
		} ).trigger( 'change' );

		$( '#fspCustomURL' ).on( 'change', function () {
			if ( $( this ).is( ':checked' ) )
			{
				$( '#fspCustomURLRow_1' ).slideUp();
				$( '#fspCustomURLRow_2' ).slideDown();
			}
			else
			{
				$( '#fspCustomURLRow_1' ).slideDown();
				$( '#fspCustomURLRow_2' ).slideUp();
			}
		} ).trigger( 'change' );

		$( '#fspUseGA' ).on( 'click', function () {
			$( this ).parent().parent().children( 'input' ).val( 'utm_source={network_name}&utm_medium={account_name}&utm_campaign=FS%20Poster' );
		} );
	} );
} )( jQuery );
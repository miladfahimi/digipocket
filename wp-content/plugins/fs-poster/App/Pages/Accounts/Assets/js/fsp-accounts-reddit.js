'use strict';

( function ( $ ) {
	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
		let _this = $( this );
		let appID = $( '#fspModalAppSelector' ).val().trim();
		let proxy = $( '#fspProxy' ).val().trim();

		if ( ! ( appID > 0 ) )
		{
			FSPoster.toast( fsp__( 'Please, select an application!' ), 'warning' );

			return;
		}

		let openURL = `${ fspConfig.siteURL }/?reddit_app_redirect=${ appID }&proxy=${ proxy }`;

		if ( $( '#fspModalAppSelector > option:selected' ).data( 'is-standart' ).toString() === '1' )
		{
			openURL = `${ fspConfig.standartAppURL }&proxy=${ proxy }&encode=true`;
		}

		window.open( openURL, 'fs-app', 'width=750, height=550' );
	} );
} )( jQuery );
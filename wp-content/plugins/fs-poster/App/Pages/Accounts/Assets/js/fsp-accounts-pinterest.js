'use strict';

( function ( $ ) {
	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
		let _this = $( this );
		let selectedMethod = String( $( '.fsp-modal-option.fsp-is-selected' ).data( 'step' ) );

		if ( selectedMethod === '1' ) // app method
		{
			let appID = $( '#fspModalStep_1 #fspModalAppSelector' ).val().trim();
			let proxy = $( '#fspProxy' ).val().trim();

			if ( ! ( appID > 0 ) )
			{
				FSPoster.toast( fsp__( 'Please, select an application!' ), 'warning' );

				return;
			}

			let openURL = `${ fspConfig.siteURL }/?pinterest_app_redirect=${ appID }&proxy=${ proxy }`;

			if ( $( '#fspModalStep_1 #fspModalAppSelector > option:selected' ).data( 'is-standart' ).toString() === '1' )
			{
				openURL = `${ fspConfig.standartAppURL }&proxy=${ proxy }&encode=true`;
			}

			window.open( openURL, 'fs-standart-app', 'width=750, height=550' );
		}
		else if ( selectedMethod === '2' ) // cookie method
		{
			let cookie_sess = $( '#fspModalStep_2 #fspCookie_sess' ).val().trim();
			let proxy = $( '#fspProxy' ).val().trim();

			if ( cookie_sess === '' )
			{
				FSPoster.toast( fsp__( 'Please, enter your cookies!' ), 'warning' );

				return;
			}

			FSPoster.ajax( 'add_pinterest_account_cookie_method', { cookie_sess, proxy }, function () {
				accountAdded();
			} );
		}
	} );
} )( jQuery );
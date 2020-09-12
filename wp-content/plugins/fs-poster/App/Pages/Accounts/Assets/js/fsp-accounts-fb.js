'use strict';

( function ( $ ) {
	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
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

			let openURL = `${ fspConfig.siteURL }/?fb_app_redirect=${ appID }&proxy=${ proxy }`;

			if ( $( '#fspModalStep_1 #fspModalAppSelector > option:selected' ).data( 'is-standart' ).toString() === '1' )
			{
				openURL = `${ fspConfig.standartAppURL }&proxy=${ proxy }&encode=true`;
			}

			window.open( openURL, 'fs-app', 'width=750, height=550' );
		}
		else if ( selectedMethod === '2' ) // cookie method
		{
			let cookie_c_user = $( '#fspModalStep_2 #fspCookie_c_user' ).val().trim();
			let cookie_xs = $( '#fspModalStep_2 #fspCookie_xs' ).val().trim();
			let proxy = $( '#fspProxy' ).val().trim();

			if ( cookie_c_user === '' || cookie_xs === '' )
			{
				FSPoster.toast( fsp__( 'Please, enter your cookies!' ), 'warning' );

				return;
			}

			FSPoster.ajax( 'add_new_fb_account_with_cookie', { cookie_c_user, cookie_xs, proxy }, function () {
				accountAdded();
			} );
		}
	} );
} )( jQuery );
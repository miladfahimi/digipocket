'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
			let _this = $( this );
			let selectedMethod = String( $( '.fsp-modal-option.fsp-is-selected' ).data( 'step' ) );

			if ( selectedMethod === '1' )
			{
				let cookie_sessionid = $( '#fspModalStep_1 #fspCookie_sessionid' ).val().trim();
				let proxy = $( '#fspProxy' ).val().trim();

				if ( cookie_sessionid === '' )
				{
					FSPoster.toast( fsp__( 'Please, enter your cookies!' ), 'warning' );

					return;
				}

				FSPoster.ajax( 'add_instagram_account_cookie_method', { cookie_sessionid, proxy }, function () {
					accountAdded();
				} );
			}
			else if ( selectedMethod === '2' ) // cookie method
			{
				let username = $( '#fspModalStep_2 #fspUsername' ).val().trim();
				let password = $( '#fspModalStep_2 #fspPassword' ).val().trim();
				let proxy = $( '#fspProxy' ).val().trim();

				if ( username === '' || password === '' )
				{
					FSPoster.toast( fsp__( 'Please, enter your cookies!' ), 'warning' );

					return;
				}

				FSPoster.ajax( 'add_instagram_account', { username, password, proxy }, function ( response ) {
					requireAction( response, username, password, proxy );
				} );
			}
		} );
	} );
} )( jQuery );

function requireAction ( response, username, password, proxy )
{
	if ( typeof jQuery !== 'undefined' ) $ = jQuery;

	if ( 'do' in response && response['do'] === 'challenge' )
	{
		$( '.fsp-modal-body' ).html( `<p class="fsp-modal-p">
			${ fsp__( 'Challenge required! Activation code was sent to ' ) } ${ FSPoster.htmlspecialchars( response[ 'message' ] ) }.
		</p>
		<div class="fsp-modal-step">
			<div class="fsp-form-group">
				<label>${ fsp__( 'Activation code' ) }</label>
				<div class="fsp-form-input-has-icon">
					<i class="far fa-copy"></i>
					<input id="fspActivationCode" class="fsp-form-input" autocomplete="off" placeholder="${ fsp__( 'Enter the activation code' ) }">
				</div>
			</div>
		</div>` );

		$( '#fspModalAddButton' ).off( 'click' ).on( 'click', function () {
			let code = $( '#fspActivationCode' ).val().trim();

			if ( code === '' )
			{
				FSPoster.toast( fsp__( 'Please, enter activation code.' ), 'warning' );

				return;
			}

			FSPoster.ajax( 'instagram_confirm_challenge', { username, password, proxy, code, user_id: response[ 'user_id' ], nonce_code: response[ 'nonce_code' ] }, function ( response ) {
				requireAction( response, username, password, proxy );
			} );
		} );
	}
	else if ( 'do' in response && response['do'] === 'two_factor' )
	{
		$( '.fsp-modal-body' ).html( `<p class="fsp-modal-p">
			${ fsp__( 'Two factor authentication required! Activation code was sent to ' ) } ${ FSPoster.htmlspecialchars( response[ 'message' ] ) }.
		</p>
		<div class="fsp-modal-step">
			<div class="fsp-form-group">
				<label>${ fsp__( 'Activation code' ) }</label>
				<div class="fsp-form-input-has-icon">
					<i class="far fa-copy"></i>
					<input id="fspActivationCode" class="fsp-form-input" autocomplete="off" placeholder="${ fsp__( 'Enter the activation code' ) }">
				</div>
			</div>
		</div>` );

		$( '#fspModalAddButton' ).off( 'click' ).on( 'click', function () {
			let code = $( '#fspActivationCode' ).val().trim();

			if ( code === '' )
			{
				FSPoster.toast( fsp__( 'Please, enter activation code.' ), 'warning' );

				return;
			}

			FSPoster.ajax( 'instagram_confirm_two_factor', { username, password, proxy, code, two_factor_identifier: response[ 'two_factor_identifier' ] }, function ( response ) {
				requireAction( response, username, password, proxy );
			} );
		} );
	}
	else
	{
		accountAdded();
	}
}
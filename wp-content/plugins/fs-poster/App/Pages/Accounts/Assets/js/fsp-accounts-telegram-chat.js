'use strict';

( function ( $ ) {
	$( '#fspReloadChats' ).on( 'click', function () {
		let accountID = $( '#fspAccountID' ).val().trim();

		if ( ! ( accountID > 0 ) )
		{
			FSPoster.toast( fsp__( 'Account ID is not correct. Please, refresh page and try again.' ), 'warning' );

			return;
		}

		FSPoster.ajax( 'telegram_last_active_chats', { account: accountID }, function ( result ) {
			if ( result[ 'list' ].length === 0 )
			{
				FSPoster.toast( fsp__( 'No active chat(s) found.' ), 'warning' );

				return;
			}

			let chatSelector = $( '#fspModalChatSelector' );

			chatSelector.html( `<option disabled selected>${ fsp__(  'Select chat') }</option>` );

			for ( let i in result[ 'list' ] )
			{
				chatSelector.append( `<option value="${ result[ 'list' ][ i ][ 'id' ] }">${ result[ 'list' ][ i ][ 'name' ] }</option>` );
			}
		} );
	} ).trigger( 'click' );

	$( '#fspModalChatSelector' ).on( 'change', function () {
		let chatID = $( this ).val().trim();

		if ( chatID === '' )
		{
			FSPoster.toast( fsp__( 'Chat ID is not correct.' ), 'warning' );

			return;
		}

		$( '#fspChatID' ).val( chatID );
	} );

	$( '.fsp-modal-footer > #fspModalAddButton' ).on( 'click', function () {
		let accountID = $( '#fspAccountID' ).val().trim();
		let chatID = $( '#fspChatID' ).val().trim();

		if ( chatID === '' )
		{
			FSPoster.toast( fsp__( 'Please, enter Chat ID.' ), 'warning' );

			return;
		}

		FSPoster.ajax( 'telegram_chat_save', { 'account_id': accountID, 'chat_id': chatID }, function () {
			accountAdded();
		} );
	} );
} )( jQuery );
( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( '#fspSaveSettings' ).on( 'click', function () {
			let data = FSPoster.serialize( $( '#fspSettingsForm' ) );

			FSPoster.ajax( 'settings_general_save', data, function () {
				FSPoster.toast( fsp__( 'Saved successfully!' ) , 'success');
			} );
		} );
	} );

	$( '#fspCheckAccounts' ).on( 'change', function () {
		if ( $( this ).is( ':checked' ) )
		{
			$( '#fspDisableAccountsRow' ).slideDown();
		}
		else
		{
			$( '#fspDisableAccountsRow' ).slideUp();
		}
	} ).trigger( 'change' );

	$(".select2-init").select2();

	$( '[data-open-img]' ).on( 'click', function () {
		let img = $( this ).data( 'open-img' );

		FSPoster.modal( `<div class="fsp-modal-body"><img src="${ img }"></div><div class="fsp-modal-footer"><button class="fsp-button" data-modal-close="true">CLOSE</button></div>`, true, true );
	} );
} )( jQuery );

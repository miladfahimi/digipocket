( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( '#fspSaveSettings' ).on( 'click', function () {
			let data = FSPoster.serialize( $( '#fspSettingsForm' ) );

			FSPoster.ajax( 'settings_linkedin_save', data, function () {
				FSPoster.toast( fsp__( 'Saved successfully!' ) , 'success');
			} );
		} );
	} );
} )( jQuery );
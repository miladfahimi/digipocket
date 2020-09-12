(function ($) {
	let doc = $( document );

	doc.ready( function () {
		$( '#fspSaveSettings > span' ).text( fsp__( 'EXPORT SETTINGS' ) );
		$( '#fspSaveSettings > i' ).prop( 'class', 'fas fa-file-export' );
		$( '#fspSaveSettings' ).on( 'click', function () {
			let data = FSPoster.serialize( $( '#fspSettingsForm' ) );

			FSPoster.ajax( 'settings_export_save', data, function (result) {
				FSPoster.toast( fsp__( 'Export is successful. The download process is starting.' ), 'success' );

				window.location.href = `${ window.location.href }&download=${ result[ 'file_id' ] }`;
			} );
		} ).before( `<button id="fspImportFileButton" class="fsp-button fsp-is-red"><i class="fas fa-file-import"></i> <span>${ fsp__( 'IMPORT SETTINGS' ) }</span></button>` );

		doc.on( 'click', '#fspImportFileButton', function () {
			$( '#fspImportFileInput' ).click();
		} );

		$( '#fspImportFileInput' ).on( 'change', function () {
			$( '#fspImportFileButton > span' ).text( fsp__( 'UPLOADING...' ) );
			$( '#fspImportFileButton > i' ).prop( 'class', 'far fa-clock' );

			let _this = $( this );
			let importedFile = _this[ 0 ].files[ 0 ];

			if ( ! importedFile )
			{
				FSPoster.toast( fsp__( 'File can\'t be empty' ), 'warning' );

				return;
			}

			FSPoster.confirm( fsp__( 'The current data will be removed from the plugin and the data in the import file will be restored. Are you sure you want to continue?' ), function () {
				let data = new FormData();
				data.append( 'fsp_import_file', importedFile );

				FSPoster.ajax( 'settings_import_save', data, function () {
					$( '#fspImportFileButton > span' ).text( fsp__( 'IMPORT SETTINGS' ) );
					$( '#fspImportFileButton > i' ).prop( 'class', 'fas fa-file-import' );
					$( '#fspImportFileInput' ).val( '' );

					FSPoster.toast( fsp__( 'Successfully restored!' ), 'success' );
				} );
			}, 'fas fa-exclamation-triangle', fsp__( 'YES, IMPORT' ), function () {
				$( '#fspImportFileButton > span' ).text( fsp__( 'IMPORT SETTINGS' ) );
				$( '#fspImportFileButton > i' ).prop( 'class', 'fas fa-file-import' );

				$( '#fspImportFileInput' ).val( '' );
			} );
		} );

		$( '#fspExportAccounts' ).on( 'change', function () {
			if ( $( this ).is( ':checked' ) )
			{
				$( '#fspExportFailedAccountsRow' ).slideDown();
				$( '#fspExportAccountsStatusesRow' ).slideDown();
			}
			else
			{
				$( '#fspExportFailedAccountsRow' ).slideUp();
				$( '#fspExportAccountsStatusesRow' ).slideUp();
			}
		} ).trigger( 'change' );

		$( '#fspExportLogs' ).on( 'change', function () {
			if ( $( this ).is( ':checked' ) )
			{
				$( '#fspExportSchedulesRow' ).slideDown();
			}
			else
			{
				$( '#fspExportSchedulesRow' ).slideUp();
			}
		} ).trigger( 'change' );
	} );
})( jQuery );
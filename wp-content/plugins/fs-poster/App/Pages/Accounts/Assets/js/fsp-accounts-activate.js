'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( '#fspCategories' ).select2( {
			'placeholder': 'Select...'
		} );

		$( '.fsp-modal-footer > #fspModalActivateBtn' ).on( 'click', function () {
			let id = $( '#fspActivateID' ).val();
			let ajaxType = $( '#fspActivateURL' ).val();
			let cats = $( '#fspCategories' ).val();
			let filterType = String( $( '.fsp-modal-option.fsp-is-selected' ).data( 'name' ) );

			if ( cats === '' )
			{
				cats = $( '#fspCategories' ).select2( 'val' );
			}

			FSPoster.ajax( ajaxType, { id, checked: 1, categories: cats, filter_type: filterType }, function () {
				$( `.fsp-account-item[data-id=${ id }][data-type="${ ajaxType === 'account_activity_change' ? 'account' : 'community' }"] .fsp-account-checkbox > i` ).removeClass( 'far' ).addClass( 'fas fsp-is-checked-conditionally' );

				$( '[data-modal-close=true]' ).click();
			} );
		} );
	} );
} )( jQuery );
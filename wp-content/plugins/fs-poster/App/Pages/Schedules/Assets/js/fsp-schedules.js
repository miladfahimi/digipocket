'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		doc.on( 'click', '.fsp-delete-schedule', function () {
			let id = $( this ).data( 'id' );
			let row = $( `.fsp-schedule[data-id=${ id }]` );

			if ( id )
			{
				FSPoster.confirm( fsp__( 'Are you sure you want to delete?' ), function () {
					FSPoster.ajax( 'delete_schedule', { id }, function ( result ) {
						row.fadeOut( 300, function () {
							$( this ).remove();
						} );

						if ( $( '.fsp-schedules > .fsp-schedule' ).length === 0 )
						{
							$( '.fsp-emptiness' ).removeClass( 'fsp-hide' );
						}

						$( '#fspSchedulesCount' ).text( parseInt( $( '#fspSchedulesCount' ).text() ) - 1 );
						$( '#fspRemoveSelected' ).addClass( 'fsp-hide' );
					} );
				} );
			}
		} ).on( 'click', '.fsp-change-schedule', function () {
			let id = $( this ).data( 'id' );

			if ( id )
			{
				FSPoster.ajax( 'schedule_change_status', { id }, function ( result ) {
					FSPoster.loading( 1 );
					window.location.reload();
				} );
			}
		} ).on( 'click', '.fsp-schedule-checkbox', function () {
			let selectedCount = $( '.fsp-schedule-checkbox:checked' ).length;

			$( '#fspSelectedCount > span' ).text( selectedCount );

			if ( selectedCount )
			{
				$( '#fspRemoveSelected' ).removeClass( 'fsp-hide' );
			}
			else
			{
				$( '#fspRemoveSelected' ).addClass( 'fsp-hide' );
			}
		} );
	} );

	$( '#fspRemoveSelected' ).on( 'click', function () {
		let selectedCount = $( '.fsp-schedule-checkbox:checked' ).length;

		if ( selectedCount )
		{
			FSPoster.confirm( fsp__( 'Are you sure you want to delete all selected schedules?' ), function () {
				let selectedIDs = [];

				$( '.fsp-schedule-checkbox:checked' ).each( function () {
					let id = $( this ).data( 'id' );

					selectedIDs.push( id );
				} );

				FSPoster.ajax( 'delete_schedules', { ids: selectedIDs }, function ( result ) {
					selectedIDs.forEach( function ( id ) {
						$( `.fsp-schedule[data-id=${ id }]` ).fadeOut( 300, function () {
							$( this ).remove();
						} );

						if ( $( '.fsp-schedules > .fsp-schedule' ).length === 0 )
						{
							$( '.fsp-emptiness' ).removeClass( 'fsp-hide' );
						}

						$( '#fspSchedulesCount' ).text( parseInt( $( '#fspSchedulesCount' ).text() ) - 1 );
						$( '#fspRemoveSelected' ).addClass( 'fsp-hide' );
					} );
				} );
			} );
		}
		else
		{
			FSPoster.toast( fsp__( 'You need to select schedules for delete!' ), 'warning' );
		}
	} );

	$( '.fsp-schedule-checkbox:checked' ).trigger( 'click' );
} )( jQuery );
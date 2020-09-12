'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		function reloadStats ()
		{
			let allCount = parseInt( $( '#all_count' ).text() );

			if ( allCount === 0 )
			{
				let realAllCount = $( '.fsp-sharing-account[data-status]' ).length;

				$( '#all_count' ).text( realAllCount );
			}

			let sendedCount = $( '.fsp-sharing-account[data-status=1]' ).length;

			$( '#finished_count' ).text( sendedCount );

			let percent = parseInt( sendedCount / FSPObject.feedsCount * 100 );

			$( '#share_progress_bar' ).css( 'width', `${ percent }%` );

			if ( FSPObject.feedsCount === sendedCount )
			{
				$( '.fsp-modal-close' ).removeClass( 'fsp-hide' );
			}
			else if ( ! $( '.fsp-modal-close' ).hasClass( 'fsp-hide' ) )
			{
				$( '.fsp-modal-close' ).addClass( 'fsp-hide' );
			}
		}

		function sendNext ()
		{
			var next = $( ".fsp-sharing-account[data-status=0]:eq(0)" );

			next.find( '.fsp-sharing-account-status' ).html( '<div class="fsp-status fsp-is-warning fsp-tooltip" data-title="' + fsp__( 'sharing...' ) + '"><i class="fas fa-clock"></i></div>' );

			FSPoster.ajax( 'share_post', { 'id': next.attr( 'data-id' ) }, function (result) {
				next.attr( 'data-status', '1' );
				if ( result[ 'result' ][ 'status' ] === 'ok' )
				{
					next.find( '.fsp-sharing-account-status' ).html( '<div class="fsp-status fsp-is-success fsp-tooltip" data-title="' + fsp__( 'shared' ) + '"><i class="fas fa-check"></i></div>' );
					next.find( '.fsp-sharing-account-link' ).html( '<a href="' + result[ 'result' ][ 'post_link' ] + '" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;' + fsp__( 'Post link' ) + '</a>' );
				}
				else
				{
					next.find( '.fsp-sharing-account-status' ).html( '<div class="fsp-status fsp-is-danger fsp-tooltip" data-title="' + result[ 'result' ][ 'error_msg' ] + '"><i class="fas fa-times"></i></div>' );
				}
				if ( $( ".fsp-sharing-account[data-status=0]" ).length > 0 )
				{
					$( ".fsp-sharing-account[data-status=0]:eq(0)" ).find( '.fsp-sharing-account-status' ).html( '<div class="fsp-status fsp-is-warning fsp-tooltip" data-title="Posting..."><i class="fas fa-clock"></i></div>' );
					FSwaitingTimer( $( ".fsp-sharing-account[data-status=0]:eq(0)" ) );

					setTimeout( sendNext, parseInt( $( ".fsp-sharing-account[data-status=0]:eq(0)" ).attr( 'data-interval' ) ) * 1000 );
				}
				else
				{
					$( ".process_text" ).text( fsp__( 'Posting finished.' ) );
					$( 'span.close' ).show();
				}

				reloadStats();
			}, true, function (result) {
				next.attr( 'data-status', '1' );
				next.find( '.fsp-sharing-account-status' ).html( '<div class="fsp-status fsp-is-danger fsp-tooltip" data-title="Failed"><i class="fas fa-times"></i></div>' );

				if ( $( ".fsp-sharing-account[data-status=0]" ).length > 0 )
				{
					$( ".fsp-sharing-account[data-status=0]:eq(0)" ).find( '.fsp-sharing-account-status' ).html( '<div class="fsp-status fsp-is-warning fsp-tooltip" data-title="Posting..."><i class="fas fa-clock"></i></div>' );
					FSwaitingTimer( $( ".fsp-sharing-account[data-status=0]:eq(0)" ) );

					setTimeout( sendNext, parseInt( $( ".fsp-sharing-account[data-status=0]:eq(0)" ).attr( 'data-interval' ) ) * 1000 );
				}
				else
				{
					$( ".process_text" ).text( fsp__( 'Posting finished.' ) );
					$( 'span.close' ).show();
				}

				reloadStats();
			} );

		}

		function FSwaitingTimer (e)
		{
			var time = e.data( 'interval' );
			time--;
			if ( time < 0 )
			{
				time = 0;
			}
			e.data( 'interval', time );
			e.find( '.fsp-sharing-account-status > .fsp-is-warning' ).text( time + ' sec.' );

			if ( time > 0 )
			{
				setTimeout( function () {
					FSwaitingTimer( e );
				}, 999 );
			}

		}

		reloadStats();
		sendNext();
	} );
} )( jQuery );
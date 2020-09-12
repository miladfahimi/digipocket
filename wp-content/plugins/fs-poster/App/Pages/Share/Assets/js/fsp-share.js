'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		let frame = wp.media( {
			title: fsp__( 'Select or Upload Media Of Your Chosen Persuasion' ),
			button: {
				text: fsp__( 'Use this media' )
			},
			multiple: false
		} );

		let saveID = FSPObject.saveID;

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();

			$( "#imageID" ).val( attachment.id );
			$( "#imageShow" ).removeClass( 'fsp-hide' ).data( 'id', attachment.id ).children( 'img' ).attr( 'src', attachment.url );
			$( '#wpMediaBtn, #fspShareURL' ).addClass( 'fsp-hide' );
		} );

		$( "#wpMediaBtn" ).click( function (event) {
			frame.open();
		} );

		$( ".saveBtn" ).click( function () {
			savePost( false, function () {
				FSPoster.toast( 'Saved successfully!', 'success' );
			} );
		} );

		$( "#closeImg" ).click( function () {
			$( "#imageShow" ).addClass( 'fsp-hide' ).children( 'img' ).attr( 'src', '' ).data( 'id', 0 );
			$( '#wpMediaBtn, #fspShareURL' ).removeClass( 'fsp-hide' );
		} );

		function savePost (tmp, callback)
		{
			var link = $( ".link_url" ).val().trim(),
				message = $( ".message_box" ).val().trim(),
				image = $( "#imageShow" ).data( 'id' );

			FSPoster.ajax( 'manual_share_save', {
				'id': saveID,
				'link': link,
				'message': message,
				'image': image,
				'tmp': tmp ? 1 : 0
			}, function (result) {
				saveID = result[ 'id' ];

				var url = window.location.href;
				if ( url.indexOf( 'post_id=' ) > -1 )
				{
					url = url.replace( /post_id\=([0-9]+)/, 'post_id=' + saveID, url );
				}
				else
				{
					url += (url.indexOf( '?' ) > -1 ? '&' : '?') + 'post_id=' + saveID;
				}

				window.history.pushState( "", "", url );

				if ( typeof callback === 'function' )
				{
					callback();
				}
			} );
		}

		$( ".scheduleBtn" ).click( function () {
			savePost( false, function () {
				var nodes = [];
				$( ".fsp-metabox-accounts input[name='share_on_nodes[]']" ).each( function () {
					var node_val = $( this ).val().split( ':' );

					nodes.push( node_val[ 1 ] + ':' + node_val[ 2 ] );
				} );

				FSPoster.loadModal( 'add_schedule', {
					'post_ids': saveID,
					'nodes': nodes,
					'not_include_js': 1
				} );
			} );
		} );

		$( ".shareNowBtn" ).click( function () {
			savePost( true, function () {
				var nodes = [];
				$( ".fsp-metabox-accounts input[name='share_on_nodes[]']" ).each( function () {
					nodes.push( $( this ).val() );
				} );

				if ( nodes.length == 0 )
				{
					FSPoster.toast( fsp__( 'No selected account!' ), 'warning' );
					return;
				}

				FSPoster.ajax( 'share_saved_post', {
					'post_id': saveID,
					'nodes': nodes,
					'background': 0
				}, function () {
					FSPoster.loadModal( 'share_feeds', { 'post_id': saveID }, true );
				} );
			} );
		} );

		$( ".delete_post_btn" ).click( function () {
			var tr = $( this ).closest( '.fsp-share-post' ),
				post_id = tr.data( 'id' );

			FSPoster.confirm( 'Are you sure you want to delete?', function () {
				FSPoster.ajax( 'manual_share_delete', { 'id': post_id }, function () {
					tr.fadeOut( 500, function () {
						if ( post_id === saveID )
						{
							window.location.href = '?page=fs-poster-share';
						}
						$( this ).remove();
					} );
				} );
			} );
		} );

		$( '.message_box' ).on( 'input, keyup', function () {
			$( '#fspShareCharCount' ).text( $( this ).val().length );
		} ).trigger( 'keyup' );
	} );
} )( jQuery );
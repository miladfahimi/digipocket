'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( ".fsp-modal-footer .share_btn" ).click( function () {
			var nodes = [];
			$( ".fsp-modal-body input[name='share_on_nodes[]']" ).each( function () {
				nodes.push( $( this ).val() );
			} );

			if ( nodes.length == 0 )
			{
				FSPoster.toast( fsp__( 'No selected account!' ), 'warning' );
				return;
			}

			var background = $( "#background_share_chckbx" ).is( ':checked' ) ? 1 : 0;

			var custom_messages = {};
			$( "#fspMetaboxCustomMessages textarea[name]" ).each( function () {
				custom_messages[ $( this ).attr( 'name' ).replace( 'fs_post_text_message_', '' ) ] = $( this ).val();
			} );

			FSPoster.ajax( 'share_saved_post', {
				'post_id': FSPObject.postID,
				'nodes': nodes,
				'background': background,
				'custom_messages': custom_messages
			}, function () {
				$( '[data-modal-close=true]' ).click();

				if ( background )
				{
					FSPoster.toast( fsp__( 'The post will be shared in the background!' ), 'info' );
				}
				else
				{
					FSPoster.loadModal( 'share_feeds', { 'post_id': FSPObject.postID }, true );
				}
			} );
		} );
	} );
} )( jQuery );
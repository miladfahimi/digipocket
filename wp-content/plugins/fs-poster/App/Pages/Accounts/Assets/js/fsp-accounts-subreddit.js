'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		let accountId = $( '#fspAccountID' ).val().trim();

		$( '#fspSubredditSelector' ).on( 'change', function () {
			let subreddit = $( '#fspSubredditSelector' ).val();

			if ( subreddit === '' )
			{
				subreddit = $( this ).select2( 'val' );
			}

			$( '#fspFlairSelector' ).empty();

			FSPoster.ajax( 'reddit_get_subreddt_flairs', { subreddit, account_id: accountId }, function ( result ) {
				if ( result[ 'flairs' ].length > 0 )
				{
					for ( let i in result[ 'flairs' ] )
					{ // todo: stack on var and then append and do it for all
						let flairInfo = result[ 'flairs' ][ i ];

						$( '#fspFlairSelector' ).append( `<option value="${ flairInfo[ 'id' ] }">${ flairInfo[ 'text' ] }</option>` );
					}

					$( '#fspFlairSelectorContainer' ).removeClass( 'fsp-hide' );
				}
				else
				{
					$( '#fspFlairSelectorContainer' ).addClass( 'fsp-hide' );
				}
			} );
		} );

		$( '#fspSubredditSelector' ).select2( {
			'placeholder': 'Search subreddit... ( type minimum 1 char. for search )',
			ajax: {
				url: ajaxurl,
				type: "POST",
				dataType: 'json',
				data: function (params) {
					return {
						account_id: accountId,
						action: 'search_subreddits',
						search: params.term
					};
				},
				processResults: function (data) {
					return {
						results: data.subreddits
					};
				}
			}
		} );


		$( '.fsp-modal-footer > #fspModalAddSubredditButton' ).on( 'click', function () {
			let subreddit		=	$(".fsp-modal-content #fspSubredditSelector").val(),
				flair			=	$(".fsp-modal-content #fspFlairSelector").val(),
				flairName		=	$(".fsp-modal-content #fspFlairSelector > :selected").text();

			if( subreddit == '' )
			{
				subreddit = $(".fsp-modal-content .subreddit_select").select2('val');
			}

			if( subreddit == '' )
			{
				FSPoster.alert('Please select subreddit!');
				return;
			}

			FSPoster.ajax( 'reddit_subreddit_save' , {'account_id': accountId, 'subreddit': subreddit, 'flair': flair, 'flair_name': flairName }, function(result)
			{
				accountAdded();
			});
		} );
	} );
} )( jQuery );
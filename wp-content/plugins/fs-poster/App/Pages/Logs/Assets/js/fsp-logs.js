'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		let currentPage = 1;

		doc.on( 'change', '#fspRowsSelector, #fspFilterSelector', function () {
			FSPLoadLogs( 1 );
		} ).on( 'change', '#fspLogsPageSelector', function () {
			let page = $( this ).val();

			FSPLoadLogs( page );
		} ).on( 'click', '.fsp-logs-page', function () {
			let _this = $( this );
			let page = _this.data( 'page' );

			if ( page === currentPage )
			{
				return;
			}
			else
			{
				currentPage = page;
			}

			FSPLoadLogs( page );
		} ).on( 'click', '#fspClearLogs', function () {
			FSPoster.confirm( fsp__( 'Are you sure you want to clear logs?'), function () {
				FSPoster.ajax( 'fs_clear_logs', {}, function () {
					window.location.reload();
				} );
			} );
		} ).on( 'click', '.fsp-logs-retry', function () {
			const _this = $( this );
			const feedId = _this.data( 'feed-id' );

			if ( feedId )
			{
				FSPoster.ajax(
					'get_feed_details',
					{
						'feed_id': feedId
					},
					function (data) {
						FSPoster.ajax(
							'share_saved_post',
							{
								'post_id': data.result.post_id,
								'nodes': data.result.nodes,
								'background': 0,
								'custom_messages': data.result.customMessages
							},
							function () {
								FSPoster.loadModal( 'share_feeds', { 'post_id': data.result.post_id }, true );
							}
						);
					}
				);
			}
		} );

		FSPLoadLogs( FSPObject.page );
	} );
} )( jQuery );

function FSPLoadLogs ( page ) {
	if ( typeof jQuery !== 'undefined' ) $ = jQuery;

	let rowsCount = $( '#fspRowsSelector' ).val();
	let filter = $( '#fspFilterSelector' ).val();
	let scheduleID = $( '#fspLogsScheduleID' ).val();

	FSPoster.ajax( 'report3_data', {
		page,
		'schedule_id': scheduleID,
		'rows_count': rowsCount,
		'filter_results': filter
	}, function ( result ) {
		let url = window.location.href;

		if ( url.indexOf( 'filter_by' ) > -1 )
		{
			url = url.replace( /filter_by=([a-zA-Z]+)/, `filter_by=${ filter }` );
		}
		else
		{
			url += `${ ( url.indexOf( '?' ) > -1 ? '&' : '?' ) }filter_by=${ filter }`
		}

		if ( url.indexOf( 'logs_page' ) > -1 )
		{
			url = url.replace( /logs_page=([0-9]+)/, `logs_page=${ page }` );
		}
		else
		{
			url += `${ ( url.indexOf( '?' ) > -1 ? '&' : '?' ) }logs_page=${ page }`;
		}

		window.history.pushState( '', '', url );

		$( '#fspLogs' ).empty();

		$( '#fspLogsCount' ).text( result[ 'total' ] );

		for ( let i in result[ 'data' ] )
		{
			let statusBtn;

			if ( result[ 'data' ][ i ][ 'is_sended' ] === '1' && result[ 'data' ][ i ][ 'status' ] === 'ok' )
			{
				statusBtn = `<div class="fsp-status fsp-is-success"><i class="fas fa-check"></i>${ fsp__( 'SUCCESS' ) }</div>`;
			}
			else if ( result[ 'data' ][ i ][ 'is_sended' ] === '1' && result[ 'data' ][ i ][ 'status' ] === 'error' )
			{
				statusBtn = `<div class="fsp-status fsp-is-danger fsp-tooltip" data-title="${ result[ 'data' ][ i ][ 'error_msg' ] }"><i class="fas fa-times"></i>${ fsp__( 'ERROR' ) }</div>
						<button class="fsp-button fsp-is-warning fsp-logs-retry" data-feed-id="${ result[ 'data' ][ i ][ 'id' ] }"><i class="fas fa-sync"></i>${ fsp__( 'RETRY' ) }</button>`;
			}
			else
			{
				statusBtn = `<div class="fsp-status fsp-is-warning"><i class="fas fa-check"></i>${ fsp__( 'NOT SENT' ) }</div>`;
			}

			let driverIcon = result[ 'data' ][ i ][ 'icon' ];

			$( '#fspLogs' ).append( `
				<div class="fsp-log">
					<div class="fsp-log-image">
						<img src="${ result[ 'data' ][ i ][ 'cover' ] }" onerror="FSPoster.no_photo( this );">
					</div>
					<div class="fsp-log-title">
						<div class="fsp-log-title-text">
							${ result[ 'data' ][ i ][ 'name' ] }
							<a target="_blank" href="${ result[ 'data' ][ i ][ 'profile_link' ] }" class="fsp-tooltip" data-title="${ fsp__( 'Profile link' ) }">
								<i class="fas fa-external-link-alt"></i>	
							</a>
						</div>
						<div class="fsp-log-title-subtext">
							${ result[ 'data' ][ i ][ 'date' ] }
							<a target="_blank" href="${ fspConfig.siteURL }/?p=${ result[ 'data' ][ i ][ 'wp_post_id' ] }" class="fsp-tooltip" data-title="${ fsp__( 'Post link' ) }">
								<i class="fas fa-external-link-alt"></i>	
							</a>
						</div>
					</div>
					<div class="fsp-log-title fsp-is-second">
						<div class="fsp-log-title-link">
							<a target="_blank" href="${ result['data'][i]['post_link'] }">
								<i class="fas fa-external-link-alt"></i>
								${ fsp__( 'Publication link' ) }
							</a>
						</div>
						<div class="fsp-log-title-subtext fsp-log-title-sublink">
							<i class="fab fa-${ driverIcon }"></i>&nbsp;${ result['data'][i]['driver'][0].toUpperCase() + result['data'][i]['driver'].substring(1) }&nbsp;>&nbsp;${ result['data'][i]['node_type'] + ( result['data'][i]['feed_type'] !== '' ? ' > ' + result['data'][i]['feed_type'] : '' ) }
						</div>
					</div>
					<div class="fsp-log-status-container">
						${ statusBtn }
					</div>
					<div class="fsp-log-stats">
						${ result[ 'data' ][ i ][ 'driver' ] === 'linkedin' || result[ 'data' ][ i ][ 'driver' ] === 'reddit' || result[ 'data' ][ i ][ 'driver' ] === 'tumblr' || result[ 'data' ][ i ][ 'driver' ] === 'google_b' || result[ 'data' ][ i ][ 'driver' ] === 'telegram' || result[ 'data' ][ i ][ 'driver' ] === 'medium' || result[ 'data' ][ i ][ 'driver' ] === 'wordpress' ? '' : `
							<div class="fsp-log-stat">
								<i class="far fa-eye"></i> ${ fsp__( 'Hits' ) }: <span class="fsp-log-stat-value">${ result[ 'data' ][ i ][ 'hits' ] }</span>
							</div>
							<div class="fsp-log-stat">
								<i class="far fa-comments"></i> ${ fsp__( 'Comments' ) }: <span class="fsp-log-stat-value">${ typeof result[ 'data' ][ i ][ 'insights' ][ 'comments' ] != 'undefined' ? result[ 'data' ][ i ][ 'insights' ][ 'comments' ] : 0 }</span>
							</div>
							<div class="fsp-log-stat">
								<i class="far fa-thumbs-up"></i> ${ fsp__( 'Likes' ) }: <span class="fsp-log-stat-value">${ result[ 'data' ][ i ][ 'insights' ][ 'like' ] }</span>
							</div>
							<div class="fsp-log-stat">
								<i class="fas fa-share-alt"></i> ${ fsp__( 'Shares' ) }: <span class="fsp-log-stat-value">${ typeof result[ 'data' ][ i ][ 'insights' ][ 'shares' ] != 'undefined' ? result[ 'data' ][ i ][ 'insights' ][ 'shares' ] : 0 }</span>
							</div>
						` }
					</div>
				</div>
			` );
		}

		let logsPages = '';
		let j = 0;

		result[ 'pages' ][ 'page_number' ].forEach( function ( i )
		{
			logsPages += `<button class="fsp-button fsp-is-${ i === parseInt( result[ 'pages' ][ 'current_page' ] ) ? 'danger' : 'white' } fsp-logs-page" data-page="${ i }">${ i }</button>`;

			if ( typeof result[ 'pages' ][ 'page_number' ][ j + 1 ] !== 'undefined' && result[ 'pages' ][ 'page_number' ][ j + 1 ] !== i + 1 )
			{
				logsPages += '<button class="fsp-button fsp-is-white" disabled>...</button>';
			}

			j++;
		} );

		logsPages += `<select id="fspLogsPageSelector" class="fsp-form-select">`;

		for ( let i = 1; i <= result[ 'pages' ][ 'count' ]; i++ )
		{
			logsPages += `<option value="${ i }" ${ i === parseInt( result[ 'pages' ][ 'current_page' ] ) ? 'selected' : '' }>${ i }</option>`;
		}

		logsPages += `</select>`;

		$( '#fspLogsPages' ).html( logsPages );
	} );
}
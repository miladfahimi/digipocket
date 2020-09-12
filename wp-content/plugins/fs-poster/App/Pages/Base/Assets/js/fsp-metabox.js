'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		doc.on( 'click', '.fsp-metabox-account-remove', function () {
			$( this ).parent().slideUp( 200, function () {
				$( this ).remove();
			} );
		} ).on( 'click', '.fsp-metabox-modal-accounts > .fsp-metabox-account:not(.fsp-is-disabled)', function () {
			let _this = $( this );
			let dataID = _this.data( 'id' );
			let cover = _this.find( '.fsp-metabox-account-image > img' ).attr( 'src' );
			let name = _this.find( '.fsp-metabox-account-text' ).text().trim();
			let link = _this.find( '.fsp-metabox-account-text' ).prop( 'href' ).trim();

			FSPAddToList( dataID, cover, name, link );

			_this.slideUp( 200, function () {
				$( this ).remove();
			} );
		} ).on( 'keyup', '.fsp-search-account', function () {
			let val = $( this ).val().trim().toLowerCase();

			if ( val !== '' )
			{
				$( '.fsp-metabox-modal-accounts > .fsp-metabox-account' ).filter( function () {
					let _this = $( this );

					if ( _this.text().toLowerCase().indexOf( val ) > -1 )
					{
						_this.slideDown( 200 );
					}
					else
					{
						_this.slideUp( 200 );
					}
				} );
			}
			else
			{
				$( '.fsp-metabox-modal-accounts > .fsp-metabox-account' ).slideDown( 200 );
			}
		} ).on( 'click', '.fsp-metabox-clear', function () {
			FSPoster.confirm( fsp__( 'Do you want to empty share list?' ), function () {
				$( '.fsp-metabox-account' ).slideUp( 200, function () {
					$( this ).remove();
				} );
			} );
		} ).on( 'click', '.fsp-metabox-add', function () {
			let ignore = [];

			$( '.fsp-metabox-account > input[name="share_on_nodes[]"]' ).each( function () {
				ignore.push( $( this ).val() );
			} );

			FSPoster.loadModal( 'add_node_to_list', { dont_show: ignore } );
		} ).on( 'change', '#fspMetaboxShare', function () {
			if ( $( this ).is( ':checked' ) )
			{
				$( '#fspMetaboxShareContainer' ).slideDown( 200 );
			}
			else
			{
				$( '#fspMetaboxShareContainer' ).slideUp( 200 );
			}
		} ).on( 'click', '.fsp-metabox-tab', function () {
			let _this = $( this );

			$( '.fsp-metabox-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
			_this.addClass( 'fsp-is-active' );

			let driver = _this.data( 'tab' );

			if ( driver == 'all' )
			{
				$( '.fsp-metabox-accounts > .fsp-metabox-account' ).slideDown( 200 );
				$( '#fspMetaboxCustomMessages > div' ).slideUp( 200 );
			}
			else
			{
				$( `.fsp-metabox-accounts > .fsp-metabox-account[data-driver!="${ driver }"]` ).slideUp( 200 );
				$( `.fsp-metabox-accounts > .fsp-metabox-account[data-driver="${ driver }"]` ).slideDown( 200 );
				$( `#fspMetaboxCustomMessages > div[data-driver!="${ driver }"]` ).slideUp( 200 );
				$( `#fspMetaboxCustomMessages > div[data-driver="${ driver }"]` ).slideDown( 200 );
			}
		} ).on( 'click', '.fsp-metabox-custom-message-label', function () {
			$( this ).next().slideToggle( 200 );
		} );

		$( '.fsp-metabox-tab' ).eq( 0 ).click();
		$( '.fsp-metabox-custom-message-label' ).click();
		$( '#fspMetaboxShare' ).trigger( 'change' );
	} );
} )( jQuery );

function FSPAddToList ( dataID, cover, name, link )
{
	if ( typeof jQuery !== 'undefined' ) $ = jQuery;

	dataID = dataID.split( ':' );

	let tab = dataID[ 0 ];
	let nodeType = dataID[ 1 ];
	let tabName = tab.charAt( 0 ).toUpperCase() + tab.slice( 1 );
	let icon;

	if ( tab === 'ok' )
	{
		icon = 'fab fa-odnoklassniki';
	}
	else if ( tab === 'google_b' )
	{
		icon = 'fab fa-google';
	}
	else
	{
		icon = `fab fa-${ tab }`;
	}

	$( `<div data-driver="${ tab }" class="fsp-metabox-account">
		<input type="hidden" name="share_on_nodes[]" value="${ dataID.join( ':' ) }">
		<div class="fsp-metabox-account-image">
			<img src="${ cover }" onerror="FSPoster.no_photo( this );">
		</div>
		<div class="fsp-metabox-account-label">
			<a href="${ link }" class="fsp-metabox-account-text">
				${ name }
			</a>
			<div class="fsp-metabox-account-subtext">
				<i class="${ icon }"></i>&nbsp;${ tabName }&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;${ nodeType }
			</div>
		</div>
		<div class="fsp-metabox-account-remove">
			<i class="fas fa-times"></i>
		</div>
	</div>` ).hide().appendTo( '.fsp-metabox-accounts' );

	FSPoster.toast( fsp__( 'Added to list!' ), 'success' );

	$( '.fsp-metabox-tab.fsp-is-active' ).click();
}
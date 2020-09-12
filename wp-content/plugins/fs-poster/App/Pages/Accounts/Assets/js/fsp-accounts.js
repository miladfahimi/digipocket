'use strict';

( function ( $ ) {
    let doc = $( document );

    doc.ready( function () {
        let currentComponent;

        doc.on( 'click', '.fsp-tab[data-component]', function () {
            let _this = $( this );
            currentComponent = _this.data( 'component' );

            if ( currentComponent )
            {
                $( '.fsp-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
                _this.addClass( 'fsp-is-active' );

                if ( currentComponent === 'telegram' )
                {
                    $( '.fsp-accounts-add-button > span' ).text( fsp__( 'ADD A BOT' ) );
                }
                else if ( currentComponent === 'wordpress' )
                {
                    $( '.fsp-accounts-add-button > span' ).text( fsp__( 'ADD A WEBSITE' ) );
                }
                else
                {
                    $( '.fsp-accounts-add-button > span' ).text( fsp__( 'ADD AN ACCOUNT' ) );
                }

                FSPoster.ajax( 'get_accounts', { name: currentComponent }, function ( res ) {
                    $( '#fspComponent' ).html( FSPoster.htmlspecialchars_decode( res[ 'html' ] ) );

                    let fspAccountsCount = $( '#fspAccountsCount' ).text();
                    let loadModal = FSPObject.modalURL;

                    _this.find( '.fsp-tab-all' ).text( fspAccountsCount );
                    $( '.fsp-accounts-add-button' ).attr( 'data-load-modal', loadModal );

                    if ( $( '.fsp-account-checkbox > .fsp-is-checked, .fsp-account-checkbox > .fsp-is-checked-conditionally' ).length > 0 )
                    {
                        $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).addClass( 'fsp-has-active-accounts' );
                    }
                    else
                    {
                        $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).removeClass( 'fsp-has-active-accounts' );
                    }
                } );

                window.history.pushState( {}, '', `?page=fs-poster-accounts&tab=${currentComponent}` );
            }
        } );

        $( '.fsp-tab.fsp-is-active' ).click();

        let component = $( '#fspComponent' );

        component.on( 'click', '.fsp-account-more', function () {
            let _this = $( this );
            let accountDiv = _this.parent().parent();
            let id = accountDiv.data( 'id' );
            let type = accountDiv.data( 'type' ) ? accountDiv.data( 'type' ) : 'account';

            if ( accountDiv.find( '.fsp-account-is-public' ).hasClass( 'fsp-hide' ) )
            {
                $( '#fspMoreMenu > [data-type="public"]' ).removeClass( 'fsp-hide' );
                $( '#fspMoreMenu > [data-type="private"]' ).addClass( 'fsp-hide' );
            }
            else
            {
                $( '#fspMoreMenu > [data-type="public"]' ).addClass( 'fsp-hide' );
                $( '#fspMoreMenu > [data-type="private"]' ).removeClass( 'fsp-hide' );
            }

            let top = _this.offset().top + 25 - $( window ).scrollTop();
            let left = _this.offset().left - ( $( '#fspMoreMenu' ).width() ) + 10;

            $( '#fspMoreMenu' ).data( 'id', id ).data( 'type', type ).css( { top: top, left: left } ).show();
        } ).on( 'click', '.fsp-account-checkbox', function () {
            let _this = $( this );
            let accountDiv = _this.parent().parent();
            let id = accountDiv.data( 'id' );
            let type = accountDiv.data( 'type' ) ? _this.parent().parent().data( 'type' ) : 'account';

            let top = _this.offset().top + 25 - $( window ).scrollTop();
            let left = _this.offset().left - ( $( '#fspActivateMenu' ).width() ) + 10;

            $( '#fspActivateMenu' ).data( 'id', id ).data( 'type', type ).css( { top: top, left: left } ).show();
        } ).on( 'click', '.fsp-account-caret', function () {
            let _this = $( this );
            let nodesDiv = _this.parent().parent().parent().find( '.fsp-account-nodes-container' );

            if ( nodesDiv.css( 'display' ) === 'none' )
            {
                nodesDiv.slideDown();
                _this.addClass( 'fsp-is-rotated' );
            }
            else
            {
                nodesDiv.slideUp();
                _this.removeClass( 'fsp-is-rotated' );
            }
        } );

        doc.on( 'click', function ( e ) {
            if ( ! $( e.target ).is( '.fsp-account-checkbox, .fsp-account-checkbox > i' ) )
            {
                $( '#fspActivateMenu' ).hide();
            }

            if ( ! $( e.target ).is( '.fsp-account-more, .fsp-account-more > i' ) )
            {
                $( '#fspMoreMenu' ).hide();
            }
        } );

        $( '#fspActivateMenu > #fspActivateConditionally' ).on( 'click', function () {
            let _this = $( this );
            let menuDiv = _this.parent();
            let id = menuDiv.data( 'id' );
            let type = menuDiv.data( 'type' ) === 'community' ? 'node' : 'account';

            FSPoster.loadModal( 'activate_with_condition', { id, type } );

            if ( $( '.fsp-account-checkbox > .fsp-is-checked, .fsp-account-checkbox > .fsp-is-checked-conditionally' ).length > 0 )
            {
                $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).addClass( 'fsp-has-active-accounts' );
            }
            else
            {
                $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).removeClass( 'fsp-has-active-accounts' );
            }
        } );

        $( '#fspActivateMenu > #fspActivate' ).on( 'click', function () {
            let _this = $( this );
            let menuDiv = _this.parent();
            let id = menuDiv.data( 'id' );
            let type = menuDiv.data( 'type' );
            let ajaxType = type === 'community' ? 'settings_node_activity_change' : 'account_activity_change';

            FSPoster.ajax( ajaxType, { id, checked: 1 } );

            $( `.fsp-account-item[data-id=${id}][data-type="${ type }"] .fsp-account-checkbox > i` ).removeClass( 'far' ).addClass( 'fas fsp-is-checked' );

            if ( $( '.fsp-account-checkbox > .fsp-is-checked, .fsp-account-checkbox > .fsp-is-checked-conditionally' ).length > 0 )
            {
                $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).addClass( 'fsp-has-active-accounts' );
            }
            else
            {
                $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).removeClass( 'fsp-has-active-accounts' );
            }
        } );

        $( '#fspActivateMenu > #fspDeactivate' ).on( 'click', function () {
            let _this = $( this );
            let menuDiv = _this.parent();
            let id = menuDiv.data( 'id' );
            let type = menuDiv.data( 'type' );
            let ajaxAction = type === 'community' ? 'settings_node_activity_change' : 'account_activity_change';

            FSPoster.ajax( ajaxAction, { id, checked: 0 } );

            $( `.fsp-account-item[data-id=${id}][data-type="${ type }"] .fsp-account-checkbox > i` ).removeClass( 'fas fsp-is-checked fsp-is-checked-conditionally' ).addClass( 'far' );

            if ( $( '.fsp-account-checkbox > .fsp-is-checked, .fsp-account-checkbox > .fsp-is-checked-conditionally' ).length > 0 )
            {
                $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).addClass( 'fsp-has-active-accounts' );
            }
            else
            {
                $( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).removeClass( 'fsp-has-active-accounts' );
            }
        } );

        $( '#fspMoreMenu > .fsp-make-public' ).on( 'click', function () {
            let _this = $( this );
            let menuDiv = _this.parent();
            let id = menuDiv.data( 'id' );
            let type = menuDiv.data( 'type' );
            let accountDiv = $( `.fsp-account-item[data-id=${id}][data-type="${ type }"]` );
            let isChecked = ! accountDiv.find('.fsp-account-is-public').hasClass( 'fsp-hide' );
            let ajaxAction = type === 'community' ? 'settings_node_make_public' : 'make_account_public';

            FSPoster.ajax( ajaxAction, { id, checked: isChecked ? 0 : 1 }, function ()
            {
                if ( isChecked )
                {
                    accountDiv.find('.fsp-account-is-public').addClass( 'fsp-hide' );
                }
                else
                {
                    accountDiv.find('.fsp-account-is-public').removeClass( 'fsp-hide' );
                }
            } );
        } );

        $( '#fspMoreMenu > #fspDelete' ).on( 'click', function () {
            let _this = $( this );
            let menuDiv = _this.parent();
            let id = menuDiv.data( 'id' );
            let type = menuDiv.data( 'type' );
            let accountDiv = $( `.fsp-account-item[data-id=${id}][data-type="${ type }"]` );

            FSPoster.confirm( fsp__( 'Are you sure you want to delete?' ), function () {
                let ajaxAction = type === 'community' ? 'settings_node_delete' : 'delete_account';

                FSPoster.ajax( ajaxAction, { id }, function () {
                    if ( type === 'community' )
                    {
                        $( '.fsp-tab.fsp-is-active' ).click();
                    }
                    else
                    {
                        $( '.fsp-tab.fsp-is-active' ).click();
                    }
                } );
            } );
        } );
        
        // modal
        doc.on( 'click', '.fsp-modal-option', function () {
            let _this = $( this );
            let step = _this.data( 'step' );

            $( '.fsp-modal-option.fsp-is-selected' ).removeClass( 'fsp-is-selected' );
            _this.addClass( 'fsp-is-selected' );

            if ( step )
            {
                if ( $( `#fspModalStep_${ step }` ).length )
                {
                    $( '.fsp-modal-step' ).addClass( 'fsp-hide' );
                    $( `#fspModalStep_${ step }` ).removeClass( 'fsp-hide' );
                }
            }
        } );

        doc.on( 'change', '#fspUseProxy', function () {
            let checked = ! ( $( this ).is( ':checked' ) );

            $( '#fspProxy' ).val( '' );
            $( '#fspProxyContainer' ).toggleClass( 'fsp-hide', checked );
        } );
    } );
} )( jQuery );

function accountAdded ()
{
    if ( typeof jQuery !== 'undefined' ) $ = jQuery;

    let modalBody = $( '.fsp-modal-body' );

    if ( modalBody.length )
    {
        $( '.fsp-modal-footer' ).remove();

        modalBody.html( `<div class="fsp-modal-succeed"><div class="fsp-modal-succeed-image"><img src="${ FSPoster.asset( 'Base', 'img/success.svg' ) }"></div><div class="fsp-modal-succeed-text">${ fsp__( 'Account has been added successfully!' ) }</div><div class="fsp-modal-succeed-button"><button class="fsp-button" data-modal-close="true">${ fsp__( 'CLOSE' ) }</button></div></div>` );

        $( '.fsp-tab.fsp-is-active' ).click();
    }
}
'use strict';

var FSPoster;

( function ( $ ) {
    let doc = $( document );

    doc.ready( function () {
        FSPoster = {
            modalsCount: 0,
            toastTimer: 0,

            options: {
                'templates': {
                    'modal': '<div class="fsp-modal {centered}"><div class="fsp-modal-content {fullscreen}">{body}</div></div>',
                    'alert': `<div class="fsp-alert"><div class="fsp-alert-content"><div class="fsp-alert-icon"><i class="fas fa-times"></i></div><div class="fsp-alert-text">{text}</div><div class="fsp-alert-buttons"><button id="fspCloseAlert" class="fsp-button">${ fsp__( 'OK' ) }</button></div></div></div>`,
                    'toast': '<div id="fspToast" class="fsp-toast"><div class="fsp-toast-info"><div class="fsp-toast-icon fsp-is-{type}"></div><div class="fsp-toast-text"><div class="fsp-toast-status">{status}</div><div class="fsp-toast-message">{message}</div></div></div><div id="fspCloseToast" class="fsp-toast-close"></div></div>',
                    'confirm': `<div class="fsp-confirm"><div class="fsp-confirm-content"><div class="fsp-confirm-icon"><i class="{icon}"></i></div><div class="fsp-confirm-text">{text}</div><div class="fsp-alert-buttons"><button id="fspCloseConfirm" class="fsp-button fsp-is-gray">${ fsp__( 'Cancel' ) }</button><button id="fspConfirmButton" class="fsp-button">{confirmBtn}</button></div></div></div>`
                }
            },

            // rewrited
            confirm: function ( text, fnOnConfirm, icon, confirmBtn, fnOnCancel )
            {
                let templateHTML = this.options.templates.confirm;

                icon = icon || 'far fa-trash-alt';
                confirmBtn = confirmBtn || fsp__( 'DELETE' );

                templateHTML = templateHTML.replace( '{text}', text ).replace( '{icon}', icon ).replace( '{confirmBtn}', confirmBtn );

                $( 'body' ).append( templateHTML );
                $( '.fsp-confirm-content #fspConfirmButton' ).on( 'click', function () {
                    fnOnConfirm();

                    $( '#fspCloseConfirm' ).click();
                } );

                $( '.fsp-confirm-content #fspCloseConfirm' ).on( 'click', function () {
                    if ( typeof fnOnCancel === 'function' )
                    {
                        fnOnCancel();
                    }

                    $( '.fsp-confirm' ).remove();
                } );
            },

            modalHide: function ( modal ) {
                modal.trigger( 'modal-hide' );
            },

            modal: function ( body, centered = false, fullscreen = false ) {
                let _this = this;
                body = typeof body === 'function' ? body() : body;
                let modalHTML;

                if ( ! centered )
                {
                    modalHTML = _this.options.templates.modal.replace( '{body}', body ).replace( '{centered}', '' );
                }
                else
                {
                    modalHTML = _this.options.templates.modal.replace( '{body}', body ).replace( '{centered}', 'fsp-is-centered' );
                }

                if ( fullscreen )
                {
                    modalHTML = modalHTML.replace( '{fullscreen}', 'fsp-is-fullscreen' );
                }
                else
                {
                    modalHTML = modalHTML.replace( '{fullscreen}', '' );
                }

                let modal = _this.parseHTML( modalHTML );
                let modalID = `fspModal_${ _this.modalsCount++ }`;

                modal.firstChild.id = modalID;

                $( 'body' ).css( 'overflow', 'hidden' ).append( modal );

                $( `#${ modalID }` ).on( 'modal-hide', function () {
                    $( `#${ modalID } .fsp-modal-content` ).removeClass( 'fsp-animate' ).addClass( 'fsp-animate-end' );
                    $( `#${ modalID }` ).fadeOut( 500, function () {
                        $( this ).remove();
                    } );

                    $( 'body' ).css( 'overflow', 'auto' );
                } );

                return [
                    modalID,
                    _this.modalsCount,
                    `#${modalID}`
                ];
            },

            loadModal: function ( url, postParams, centered = false, fullscreen = false ) {
                let _this = this;
                let newModal = _this.modal( '', centered, fullscreen );

                postParams[ 'action' ] = `popup_${url}`;
                postParams = typeof postParams !== 'undefined' ? postParams : {};
                postParams[ '_mn' ] = newModal[ 1 ];
                postParams[ '_token' ] = $( 'meta[name=csrf-token]' ).attr( 'content' );

                _this.loading( true );

                $.ajax( {
                    url: ajaxurl,
                    method: 'POST',
                    data: postParams,
                    success: function ( result ) {
                        _this.loading( false );

                        result = _this.jsonResult( result );

                        if ( result[ 'status' ] === 'ok' && typeof result[ 'html' ] !== 'undefined' )
                        {
                            $( `#${newModal[0]}` ).find( '.fsp-modal-content' ).html( `${ _this.htmlspecialchars_decode( result[ 'html' ] ) }` );

                            $( '.fsp-modal-content' ).addClass( 'fsp-animate' );
                        }
                        else if ( result[ 'status' ] === 'error' ) {
                            _this.alert( typeof result[ 'error_msg' ] === 'undefined' ? 'Error!' : result[ 'error_msg' ] );

                            _this.modalHide( $( `#${newModal[0]}` ) );
                        }
                    },
                    error: function ( jqXHR, exception ) {
                        _this.loading( false );

                        let msg = '';

                        if ( jqXHR.status === 0 )
                        {
                            msg = fsp__( 'Not connect.' );
                        }
                        else if ( jqXHR.status === 404 )
                        {
                            msg = fsp__( 'Requested page not found. [404]' );
                        }
                        else if ( jqXHR.status === 500 )
                        {
                            msg = fsp__( 'Internal Server Error [500].' );
                        }
                        else if ( exception === 'parsererror' )
                        {
                            msg = fsp__( 'Requested JSON parse failed.' );
                        }
                        else if ( exception === 'timeout' )
                        {
                            msg = fsp__( 'Time out error.' );
                        }
                        else if ( expection === 'abort' )
                        {
                            msg = fsp__( 'Ajax request aborted.' );
                        }
                        else
                        {
                            msg = fsp__( 'Uncaught Error.' );
                        }

                        _this.alert( msg, 'warning' );
                    }
                } );
            },

            parseHTML: function (html) {
                let range = document.createRange();
				return range.createContextualFragment( html );
            },

            loading: function ( isVisible ) {
                if ( typeof  isVisible === 'undefined' || isVisible )
                {
                    if ( $( '#fspLoaderContainer' ).length )
                    {
                        $( '#fspLoaderContainer' ).remove();
                    }

                    $( 'body' ).append( '<div id="fspLoaderContainer" class="fsp-loader-container"></div>' );
                }
                else
                {
                    $( '#fspLoaderContainer' ).fadeOut( 200, function () {
                        $( this ).remove();
                    } );
                }
            },

            jsonResult: function (json) {
                if ( typeof json === 'object' )
                {
                    return json;
                }

                var result;
                try
                {
                    result = JSON.parse( json );
                } catch ( e )
                {
                    result = {
                        'status': 'parse-error',
                        'error': e
                    };
                }
                return result;
            },

            htmlspecialchars_decode: function (string, quote_style) {
                var optTemp = 0,
                    i = 0,
                    noquotes = false;
                if ( typeof quote_style === 'undefined' )
                {
                    quote_style = 2;
                }
                string = string.toString().replace( /&lt;/g, '<' ).replace( /&gt;/g, '>' );
                var OPTS = {
                    'ENT_NOQUOTES': 0,
                    'ENT_HTML_QUOTE_SINGLE': 1,
                    'ENT_HTML_QUOTE_DOUBLE': 2,
                    'ENT_COMPAT': 2,
                    'ENT_QUOTES': 3,
                    'ENT_IGNORE': 4
                };
                if ( quote_style === 0 )
                {
                    noquotes = true;
                }
                if ( typeof quote_style !== 'number' )
                {
                    quote_style = [].concat( quote_style );
                    for ( i = 0; i < quote_style.length; i++ )
                    {
                        if ( OPTS[ quote_style[ i ] ] === 0 )
                        {
                            noquotes = true;
                        }
                        else if ( OPTS[ quote_style[ i ] ] )
                        {
                            optTemp = optTemp | OPTS[ quote_style[ i ] ];
                        }
                    }
                    quote_style = optTemp;
                }
                if ( quote_style & OPTS.ENT_HTML_QUOTE_SINGLE )
                {
                    string = string.replace( /&#0*39;/g, "'" );
                }
                if ( ! noquotes )
                {
                    string = string.replace( /&quot;/g, '"' );
                }
                string = string.replace( /&amp;/g, '&' );
                return string;
            },

            htmlspecialchars: function (string, quote_style, charset, double_encode) {
                var optTemp = 0,
                    i = 0,
                    noquotes = false;
                if ( typeof quote_style === 'undefined' || quote_style === null )
                {
                    quote_style = 2;
                }
                string = typeof string != 'string' ? '' : string;

                string = string.toString();
                if ( double_encode !== false )
                {
                    string = string.replace( /&/g, '&amp;' );
                }
                string = string.replace( /</g, '&lt;' ).replace( />/g, '&gt;' );
                var OPTS = {
                    'ENT_NOQUOTES': 0,
                    'ENT_HTML_QUOTE_SINGLE': 1,
                    'ENT_HTML_QUOTE_DOUBLE': 2,
                    'ENT_COMPAT': 2,
                    'ENT_QUOTES': 3,
                    'ENT_IGNORE': 4
                };
                if ( quote_style === 0 )
                {
                    noquotes = true;
                }
                if ( typeof quote_style !== 'number' )
                {
                    quote_style = [].concat( quote_style );
                    for ( i = 0; i < quote_style.length; i++ )
                    {
                        if ( OPTS[ quote_style[ i ] ] === 0 )
                        {
                            noquotes = true;
                        }
                        else if ( OPTS[ quote_style[ i ] ] )
                        {
                            optTemp = optTemp | OPTS[ quote_style[ i ] ];
                        }
                    }
                    quote_style = optTemp;
                }
                if ( quote_style & OPTS.ENT_HTML_QUOTE_SINGLE )
                {
                    string = string.replace( /'/g, '&#039;' );
                }
                if ( ! noquotes )
                {
                    string = string.replace( /"/g, '&quot;' );
                }
                return string;
            },

            alert: function ( message )
            {
                let templateHTML = this.options.templates.alert.replace( '{text}', message );

                $( 'body' ).append( templateHTML );
            },

            ajaxResultCheck: function ( res ) {
                if ( typeof $ === 'undefined' )
                {
                    var $ = typeof jQuery === 'undefined' ? null : jQuery;
                }

                if ( typeof res != 'object' )
                {
                    try
                    {
                        res = JSON.parse( res );
                    } catch ( e )
                    {
                        this.alert( 'Error!' );
                        return false;
                    }
                }

                if ( typeof res[ 'status' ] === 'undefined' )
                {
                    this.alert( 'Error!' );
                    return false;
                }

                if ( res[ 'status' ] === 'error' )
                {
                    this.alert( typeof res[ 'error_msg' ] === 'undefined' ? 'Error!' : res[ 'error_msg' ] );
                    return false;
                }

                if ( res[ 'status' ] === 'ok' )
                {
                    return true;
                }

                // else

                this.alert( 'Error!' );
                return false;
            },

            ajax: function (action, params, func, noLoading, funcOnErr, async = true ) {
                if ( typeof $ === 'undefined' )
                {
                    var $ = typeof jQuery === 'undefined' ? null : jQuery;
                }
                noLoading = typeof noLoading === 'undefined' ? false : noLoading;

                var t = this;
                if ( ! noLoading )
                {
                    t.loading( true );
                }

                if ( params instanceof FormData )
                {
                    params.append( 'action', action );
                }
                else
                {
                    params[ 'action' ] = action;
                }

                let ajaxObject = {
                    url: ajaxurl,
                    method: 'POST',
                    data: params,
                    async: typeof async === 'boolean' ? async : true,
                    success: function ( result )
                    {
                        if ( ! noLoading )
                        {
                            t.loading( false );
                        }

                        if ( FSPoster.ajaxResultCheck( result ) )
                        {
                            try
                            {
                                result = JSON.parse( result );
                            } catch ( e )
                            {

                            }

                            if ( typeof func === 'function' )
                            {
                                func( result );
                            }
                        }
                        else
                        {
                            try
                            {
                                result = JSON.parse( result );
                            } catch ( e )
                            {

                            }

                            if ( typeof funcOnErr === 'function' )
                            {
                                funcOnErr( funcOnErr );
                            }
                        }
                    },
                    error: function ( jqXHR )
                    {
                        t.loading( false );

                        FSPoster.toast( jqXHR.status + fsp__( ' error!' ), 'warning' );
                    }
                };

                if ( params instanceof FormData )
                {
                    ajaxObject[ 'processData' ] = false;
                    ajaxObject[ 'contentType' ] = false;
                }

                $.ajax( ajaxObject );
            },

            zeroPad: function (n) {
                return n > 9 ? n : '0' + n;
            },

            spintax: function (text) {
                var matches, options, random;

                var regEx = new RegExp( /{([^{}]+?)}/ );

                while ( (matches = regEx.exec( text )) !== null )
                {
                    options = matches[ 1 ].split( "|" );
                    random = Math.floor( Math.random() * options.length );
                    text = text.replace( matches[ 0 ], options[ random ] );
                }

                return text;
            },

            toast: function ( message, type )
            {
                $( '#fspToast' ).remove();

                if ( this.toastTimer )
                {
                    clearTimeout( this.toastTimer );
                }

                let toastHTML = this.options.templates.toast.replace( '{message}', message ).replace( '{type}', type ).replace( '{status}', fsp__( type ) );

                $( 'body' ).append( toastHTML );

                this.toastTimer = setTimeout( function () {
                    $( '#fspToast' ).fadeOut( 200, function () {
                        $( this ).remove();
                    } );
                }, 4000 );
            },

            serialize: function (data) {
                if ( typeof $ === 'undefined' )
                {
                    var $ = typeof jQuery === 'undefined' ? null : jQuery;
                }
                var res = {};
                data = data.serializeArray();

                $.each( data, function () {
                    if ( res[ this.name ] )
                    {
                        if ( ! res[ this.name ].push )
                        {
                            res[ this.name ] = [ res[ this.name ] ];
                        }

                        res[ this.name ].push( this.value || '' );
                    }
                    else
                    {
                        res[ this.name ] = this.value || '';
                    }
                } );
                return res;
            },

            asset: function ( page, path ) {
                return `${ fspConfig.pagesURL }${ page }/Assets/${ path }`;
            },

            no_photo: function ( img ) {
                img.src = FSPoster.asset( 'Base', 'img/no-photo.png' );
            }
        };

        doc.on( 'click', '[data-load-modal]', function () {
            let _this = $( this );
            let modal = _this.attr( 'data-load-modal' );
            let parameters = {};
            let attrs = _this[0].attributes;

            for ( let i = 0; i < attrs.length; i++ )
            {
                let attrKey = attrs[ i ].nodeName;

                if ( attrKey.indexOf( 'data-parameter-' ) === 0 )
                {
                    parameters[ attrKey.substr( 15 ) ] = attrs[ i ].nodeValue;
                }
            }

            let fullscreen = _this[ 0 ].hasAttribute( 'data-fullscreen' );

            FSPoster.loadModal( modal, parameters, false, fullscreen );
        } ).on( 'click', '.fsp-modal-content [data-modal-close=true]', function () {
            FSPoster.modalHide( $( this ).closest( '.fsp-modal' ) );
        } ).on( 'mouseover', '.fsp-tooltip', function () {
            let _this = $( this );

            if ( _this.data( 'is-open' ) )
            {
                if ( _this.data( 'hide-timer' ) )
                {
                    clearTimeout( _this.data( 'hide-timer' ) );
                }

                return;
            }

            $( '.fsp-tooltip-div' ).remove();

            let tooltipDiv = $( `<div class="fsp-tooltip-div">${ _this.data( 'title' ) }</div>` );

            $( 'body' ).append( tooltipDiv );

            tooltipDiv.css( {
                top: _this.offset().top + 30 - $( window ).scrollTop(),
                left: _this.offset().left - tooltipDiv.width()
            } ).fadeIn( 200 );

            if ( tooltipDiv.offset().left <= 0 )
            {
                tooltipDiv.css( {
                    left: _this.offset().left + Math.abs( tooltipDiv.offset().left ) + 20 - tooltipDiv.width()
                } );
            }

            _this.data( 'is-open', $( 'body > .fsp-tooltip-div:eq( -1 )' ) );
        } ).on( 'mouseout', '.fsp-tooltip', function () {
            let _this = $( this );

            if ( _this.data( 'is-open' ) )
            {
                _this.data( 'hide-timer', setTimeout( function () {
                    if ( typeof _this.data( 'is-open' ).remove === 'function' )
                    {
                        _this.data( 'is-open' ).remove();
                    }

                    _this.removeData( 'hide-timer' );
                    _this.removeData( 'is-open' );
                }, 100 ) );
            }
        } ).on( 'click', '#fspCloseAlert', function () {
            $( '.fsp-alert' ).remove();
        } ).on( 'click', '#fspCloseToast', function () {
            $( '#fspToast' ).fadeOut( 200, function () {
                $( this ).remove();
            } );
        } );

        try
        {
            if ( typeof $( 'body' ).tooltip === 'function' )
            {
                $( 'body' ).tooltip( {
                    items: '.fsp-tooltip-2',
                    content: function () {
                        return $( this ).attr( 'title' );
                    }
                } );
            }
        }
        catch ( e ) {}

        $( '.fsp-select2-single' ).select2( {
            theme: '',
            minimumResultsForSearch: Infinity,
            width: '',
            containerCssClass: 'fsp-select2',
            dropdownCssClass: 'fsp-select2'
        } );

        doc.on( 'click', '.fsp-append-to-text', function () {
            let _this = $( this );
            let customPost = _this.parent().parent();
            let key = `${ _this.data( 'key' ) }`;
            let textarea = customPost.children( 'textarea, input' );

            textarea.val( textarea.val() + ' ' + key );

            if ( textarea.is( 'textarea' ) )
            {
                textarea.trigger( 'keyup' );
            }
            else
            {
                textarea.focus();
                textarea.setSelectionRange( textarea.val().length, textarea.val().length );
            }
        } );

        $( document ).on( 'click', '.fsp-clear-button', function () {
            let textarea = $( this ).parent().parent().children( 'textarea, input' );

            textarea.val( '' );

            if ( textarea.is( 'textarea' ) )
            {
                textarea.trigger( 'keyup' );
            }
        } );

        $( '.fsp-custom-post > textarea' ).on( 'input, keyup', function () {
            let _this = $( this );
            let preview = _this.parent().data( 'preview' );

            if ( preview )
            {
                $( `#${ preview }` ).text( _this.val() );
            }
        } );

        $( '.fsp-close-notification' ).on( 'click', function () {
            let _this = $( this );

            $.get( _this.prev( 'a' ).prop( 'href' ) );

            _this.parent().parent().parent().remove();
        } );

        $( '#fspNotificationShareWithPopup' ).on( 'click', function () {
            $( this ).parent().find( '.fsp-close-notification' ).click();

            FSPoster.loadModal( 'share_feeds', { 'post_id': 0, 'is_paused_feeds': 1 }, true );
        } );

        $( '#fspNotificationShareOnBackground' ).on( 'click', function () {
            $( this ).parent().find( '.fsp-close-notification' ).click();

            FSPoster.loading( true );

            FSPoster.ajax( 'share_on_bg_paused_feeds', {}, function () {
                FSPoster.toast( fsp__( 'Posts will be shared on background!' ), 'success' );

                FSPoster.loading( false );
            } );
        } );

        $( '#fspNotificationDoNotShare' ).on( 'click', function () {
            FSPoster.ajax( 'do_not_share_paused_feeds', {} );
        } );
    } );
})( jQuery );

function fsp__ ( str )
{
    return str;
}
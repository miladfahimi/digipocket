'use strict';

( function ( $ ) {
    let doc = $( document );
    let schedule_data = $( '#fspKeepLogs' ).val();
    let schedule_id = $( '#fspScheduleID' ).val();

    doc.ready( function () {
        $( '.fsp-modal-tab' ).on( 'click', function () {
            if ( $( this ).hasClass( 'fsp-is-active' ) )
            {
                return;
            }

            $( '.fsp-modal-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
            $( this ).addClass( 'fsp-is-active' );

            let step = String( $( '.fsp-modal-tab.fsp-is-active' ).data( 'step' ) );

            $( '.fsp-modal-step' ).hide();
            $( `#fspModalStep_${ step }` ).show();
        } ).eq( 0 ).click();

        $( '.fsp-custom-messages-tab' ).on( 'click', function () {
            let _this = $( this );

            $( '.fsp-custom-messages-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
            _this.addClass( 'fsp-is-active' );

            let driver = _this.data( 'tab' );

            if ( driver === 'all' )
            {
                $( '#fspCustomMessages > div' ).slideUp( 200 );
            }
            else
            {
                $( `#fspCustomMessages > div[data-driver!="${ driver }"]` ).slideUp( 200 );
                $( `#fspCustomMessages > div[data-driver="${ driver }"]` ).slideDown( 200 );
            }
        } ).eq( 0 ).click();

        $(".schedule_popup").on('click', '.schedule_save_btn', function () {
            var title = $(".schedule_popup .schedule_input_title").val(),
                startDate = $(".schedule_popup .schedule_input_start_date").val(),
                startTime = $(".schedule_popup .schedule_input_start_time").val(),
                interval = $(".schedule_popup .interval").val(),
                intervalType = $(".schedule_popup .interval_type").val(),
                share_time = $(".schedule_popup .share_time").val(),
                post_type_filter = $(".schedule_popup .schedule_input_post_type_filter").val(),
                dont_post_out_of_stock_products = $(".schedule_popup .schedule_dont_post_out_of_stock_products").is(':checked') ? 1 : 0,
                category_filter = $(".schedule_popup .schedule_input_category_filter").val(),
                post_ids = $(".schedule_popup .schedule_input_post_ids").val(),
                post_freq = $(".schedule_popup .post_freq").val(),
                post_sort = $(".schedule_popup .post_sort").val(),
                post_date_filter = $(".schedule_popup .schedule_input_post_date_filter").val(),
                set_sleep_time = $(".schedule_popup .schedule_set_sleep_time").is(':checked') ? 1 : 0,
                sleep_time_start = set_sleep_time ? $(".schedule_popup .schedule_input_sleep_time_start").val() : '',
                sleep_time_end = set_sleep_time ? $(".schedule_popup .schedule_input_sleep_time_end").val() : '',
                custom_messages = {},
                accounts_list = [];

            if ( interval % 1 !== 0 )
            {
                FSPoster.toast( fsp__( 'Interval is not correct!' ) , 'warning');

                return false;
            }

            let matchesCount = parseInt( $('.schedule_popup .schedule_matches_count').text().trim() );

            if ( matchesCount === 0 ) {
                FSPoster.toast( fsp__( 'No post matches your filters!' ) , 'warning');

                return false;
            }
            else if ( matchesCount > 1 && post_freq === 'repeat' )
            {
                FSPoster.toast( fsp__( 'If you want to share repeatedly, you should schedule only a post!' ) , 'warning');

                return false;
            }

            $(".schedule_popup .fsp-custom-post > textarea").each(function () {
                custom_messages[$(this).data('sn-id')] = $(this).val();
            });

            $('.fsp-metabox-account > input[name="share_on_nodes[]"]').each(function () {
                let splitVal = $( this ).val().split( ':' );
                let realVal = splitVal.length === 3 ? `${ splitVal[ 1 ] }:${ splitVal[ 2 ] }` : $( this ).val();

                accounts_list.push( realVal );
            });

            if ( schedule_data === 'off' && post_sort === 'random2' ) {
                FSPoster.alert('You can not select "Random (no duplicates)" option. Because in your Publish settings "Keep shared posts log" is disabled. Please activate it firstly.');
                return false;
            }

            FSPoster.ajax('schedule_save', {
                'id': schedule_id,
                'title': title,
                'start_date': startDate,
                'start_time': startTime,
                'interval': (parseInt(interval) * parseInt(intervalType)),
                'share_time': share_time,
                'post_type_filter': post_type_filter,
                'dont_post_out_of_stock_products': dont_post_out_of_stock_products,
                'category_filter': category_filter,
                'post_ids': post_ids,
                'post_freq': post_freq,
                'post_sort': post_sort,
                'post_date_filter': post_date_filter,
                'sleep_time_start': sleep_time_start,
                'sleep_time_end': sleep_time_end,
                'custom_messages': JSON.stringify(custom_messages),
                'accounts_list': JSON.stringify(accounts_list)
            }, function (result) {
                FSPoster.loading(1);
                window.location.href = 'admin.php?page=fs-poster-schedules&view=list';
            });
        }).on('click', '.wp_native_schedule_save_btn', function () {
            var info = $(this).data('info'),
                custom_messages = {},
                accounts_list = [];

            $(".schedule_popup .fsp-custom-post > textarea").each(function () {
                custom_messages[$(this).data('sn-id')] = $(this).val();
            });

            $('.fsp-metabox-account > input[name="share_on_nodes[]"]').each(function () {
                let splitVal = $( this ).val().split( ':' );
                let realVal = splitVal.length === 3 ? `${ splitVal[ 1 ] }:${ splitVal[ 2 ] }` : $( this ).val();

                accounts_list.push( realVal );
            });

            FSPoster.ajax( 'wp_native_schedule_save', {
                'info': JSON.stringify( info ),
                'custom_messages': JSON.stringify( custom_messages ),
                'accounts_list': JSON.stringify( accounts_list )
            }, function (result) {
                FSPoster.loading( 1 );

                window.location.reload();
            } );
        }).on('blur', '.schedule_input_post_ids', function () {
            if ($(this).val() == '' && !$(this).data('old-value'))
                return;

            if ($(this).val() == $(this).data('old-value'))
                return;


            $(this).data('old-value', $(this).val());

            fsRecalculatePostCount();
        }).on('change', '.schedule_input_post_type_filter', function () {
            var post_type = $(this).val();

            if (post_type == 'product') {
                $('.schedule_popup .fs_stock_option_area').slideDown(200);
            } else {
                $('.schedule_popup .fs_stock_option_area').slideUp(200);
            }
        }).on( 'change', '#fspScheduleSetSleepTime', function () {
            let checked = ! ( $( this ).is( ':checked' ) );

            $( '#fspScheduleSetSleepTimeContainer' ).toggleClass( 'fsp-hide', checked );
        } ).on( 'change', '.post_freq', function () {
            let val = $( this ).val();

            if ( val === 'once' )
            {
                $( '#fspSchedulePostEveryRow' ).addClass( 'fsp-hide' );
            }
            else if ( val === 'repeat' )
            {
                $( '#fspSchedulePostEveryRow' ).removeClass( 'fsp-hide' );
            }
        } );

        $('.schedule_popup .schedule_input_post_ids').data('old-value', $('.schedule_popup .schedule_input_post_ids').val());
        $('.schedule_popup').on('change', '.schedule_input_post_date_filter, .schedule_input_post_type_filter, .schedule_input_category_filter, .schedule_dont_post_out_of_stock_products', fsRecalculatePostCount);
        $(".schedule_popup .schedule_set_sleep_time").trigger('change');
        $('.schedule_popup .sn_tabs > [data-tab-id]:eq(0)').trigger('click');
        $('.schedule_popup .schedule_input_post_type_filter').trigger('change');

        function datePickerBoot() {
            if ('datepicker' in $.fn) {
                $(".schedule_popup .schedule_input_start_date").datepicker({
                    dateFormat: "yy-mm-dd"
                });
            } else {
                setTimeout( datePickerBoot, 1000 );
            }
        }

        function fsRecalculatePostCount() {
            var post_date_filter = $('.schedule_popup .schedule_input_post_date_filter').val(),
                post_type_filter = $('.schedule_popup .schedule_input_post_type_filter').val(),
                dont_post_out_of_stock_products = $(".schedule_popup .schedule_dont_post_out_of_stock_products").is(':checked') ? 1 : 0,
                category_filter = $('.schedule_popup .schedule_input_category_filter').val(),
                post_ids = $('.schedule_popup .schedule_input_post_ids').val();

            FSPoster.ajax('calcualte_post_count', {
                post_date_filter: post_date_filter,
                post_type_filter: post_type_filter,
                dont_post_out_of_stock_products: dont_post_out_of_stock_products,
                category_filter: category_filter,
                post_ids: post_ids
            }, function (result) {
                $('.schedule_popup .schedule_matches_count').text(result['count']);

                if ( parseInt( result[ 'count' ] ) > 1 )
                {
                    $( '#fspScheduleHowShareRow' ).addClass( 'fsp-hide' );
                    $( '#fspScheduleOrderPostsRow, #fspScheduleOutOfStockRow, #fspSchedulePostEveryRow' ).removeClass( 'fsp-hide' );
                }
                else
                {
                    $( '#fspScheduleHowShareRow' ).removeClass( 'fsp-hide' );
                    $( '#fspScheduleOrderPostsRow, #fspScheduleOutOfStockRow' ).addClass( 'fsp-hide' );
                    $('.schedule_popup .post_freq').trigger( 'change' );
                }
            });
        }

        fsRecalculatePostCount();
        datePickerBoot();
    } );
} )( jQuery );
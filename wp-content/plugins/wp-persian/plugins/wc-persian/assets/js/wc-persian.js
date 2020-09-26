jQuery(document).ready(function() {

    var jdf = new jDateFunctions();
    var isPageRTL=(jQuery('html').attr('dir')=='rtl')?1:0;

    function commitDate(old_textbox, new_textbox){

        var jdate=new_textbox.val();

        var arrdate=jdate.split('-');
        var pd = new persianDate();
        pd.year = parseInt(arrdate[0]);
        pd.month = parseInt(arrdate[1]);
        pd.date = parseInt(arrdate[2]);
        old_textbox.val(jdf.getGDate(pd)._toString("YYYY-0M-0D"));
    }


    function orderDate(){

        jQuery( "<input name='jalali_order_date' class='wcp-jdate-picker' type='text'>" ).insertAfter( "input[name='order_date']" );

        var txtold_orderdate=jQuery("input[name='order_date']");
        var txtnew_orderdate = jQuery( '.wcp-jdate-picker' );

        //txtold_orderdate.removeAttr('pattern');
        txtold_orderdate.removeAttr('class');
        //txtold_orderdate.css('display','none');
        txtold_orderdate.hide();


        txtnew_orderdate.val(jdf.getPCalendarDate(jdf.getJulianDay(new Date(txtold_orderdate.val()))).toString("YYYY-0M-0D"));


        txtnew_orderdate.persianDatepicker({
            formatDate: "YYYY-0M-0D",
            isRTL: isPageRTL,
            nextArrow: '«',
            prevArrow: '»',
            fontSize: 12,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onSelect: function(){
                commitDate(txtold_orderdate, txtnew_orderdate) },
        });

        txtnew_orderdate.change(function () {
            commitDate(txtold_orderdate, txtnew_orderdate);

        });
    }


    function salePriceDate(){

        var pTag =jQuery(".sale_price_dates_fields");
        pTag.removeClass('sale_price_dates_fields').addClass('wcp-sale_price_jdates_fields');


        jQuery( "<input id='_sale_price_jdates_from' class='wcp-jdate-picker short' type='text'>" ).insertAfter( "#_sale_price_dates_from" );
        jQuery( "<input id='_sale_price_jdates_to' class='wcp-jdate-picker short' type='text'>" ).insertAfter( "#_sale_price_dates_to" );

        var txtold_salepricefrom = jQuery( '#_sale_price_dates_from' );
        var txtnew_salepricefrom = jQuery( '#_sale_price_jdates_from' );

        if(txtold_salepricefrom.length && txtold_salepricefrom.val().length>0)
            txtnew_salepricefrom.val(jdf.getPCalendarDate(jdf.getJulianDay(new Date(txtold_salepricefrom.val()))).toString("YYYY-0M-0D"));

        //txtold_salepricefrom.removeAttr('class');
        txtold_salepricefrom.css('display','none');


        calendarXPos=(txtnew_salepricefrom.length && jQuery('html').attr('dir')=='rtl')?(txtnew_salepricefrom.outerWidth(true) ):0;
        txtnew_salepricefrom.persianDatepicker({
            formatDate: "YYYY-0M-0D",
            isRTL: isPageRTL,
            nextArrow: '«',
            prevArrow: '»',
            fontSize: 12,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onSelect: function(){
                commitDate(txtold_salepricefrom, txtnew_salepricefrom) },
        });


        txtnew_salepricefrom.change(function () {
            commitDate(txtold_salepricefrom, txtnew_salepricefrom);

        });


        var txtold_salepriceto = jQuery( '#_sale_price_dates_to' );
        var txtnew_salepriceto = jQuery( '#_sale_price_jdates_to' );

        if(txtold_salepriceto.length && txtold_salepriceto.val().length>0)
            txtnew_salepriceto.val(jdf.getPCalendarDate(jdf.getJulianDay(new Date(txtold_salepriceto.val()))).toString("YYYY-0M-0D"));

        //txtold_salepriceto.removeAttr('class');
        txtold_salepriceto.css('display','none');

        txtnew_salepriceto.persianDatepicker({
            formatDate: "YYYY-0M-0D",
            isRTL: isPageRTL,
            nextArrow: '«',
            prevArrow: '»',
            fontSize: 12,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onSelect: function(){
                commitDate(txtold_salepriceto, txtnew_salepriceto) },
        });


        txtnew_salepriceto.change(function () {
            commitDate(txtold_salepriceto, txtnew_salepriceto);

        });

        jQuery('.cancel_sale_schedule').click(function () {
            txtold_salepricefrom.val('');
            txtnew_salepricefrom.val('');

            txtold_salepriceto.val('');
            txtnew_salepriceto.val('');
            jQuery('.wcp-sale_price_jdates_fields').hide();

        });

        jQuery('.sale_schedule').click(function () {

            jQuery('.wcp-sale_price_jdates_fields').show();

        });

        if(txtold_salepricefrom.length && txtold_salepriceto.length &&  txtold_salepricefrom.val().length<1 && txtold_salepriceto.val().length<1){
            jQuery('.wcp-sale_price_jdates_fields').hide();
        }

    }


    function couponExpirydate() {
        jQuery( "<input id='expiry_jdate' class='wcp-jdate-picker short' type='text'>" ).insertAfter( "#expiry_date" );

        var txtold_couponexpirydate = jQuery( '#expiry_date' );
        var txtnew_couponexpirydate = jQuery( '#expiry_jdate' );

        if(txtold_couponexpirydate.length && txtold_couponexpirydate.val().length>0)
            txtnew_couponexpirydate.val(jdf.getPCalendarDate(jdf.getJulianDay(new Date(txtold_couponexpirydate.val()))).toString("YYYY-0M-0D"));

        //txtold_couponexpirydate.removeAttr('class');
        txtold_couponexpirydate.css('display','none');

        txtnew_couponexpirydate.persianDatepicker({
            formatDate: "YYYY-0M-0D",
            isRTL: isPageRTL,
            nextArrow: '«',
            prevArrow: '»',
            fontSize: 12,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onSelect: function(){
                commitDate(txtold_couponexpirydate, txtnew_couponexpirydate) },
        });


        txtnew_couponexpirydate.change(function () {
            commitDate(txtold_couponexpirydate, txtnew_couponexpirydate);

        });


    }


    if(jQuery( '#expiry_date' ).length && jQuery( '#coupon_amount' ).length)
        couponExpirydate()

    if(jQuery( "input[name='order_date']" ).length)
        orderDate();

    if(jQuery( '#_sale_price_dates_from' ).length && jQuery( '#_sale_price_dates_to' ).length)
        salePriceDate();


});// ready Document
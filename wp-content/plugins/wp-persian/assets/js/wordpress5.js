jQuery(document).ready(function() {

    //jQuery(".edit-post-sidebar").hide();

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

        jQuery( "<input name='jalali_order_date' class='wcp-jdate-picker' type='text'>" ).insertAfter( "#edit-post-post-schedule__toggle-0" );

        var txtold_orderdate=jQuery("#edit-post-post-schedule__toggle-0");
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

    //console.log(jQuery( 'button[id^="edit-post-post-schedule"]' ).length);
    console.log(jQuery( '.edit-post-post-schedule__toggle' ).length);
    if(jQuery( '#edit-post-post-schedule__toggle-0' ).length)
        orderDate();


});// ready Document
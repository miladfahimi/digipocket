jQuery(document).ready(function() {

    function gregorian_to_jalali(gy, gm, gd) {
        gy = parseInt(gy);
        gm = parseInt(gm);
        gd = parseInt(gd);
        g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        jy = (gy <= 1600) ? 0 : 979;
        gy -= (gy <= 1600) ? 621 : 1600;
        gy2 = (gm > 2) ? (gy + 1) : gy;
        days = (365 * gy) + (parseInt((gy2 + 3) / 4)) - (parseInt((gy2 + 99) / 100))
            + (parseInt((gy2 + 399) / 400)) - 80 + gd + g_d_m[gm - 1];
        jy += 33 * (parseInt(days / 12053));
        days %= 12053;
        jy += 4 * (parseInt(days / 1461));
        days %= 1461;
        jy += parseInt((days - 1) / 365);
        if (days > 365)days = (days - 1) % 365;
        jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
        jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
        if (jm < 10)jm = '0' + String(jm);
        return [String(jy), String(jm), String(jd)];
    }

    function jalali_to_gregorian(jy, jm, jd) {
        jy = parseInt(jy);
        jm = parseInt(jm);
        jd = parseInt(jd);
        gy = (jy <= 979) ? 621 : 1600;
        jy -= (jy <= 979) ? 0 : 979;
        days = (365 * jy) + ((parseInt(jy / 33)) * 8) + (parseInt(((jy % 33) + 3) / 4))
            + 78 + jd + ((jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
        gy += 400 * (parseInt(days / 146097));
        days %= 146097;
        if (days > 36524) {
            gy += 100 * (parseInt(--days / 36524));
            days %= 36524;
            if (days >= 365)days++;
        }
        gy += 4 * (parseInt((days) / 1461));
        days %= 1461;
        gy += parseInt((days - 1) / 365);
        if (days > 365)days = (days - 1) % 365;
        gd = days + 1;
        sal_a = [0, 31, ((gy % 4 == 0 && gy % 100 != 0) || (gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        for (gm = 0; gm < 13; gm++) {
            v = sal_a[gm];
            if (gd <= v)break;
            gd -= v;
        }
        if (gm < 10)gm = '0' + String(gm);
        return [String(gy), String(gm), String(gd)];
    }

    var jalali_month_names = ['', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];


    /*
     * Edit inline
     */
    function jalaliTimestampDiv(year, mon, day, hour, minu) {
        div = '<div class="timestamp-wrap jalali">' +
            '<label><input type="text" id="jja" name="jja" value="' + day + '" size="2" maxlength="2" autocomplete="off" /></label>' +
            '<label><select id="mma" name="mma">';
        for (var i = 1; i < 13; i++) {
            if (i == mon)
                div += '<option value="' + i + '" selected="selected">' + jalali_month_names[i] + '</option>';
            else
                div += '<option value="' + i + '">' + jalali_month_names[i] + '</option>';
        }
        div += '</select></label>' +

            '<label><input type="text" id="aaa" name="aaa" value="' + year + '" size="4" maxlength="4" autocomplete="off" /></label> @ ' +
            '<input type="text" id="hha" name="hha" value="' + hour + '" size="2" maxlength="2" autocomplete="off" />:' +
            '<input type="text" id="mna" name="mna" value="' + minu + '" size="2" maxlength="2" autocomplete="off" />' +
            '</div>';
        return div;
    }

    jQuery('a.edit-timestamp').on('click', function () {
        jQuery('.jalali').remove();
        var date = gregorian_to_jalali(jQuery('#aa').val(), jQuery('#mm').val(), jQuery('#jj').val());
        jQuery('#timestampdiv').prepend(jalaliTimestampDiv(date[0], date[1], date[2], jQuery('#hh').val(), jQuery('#mn').val()));
        jQuery('#timestampdiv .timestamp-wrap:eq(1)').hide();
    });

    jQuery('#the-list').on('click', '.editinline', function () {
        var tr = jQuery(this).closest('td');
        var year = tr.find('.aa').html();
        if (year > 1700) {
            var month = tr.find('.mm').html();
            var day = tr.find('.jj').html();
            var hour = tr.find('.hh').html();
            var minu = tr.find('.mn').html();
            var date = gregorian_to_jalali(year, month, day);
            jQuery('.inline-edit-date .timestamp-wrap').hide();
            jQuery('.jalali').remove();
            jQuery('.inline-edit-date legend').after(jalaliTimestampDiv(date[0], date[1], date[2], hour, minu));
        }
    });

    jQuery('#timestampdiv,.inline-edit-date').on('keyup', '#hha', function (e) {
        jQuery('input[name=hh]').val(jQuery(this).val());
    });

    jQuery('#timestampdiv,.inline-edit-date').on('keyup', '#mna', function (e) {
        jQuery('input[name=mn]').val(jQuery(this).val());
    });

    jQuery('#timestampdiv,.inline-edit-date').on('keyup', '#aaa , #jja', function (e) {
        date = jalali_to_gregorian(jQuery('#aaa').val(), jQuery('#mma').val(), jQuery('#jja').val());
        jQuery('input[name=aa]').val(date[0]);
        jQuery('select[name=mm]').val(date[1]);
        jQuery('input[name=jj]').val(date[2]);
    });

    jQuery('#timestampdiv,.inline-edit-date').on('change', '#mma', function () {
        date = jalali_to_gregorian(jQuery('#aaa').val(), jQuery('#mma').val(), jQuery('#jja').val());
        jQuery('input[name=aa]').val(date[0]);
        jQuery('select[name=mm]').val(date[1]);
        jQuery('input[name=jj]').val(date[2]);
    });


    /*
     * Filter on post screen dates
     */
//jQuery('select[name=m]').hide()
    var timer;

    function applyJalaliDate() {
        var oldTimestamp = jQuery('#timestamp b').text();
        var newTimestamp = jQuery('#jja').val() + ' ' + jQuery('#mma option:selected').text() + ' ' + jQuery('#aaa').val() + ' @ ' + jQuery('#hha').val() + ':' + jQuery('#mna').val();
        newTimestamp = newTimestamp.replace(/\d+/g, function (digit) {
            var ret = '';
            for (var i = 0, len = digit.length; i < len; i++) {
                ret += String.fromCharCode(digit.charCodeAt(i) + 1728);
            }
            return ret;
        });
        if (oldTimestamp != newTimestamp) {
            jQuery('#timestamp b').attr('dir', 'rtl');
            jQuery('#timestamp b').html(newTimestamp);
            clearInterval(timer);
        }
    }

    jQuery('#timestampdiv').on('keypress', function (e) {
        if (e.which == 13)
            timer = setInterval(function () {
                applyJalaliDate();
            }, 50);
    });

    jQuery('.save-timestamp  , #publish').on('click', function () {
        if (jQuery('#aaa').length)
            timer = setInterval(function () {
                applyJalaliDate();
            }, 50);
    });


});

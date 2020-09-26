<?php
/**
 * Created by Siavash Salemi
 * Date: 06/05/2018
 * Time: 11:14 PM
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once(WPP_DIR.'includes/class-wpp-jdate.php');

/**
 *
 * @param string $format
 * @param int $time
 * @param string $tr_num
 *
 * @return mixed|string
 */
function wpp_jdate( $format , $time  , $tr_num='') {
    //if ( is_int( $time ) ) {
    if ( is_int( $time ) ) {
        $timestamp = $time;
    }elseif(is_numeric($time)){
        $timestamp = intval($time);
    } else {
        $timestamp = strtotime($time);
    }

    if ( empty( $tr_num ) && is_admin() && get_option( 'wpp_adminpanel_numbers_date_i18n' ) ) {
        $tr_num = 'fa';
    } elseif ( empty( $tr_num ) && is_admin() ) {
        $tr_num = 'en';
    } elseif ( empty( $tr_num ) && ! is_admin() && get_option( 'wpp_frontpage_numbers_date_i18n' ) ) {
        $tr_num = 'fa';
    } elseif ( empty( $tr_num ) && ! is_admin() ) {
        $tr_num = 'en';
    }
    //$time_zone = get_option( 'timezone_string' );
    $time_zone = get_option( 'gmt_offset' );
    //error_log('timezone='.$time_zone);
    //$time_zone = 'UTC';
    //$tr_num='en';
    return WPP_JDate::jdate( $format, $timestamp, '', $time_zone, $tr_num );
}


/**
 * @param int|string $jmonth
 *
 * @return string
 */
function wpp_jmonth_name($jmonth) {
    return WPP_JDate::jdate_words( array( 'mm' => (int) $jmonth ) )['mm'];
}


function wpp_jweekday_abbrev($jmonth) {
    return WPP_JDate::jdate_words( array( 'km' => (int) $jmonth ) )['km'];
}


function wpp_jweekday_initial($jmonth) {
    return WPP_JDate::jdate_words( array( 'kh' => (int) $jmonth ) )['kh'];
}


function wpp_jalali_to_gregorian($j_y,$j_m,$j_d) {
    return WPP_JDate::jalali_to_gregorian( $j_y, $j_m, $j_d );
}


function wpp_gregorian_to_jalali($g_y,$g_m,$g_d) {
    return WPP_JDate::gregorian_to_jalali( $g_y, $g_m, $g_d );
}


function wpp_jmktime($hour , $minute , $second , $month , $day , $year) {
    return WPP_JDate::jmktime( $hour, $minute, $second, $month, $day, $year );

}


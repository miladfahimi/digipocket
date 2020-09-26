<?php
/**
 * Created by Siavash Salemi
 * Date: 17/05/2018
 * Time: 01:10 PM
 */

if ( ! defined( 'ABSPATH' ) ) exit;


function wpp_letters_ar2fa( $content ) {
    return str_replace( array( 'ي', 'ك', 'ة', '٤', '٥', '٦' ), array( 'ی', 'ک', 'ه', '۴', '۵', '۶' ), $content );
}


function wpp_numbers_en2fa( $content ) {
    return preg_replace_callback( '/(?:&#\d{2,4};)|(\d+[\.\d]*)|(?:[a-z](?:[\x00-\x3B\x3D-\x7F]|<\s*[^>]+>)*)|<\s*[^>]+>/i',
        'wpp_numbers_to_farsi', $content );
}


function wpp_numbers_to_farsi( $matches ) {
    $farsi_array   = array( "۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "." );
    $english_array = array( "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "." );
    if ( isset( $matches[1] ) ) {
        return str_replace( $english_array, $farsi_array, $matches[1] );
    }
    return $matches[0];
}


function wpp_numbers_to_english( $num ) {
    $farsi_array   = array( "۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "." );
    $english_array = array( "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "." );

    return str_replace( $farsi_array, $english_array, $num );
}

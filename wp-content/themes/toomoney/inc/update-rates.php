<?php
function update_currency_rate() {

    wp_insert_post(
        array(
            'post_type'         => 'rate',
            'post_status'		=>	'publish',
            'meta_input' => array(
            'aed_usd'		=>	convertIt('aed','usd'),       //USD
            'usd_sek'		=>	convertIt('usd','sek'),       //SEK
            'usd_dkk'		=>	convertIt('usd','dkk'),       //DKK
            'usd_nok'		=>	convertIt('usd','nok'),       //NOK
        ))
    );
}

function convertIt($t,$f){
    
    //GET THE LIVE CONVERSION RATES
    $endpoint = 'convert';
    $access_key = 'fe4f757533db7ed9d467848cfa6a6e6f';

    $from = $f;
    $to = $t;
    $amount = 1;

    // initialize CURL:
    $json = file_get_contents('https://api.coinlayer.com/api/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$amount.'');

    // Decode JSON response:
    $conversionResult = json_decode($json, true);
    return $conversionResult['result'];

}
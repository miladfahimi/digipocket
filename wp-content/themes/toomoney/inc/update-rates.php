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
            'usd_nok'		=>	convertItRapid('USD','NOK'),       //NOK
            'btc_usd'       =>  getBtcRate(),                 //BTC
            // 'Gold_usd'      =>  getGoldRate(),                //GOLD
        ))
    );
}
function getBtcRate(){
    $json=file_get_contents('https://data.fixer.io/api/convert?access_key=08a49f056fa219b51066bbdc5445a9ed&from=BTC&to=usd&amount=1');
                             
    $conversionResult = json_decode($json, true);
    return $conversionResult['result'];
}
// function getGoldRate(){
//     $json=file_get_contents('https://api.coinlayer.com/convert?access_key=fe4f757533db7ed9d467848cfa6a6e6f&from=XAU&to=usd&amount=1');
//     $conversionResult = json_decode($json, true);
//     return $conversionResult['result'];
// }
function convertIt($t,$f){
    
    //GET THE LIVE CONVERSION RATES
    $endpoint = 'convert';
    $access_key = '08a49f056fa219b51066bbdc5445a9ed';

    $from = $f;
    $to = $t;
    $amount = 1;

    // initialize CURL:
    $json = file_get_contents('https://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$amount.'');

    // Decode JSON response:
    $conversionResult = json_decode($json, true);
    return $conversionResult['result'];

}
function convertItRapid($t,$f){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://currency-converter5.p.rapidapi.com/currency/convert?format=json&from='.$f.'&to='.$t.'&amount=1',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: currency-converter5.p.rapidapi.com",
            "x-rapidapi-key: e74de94fb8mshcae9464ff489360p11f98ajsn50cecdc27b6a"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        $result = $err;
    } else {
        $json = json_decode($response,true);
        $result = (float)$json->rates[0]->$t->rate;
    }
    $json = json_decode($response,true);
    $result = (float)$json->rates[0]->$t->rate;
    return $result;
}
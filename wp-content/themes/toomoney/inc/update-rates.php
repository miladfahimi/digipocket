<?php
function update_currency_rate() {

    wp_insert_post(
        array(
            'post_type'         => 'rate',
            'post_status'		=>	'publish',
            'meta_input' => array(
            'aed_usd'		=>	getYahooCurrency('aed','usd'),       //USD
            'usd_sek'		=>	getYahooCurrency('usd','sek'),       //SEK
            'usd_dkk'		=>	getYahooCurrency('usd','dkk'),                 //DKK
            'usd_nok'		=>	getYahooCurrency('usd','nok'),       //NOK
            'btc_usd'       =>  getYahooCrypto('btc','usd')        //BTC
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

//FIXER API
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

//CURRENCY API
function convertItByCurrencyApi($t,$f){
    $key = 'VeuCryIuwgaKnnhBHIvqw4nxFEW38VkdNg5L';
    
    // initialize CURL:
    $json = file_get_contents('https://currencyapi.net/api/v1/rates?key=VeuCryIuwgaKnnhBHIvqw4nxFEW38VkdNg5L&base='.strtoupper($f));
    //https://currencyapi.net/api/v1/rates?key=VeuCryIuwgaKnnhBHIvqw4nxFEW38VkdNg5L&base=USD

    // Decode JSON response:
    $conversionResult = json_decode($json, true);
    return $conversionResult['rates'][strtoupper($t)];
}

function getYahooCurrency($t,$f){
    $html = file_get_html('https://finance.yahoo.com/quote/'. strtoupper($f) . strtoupper($t) .'=X');
    foreach($html->find('div[class="D(ib) Mend(20px)"]') as $div)
    foreach($div->find('span[data-reactid="32"]') as $e)
    $result = $e->innertext;
    return $result;
}
function getYahooCrypto($t,$f){
    $html = file_get_html('https://finance.yahoo.com/quote/BTC-USD');
    foreach($html->find('div[class="D(ib) Mend(20px)"]') as $div)
    foreach($div->find('span[data-reactid="32"]') as $e)
    $result = (float)str_replace(',', '',$e->innertext);
    return $result;
}
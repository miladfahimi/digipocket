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
    $client = new http\Client;
    $request = new http\Client\Request;
    
    $request->setRequestUrl('https://currency-converter5.p.rapidapi.com/currency/convert');
    $request->setRequestMethod('GET');
    $request->setQuery(new http\QueryString([
        'format' => 'json',
        'from' => 'AUD',
        'to' => 'CAD',
        'amount' => '1'
    ]));
    
    $request->setHeaders([
        'x-rapidapi-key' => 'e74de94fb8mshcae9464ff489360p11f98ajsn50cecdc27b6a',
        'x-rapidapi-host' => 'currency-converter5.p.rapidapi.com'
    ]);
    
    $client->enqueue($request)->send();
    $response = $client->getResponse();
    
    $json = $response->getBody();
    $result = $json->rates[0]->CAD->rate+0;
    return $result;
}
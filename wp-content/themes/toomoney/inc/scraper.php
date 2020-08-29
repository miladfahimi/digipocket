<?php
function run_scraper_every_thirty_minutes() {
    // get DOM from URL or file
    // $html = file_get_html('http://www.bonbast.com/');
    // $html02 = file_get_html('https://www.tgju.org/profile/price_sek/technical');
    $html03 = file_get_html('https://www.tgju.org/');
    $html04 = file_get_html('https://www.tgju.org/profile/price_aed/technical');

    $usBuy=0;;
    $usSale = 0;
    $sekBuy = 0;
    $sekSale = 0;
    $sekSale02 = 0;
    $sekSale03 = 0;
    // // find all div tags with id=gbar
    // foreach($html->find('td#sek2') as $e)
    //     $sekBuy = $e->innertext . '<br>';
    // // find all div tags with id=gbar
    // foreach($html->find('td#sek1') as $e)
    //     $sekSale = $e->innertext . '<br>';
    // // find all div tags with id=gbar
    // foreach($html02->find('span[data-col="info.last_trade.PDrCotVal"]') as $e)
    //     $sekSale02 = $e->innertext . '<br>';
    // // find all div tags with id=gbar
    foreach($html04->find('span[data-col="info.last_trade.PDrCotVal"]') as $e)
    if((float)str_replace(',', '',$e->innertext)>100){
        $aed = $e->innertext;
    }
    foreach($html03->find('tr[data-market-row="price_aed"]') as $e)
    if((float)str_replace(',', '',$e->getAttribute('data-price'))>100){
    $aed = $e->getAttribute('data-price');
    }

    $k = 180;
    $g = 50;

    // Function call with your own text or variable
    $USD= ((float)str_replace(',', '',$aed)*convertIt('aed','usd'))/10;

    wp_insert_post(
        array(
            'post_type'         => 'index',
            'post_status'		=>	'publish',
            'meta_input' => array(
            'sek_buy'		=>	round($USD*convertIt('usd','sek')/10)*10-$k,       //SEK
            'sek_sale'		=>	round($USD*convertIt('usd','dkk')/10)*10-$k,       //DKK
            'usd_buy'		=>	round($USD*convertIt('usd','nok')/10)*10-$k,       //NOK
            'usd_sale'		=>	round($USD/10)*10-$g                               //USD
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
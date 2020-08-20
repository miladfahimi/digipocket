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
    if((float)str_replace(',', '',$e->innertext)!=0){
        $aedSale = $e->innertext;
    }
    foreach($html03->find('tr[data-market-row="price_aed"]') as $e)
    if((float)str_replace(',', '',$e->getAttribute('data-price'))!=0){
    $aedSale = $e->getAttribute('data-price');
    }
    // Function call with your own text or variable
    $USDasAED= ((float)str_replace(',', '',$aedSale)*3.67)/10;
    $sekTousd=0.11;
    $usdTonok=0.10;
    $usdTodkk=0.16;
    $nokTosek=0.97;

    wp_insert_post(
        array(
            'post_type'         => 'index',
            'post_status'		=>	'publish',
            'meta_input' => array(
            'sek_buy'		=>	round($USDasAED*$sekTousd/10)*10,       //SEK
            'sek_sale'		=>	round($USDasAED*$usdTodkk/10)*10,       //DKK
            'usd_buy'		=>	round($USDasAED*$sekTousd*0.97/10)*10,  //NOK
            'usd_sale'		=>	round($USDasAED/10)*10                  //USD
        ))
    );
}
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

    $k = 0;
    $g = 180;
    $aed_usd=0;
    $usd_sek=0;
    $usd_dkk=0;
    $usd_nok=0;

    $ratesCheck = new WP_Query(array(
        'post_type' => 'rate',
        'posts_per_page'=>1,
        'order'=>'DESC',
        'orderby'=>'ID',
      ));
      if ( $ratesCheck->have_posts() ) {
        while ( $ratesCheck->have_posts() ) {
            $ratesCheck->the_post();
            $aed_usd=get_field('aed_usd');
            $usd_sek=get_field('usd_sek');
            $usd_dkk=get_field('usd_dkk');
            $usd_nok=get_field('usd_nok');
            $btc=get_field('btc_usd');
            $date=get_the_date('F j, Y G:i');
    }}

    // Function call with your own text or variable
    $USD= ((float)str_replace(',', '',$aed)*$aed_usd)/10-500;

    wp_insert_post(
        array(
            'post_type'         => 'index',
            'post_status'		=>	'publish',
            'meta_input' => array(
            'sek_buy'		=>	round($USD*$usd_sek/10)*10-$k,       //SEK
            'sek_sale'		=>	round($USD*$usd_dkk/10)*10-$k,       //DKK
            'usd_buy'		=>	round($USD*$usd_nok/10)*10-$k,       //NOK
            'usd_sale'		=>	round($USD/10)*10-$k,                //USD
            'btc'           =>  round($btc)                          //BTC
        ))
    );
}  
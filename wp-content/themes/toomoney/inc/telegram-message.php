<?php
function check_every_30min_if_new_value() {
$checkNew = array();
    $indexCheckNew = new WP_Query(array(
        'post_type' => 'index',
        'posts_per_page'=>2,
        'order'=>'DESC',
        'orderby'=>'ID',
      ));
      if ( $indexCheckNew->have_posts() ) {
        while ( $indexCheckNew->have_posts() ) {
            $indexCheckNew->the_post();
            array_push($checkNew,get_field('usd_sale'));
    }}
    print_r($checkNew);
    if ($checkNew[0] != $checkNew[1]){
        sendMessage();
    }else{
        //something here!
    }
}

function send_message_once_at_morning() {
$checkNew = array();
    $indexCheckNew = new WP_Query(array(
        'post_type' => 'index',
        'posts_per_page'=>2,
        'order'=>'DESC',
        'orderby'=>'ID',
      ));
      if ( $indexCheckNew->have_posts() ) {
        while ( $indexCheckNew->have_posts() ) {
            $indexCheckNew->the_post();
            array_push($checkNew,get_field('usd_sale'));
    }}
    print_r($checkNew);
    if ($checkNew[0] == $checkNew[1]){
        sendMessage();
    }else{
        //something here!
    }
}

function sendMessage() {
        $indexQuery = new WP_Query(array(
            'post_type' => 'index',
            'posts_per_page'=>1,
            'order'=>'DESC',
            'orderby'=>'ID',
          ));
          if ( $indexQuery->have_posts() ) {
            while ( $indexQuery->have_posts() ) {
                $indexQuery->the_post();
                $priceSek=get_field('sek_buy');
                $priceDkk=get_field('sek_sale');
                $priceNok=get_field('usd_buy');
                $date=get_the_date('F j, Y G:i');
        }}
          
$priceSekIranBuyLow=round(($priceSek-($priceSek/100)*20)/50)*50;
$priceDkkIranBuyLow=round(($priceDkk-($priceDkk/100)*20)/50)*50;
$priceNokIranBuyLow=round(($priceNok-($priceNok/100)*20)/50)*50;
$priceSekIranBuyHigh=round(($priceSek-($priceSek/100)*15)/50)*50;
$priceDkkIranBuyHigh=round(($priceDkk-($priceDkk/100)*15)/50)*50;
$priceNokIranBuyHigh=round(($priceNok-($priceNok/100)*15)/50)*50;
$priceSekIranSaleLow=round(($priceSek-($priceSek/100)*10)/50)*50;
$priceDkkIranSaleLow=round(($priceDkk-($priceDkk/100)*10)/50)*50;
$priceNokIranSaleLow=round(($priceNok-($priceNok/100)*10)/50)*50;
$priceSekIranSaleHigh=round(($priceSek-($priceSek/100)*15)/50)*50;
$priceDkkIranSaleHigh=round(($priceDkk-($priceDkk/100)*15)/50)*50;
$priceNokIranSaleHigh=round(($priceNok-($priceNok/100)*15)/50)*50;
$priceSekSwedBuyLow=round(($priceSek-($priceSek/100)*30)/50)*50;
$priceDkkSwedBuyLow=round(($priceDkk-($priceDkk/100)*30)/50)*50;
$priceNokSwedBuyLow=round(($priceNok-($priceNok/100)*30)/50)*50;
$priceSekSwedBuyHigh=round(($priceSek-($priceSek/100)*15)/50)*50;
$priceDkkSwedBuyHigh=round(($priceDkk-($priceDkk/100)*15)/50)*50;
$priceNokSwedBuyHigh=round(($priceNok-($priceNok/100)*15)/50)*50;
$priceSekSwedSaleLow=round(($priceSek-($priceSek/100)*8)/50)*50;
$priceDkkSwedSaleLow=round(($priceDkk-($priceDkk/100)*8)/50)*50;
$priceNokSwedSaleLow=round(($priceNok-($priceNok/100)*8)/50)*50;
$priceSekSwedSaleHigh=round(($priceSek-($priceSek/100)*2)/50)*50;
$priceDkkSwedSaleHigh=round(($priceDkk-($priceDkk/100)*2)/50)*50;
$priceNokSwedSaleHigh=round(($priceNok-($priceNok/100)*2)/50)*50;

$msg="
<b>نرخ لحظه ای کرون سوئد، نروژ و دانمارک</b>
ـ               $date
ـ                        🔻🔻🔻

<b>قیمت بر حسب نرخ لحظه ای دلار</b>
<code> واحد  |   قیمت  </code>
<b>ـ 🇸🇪   |  ـ$priceSek   تومان </b>
<b>ـ 🇩🇰   |  ـ$priceDkk   تومان</b>
<b>ـ 🇳🇴   |  ـ$priceNok   تومان</b>

ـ                        💠💠💠

<em> مقایسه بازه نرخ خرید و فروش تهران</em>
<b>🔹نرخهای خرید</b>
<em>واحد  پایین ترین     بالاترین  </em>
<b>ـ 🇸🇪  |  ـ$priceSekIranBuyLow   ≃   $priceSekIranBuyHigh  تومان </b>
<b>ـ 🇩🇰  |  ـ$priceDkkIranBuyLow   ≃   $priceDkkIranBuyHigh  تومان </b>
<b>ـ 🇳🇴  |  ـ$priceNokIranBuyLow   ≃   $priceNokIranBuyHigh  تومان </b>
<b>🔸نرخهای فروش</b>
<em>واحد  پایین ترین     بالاترین  </em>
<b>ـ 🇸🇪  |  ـ$priceSekIranSaleLow   ≃   $priceSekIranSaleHigh  تومان </b>
<b>ـ 🇩🇰  |  ـ$priceDkkIranSaleLow   ≃   $priceDkkIranSaleHigh  تومان </b>
<b>ـ 🇳🇴  |  ـ$priceNokIranSaleLow   ≃   $priceNokIranSaleHigh  تومان </b>

<code>(توضیحات: قیمت به ازای مبالغ نقدی تهران می باشد.)</code>

ـ                        💠💠💠

<em> مقایسه بازه نرخ خرید و فروش اروپا</em>
<b>🔹نرخهای خرید</b>
<em>واحد  پایین ترین     بالاترین  </em>
<b>ـ 🇸🇪  |  ـ$priceSekSwedBuyLow   ≃   $priceSekSwedBuyHigh  تومان </b>
<b>ـ 🇩🇰  |  ـ$priceDkkSwedBuyLow   ≃   $priceDkkSwedBuyHigh  تومان </b>
<b>ـ 🇳🇴  |  ـ$priceNokSwedBuyLow   ≃   $priceNokSwedBuyHigh  تومان </b>
<b>🔸نرخهای فروش</b>
<em>واحد  پایین ترین     بالاترین  </em>
<b>ـ 🇸🇪  |  ـ$priceSekSwedSaleLow   ≃   $priceSekSwedSaleHigh  تومان </b>
<b>ـ 🇩🇰  |  ـ$priceDkkSwedSaleLow   ≃   $priceDkkSwedSaleHigh  تومان </b>
<b>ـ 🇳🇴  |  ـ$priceNokSwedSaleLow   ≃   $priceNokSwedSaleHigh  تومان </b>

ـ                        💠💠💠

<b>ـ💰💰💰💰💰💰💰💰💰💰💰💰💰</b>
<b>ـ http://833efedb77c8.ngrok.io/logo.png ـ</b>";
 telegram ($msg);
}

?>
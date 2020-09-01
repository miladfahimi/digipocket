<?php
function send_telegram_msg_every_thirty_minutes() {
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
     
//telegram(convertorJpeg());
}






function convertorJpeg(){
    $html = '<div class="p-4 text-center mt-4" style="width: 500px">
      <span class="tweet-text mb-4">
        This is Little Bear. He tolerates baths because he knows how phenomenal his
        floof will appear afterwards. 13/10
      </span>
      <div class="mt-2 p-4">
        <img src="https://pbs.twimg.com/profile_images/1267972589722296320/XBr04M6J_400x400.jpg" class="rounded-circle shadow border mt-4" width="100px">
      </div>
      <h4 class="mt-2">
        WeRateDogs
      </h4>
      <span class="text-muted">@dog_rates</span>
    </div>
    
    <!-- Include external CSS, JavaScript or Fonts! -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700" rel="stylesheet">';
    $css = ".tweet-text {
      background-color: #fff2ac;
      background-image: linear-gradient(to right, #ffe359 0%, #fff2ac 100%);
      font-weight: bolder;
      font-size: 32px;
      font-family: 'Roboto', sans-serif;
      padding: 4px;
    }";
    
    $client = new \GuzzleHttp\Client();
    // Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
    $res = $client->request('POST', 'https://hcti.io/v1/image', [
      'auth' => ['2d7f5af8-4254-41a9-bff5-c76a6cae81f1', '7c46c9c9-5b09-40e0-90fb-487c309d6100'],
      'form_params' => ['html' => $html, 'css' => $css]
    ]);
    
    //echo $res->getBody();
    //$test=$res->getBody();
    
    return $res->getBody()." ";
    }
    



?>
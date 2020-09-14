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
 //addQueryImageToMedia();
     
//telegram(convertorJpeg($priceSek,$priceNok,$priceDkk,$date));
saveTheImage(convertorJpeg($priceSek,$priceNok,$priceDkk,$date),get_the_date('DD-MM-YYYY-G:i'));
addd();
}



function convertorJpeg($priceSek,$priceNok,$priceDkk,$date){
    
        $html = '<div class="cont"
        style="background-image:linear-gradient(to right top,rgba(27, 51, 77, 0.6),rgba(27, 51, 77, 0.8)),url(https://digipocket.ir/wp-content/themes/toomoney/images/slider_img1.png)">
        <ul class="rows">
            <li class="toomoney-logo"> <img src="https://digipocket.ir/wp-content/themes/toomoney/images/logos/logo_2.png" >
            </li>
            <li class="columns">
                <div class="cells-date">
                    '.$date.'</div>
            </li>
            <li class="columns">
                <div class="cells-full">
                    نرخ لحظه ای ارز، طلا و بیت کوین
                </div>
            </li>
            <li class="columns">
                <div class="cells"> <img src="https://digipocket.ir/wp-content/themes/toomoney/images/sweden.jpg"><span>'.$priceSek.'</span>
                    <p>
                        تومان</p>
    
                </div>
                <div class="cells">
                    <img src="https://digipocket.ir/wp-content/themes/toomoney/images/BTC.png"><span
                        style="font-size:17px">11397</span>
                    <p>
                        دلار</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells"> <img src="https://digipocket.ir/wp-content/themes/toomoney/images/norwegin.jpg"><span>'.$priceNok.'</span>
                    <p>
                        تومان</p>
                </div>
                <div class="cells"> <img src="https://digipocket.ir/wp-content/themes/toomoney/images/etherium.png"><span
                        style="font-size:17px">11397</span>
                    <p>
                        دلار</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells"> <img src="https://digipocket.ir/wp-content/themes/toomoney/images/denmark.jpg"><span>'.$priceDkk.'</span>
                    <p>
                        تومان</p>
                </div>
                <div class="cells"> <img src="https://digipocket.ir/wp-content/themes/toomoney/images/ripple.png"><span
                        style="font-size:17px">11397</span>
                    <p>
                        دلار</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells-full-sub">
                    قیمت ها با تغییرات لحظه ای بازار ارز تغییر می کنند. </div>
            </li>
        </ul>
    </div>';

$css = '@font-face {
font-family: "vazir";
src: url("https://digipocket.ir/wp-content/themes/toomoney/fonts/Vazir-FD-WOL.eot");
src: url("https://digipocket.ir/wp-content/themes/toomoney/fonts/Vazir-FD-WOL.eot#iefix") format("embedded-opentype"),
url("fonts/Vazir-FD-WOL.woff") format("woff"),
url("https://digipocket.ir/wp-content/themes/toomoney/fonts/Vazir-FD-WOL.woff2") format("woff2"),
url("https://digipocket.ir/wp-content/themes/toomoney/fonts/Vazir-FD-WOL.ttf") format("truetype"),
url("https://digipocket.ir/wp-content/themes/toomoney/fonts/Vazir-FD-WOL.svg#CartoGothicStdBook") format("svg");
font-weight: normal;
font-style: normal;
}
.cont {
    position: relative;
    overflow: hidden;
    direction: rtl;
    font-family: "vazir";
    width: 300px;
    height: 300px;
    color: #e9d16f;
    background-size: cover;
    background-position: right;
}
.rows {
    display: flex;
    margin-top: 50px;
    flex-direction: column;
    align-items: center;
    list-style-type: none;
    padding: 10px;
}
.columns {
    display: flex;
    justify-content: center;
}
.cells {
    display: flex;
    height: 40px;
    width: 120px;
    margin: 5px;
    text-align: center;
    border: 1px solid #e9d16f;
    border-radius: 5px;
}
.cells-full {
    justify-content: center;
    height: 20px;
    width: 250px;
    margin-bottom: 5px;
    text-align: center;
    font-size: 10px;
    background-color: #e9d16f;
    color: #1b334d;
    border-radius: 5px;
}
.cells-date {
    font-size: 8px;
}
.cells-full-sub {
    height: 20px;
    width: 250px;
    margin: 5px;
    text-align: center;
    font-size: 8px;
    color: #e9d16f;
}
.cells img {
    flex: 35% 0 1;
    height: 100%;
    width: 35%;
    object-fit: cover;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}
.cells span {
    font-size: 20px;
    padding: 5px 8px;
    color: #ffe78f;
    padding-left: 0;
}
.cells p {
    font-size: 5px;
    color: #e9d16f;
}
.toomoney-logo {
    position: absolute;
    top: 5px;
    height: 50px;
    width: 100px;
}
.toomoney-logo img {
    height: 100%;
    width: 100%;
}';

$client = new \GuzzleHttp\Client();
// Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
$res = $client->request('POST', 'https://hcti.io/v1/image', [
'auth' => ['2d7f5af8-4254-41a9-bff5-c76a6cae81f1', '7c46c9c9-5b09-40e0-90fb-487c309d6100'],
'form_params' => ['html' => $html, 'css' => $css]
]);

//echo $res->getBody();
//$test=$res->getBody();
$key="url";
return json_decode($res->getBody())->$key;
}

function saveTheImage($url,$date){ 
    $dir = __DIR__ . "/query-images"; // Full Path
    $name = $date.'.png';
    is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
    
    //Get a list of all of the file names in the folder.
    $files = glob($dir . '/*');
    
    //Loop through the file list.
    foreach($files as $file){
        //Make sure that this is a file and not a directory.
        if(is_file($file)){
            //Use the unlink function to delete the file.
            unlink($file);
        }
    }
    copy($url, $dir . DIRECTORY_SEPARATOR . $name);
    $img=get_theme_file_uri('inc/query-images/'.$name);
    telegram($img);
    addToMedia($img);
}

function addQueryImageToMedia(){
    wp_insert_post(
        array(
            'post_type'		    =>	'insta',
            'post_title'		=>	'query image',
            'post_author'       =>'1',
            'post_status'		=>	'publish',
        )
    );
}
function addToMedia($image_url){
    //$image_url = 'https://toomoney.se/wp-content/themes/toomoney/inc/query-images/13-09-2020-1700.png'; // Define the image URL here

    $upload_dir = wp_upload_dir();
    
    $image_data = file_get_contents( $image_url );
    
    $filename = basename( $image_url );
    
    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
      $file = $upload_dir['path'] . '/' . $filename;
    }
    else {
      $file = $upload_dir['basedir'] . '/' . $filename;
    }
    
    file_put_contents( $file, $image_data );
    
    $wp_filetype = wp_check_filetype( $filename, null );
    
    $attachment = array(
      'post_mime_type' => $wp_filetype['type'],
      'post_title' => sanitize_file_name( $filename ),
      'post_content' => '',
      'post_status' => 'inherit'
    );
    
    $attach_id = wp_insert_attachment( $attachment, $file );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
}
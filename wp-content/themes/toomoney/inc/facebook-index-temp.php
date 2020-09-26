<?php
function convertToFacebookIndexTemplate($priceSek,$priceNok,$priceDkk,$date){
$html = ' <div class="cont-facebook" style="background-image:linear-gradient(to right top,rgba(27, 51, 77, 0.6),rgba(27, 51, 77, 0.8)),url(https://digipocket.ir/wp-content/themes/toomoney/images/slider_img1.png)">
<ul class="rows-facebook">
    <li class="toomoney-logo">
        <img src="https://digipocket.ir/wp-content/themes/toomoney/images/logos/logo_2.png" >
    </li>

    <li class="columns">
        <div class="cells">
            <img src="https://digipocket.ir/wp-content/themes/toomoney/images/sweden.jpg">
            <span>'.$priceSek.'</span>
            <p>تومان</p>
        </div>
        <div class="cells-s">
            <img src="https://digipocket.ir/wp-content/themes/toomoney/images/BTC.png">
            <span style="font-size:17px">11397</span>
            <p>دلار</p>
        </div>
    </li>

    <li class="columns">
        <div class="cells">
            <img src="https://digipocket.ir/wp-content/themes/toomoney/images/norwegin.jpg">
            <span>'.$priceNok.'</span>
            <p>تومان</p>
        </div>
        <div class="cells">
            <img src="https://digipocket.ir/wp-content/themes/toomoney/images/etherium.png">
            <span style="font-size:17px">11397</span>
            <p>دلار</p>
        </div>
    </li>

    <li class="columns">
        <div class="cells">
            <img src="https://digipocket.ir/wp-content/themes/toomoney/images/denmark.jpg">
            <span>'.$priceDkk.'</span>
            <p>تومان</p>
        </div>
        <div class="cells">
            <img src="https://digipocket.ir/wp-content/themes/toomoney/images/ripple.png">
            <span style="font-size:17px">11397</span>
            <p>دلار</p>
        </div>
    </li>

    <li class="columns">
        <div class="cells-full-sub">قیمت ها با تغییرات لحظه ای بازار ارز تغییر می کنند. </div>
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
.templates-cntr {
display: flex;
margin-top: 70px;
}
@media (max-width: 500px) {
.templates-cntr {
flex-direction: column;
margin: 30px;
justify-content: space-between;
height: 700px;
}
}

.cont-facebook {
position: relative;
overflow: hidden;
direction: rtl;
font-family: "vazir";
width: 300px;
height: 156px;
color: #e9d16f;
background-size: cover;
background-position: right;
}

.rows-facebook {
display: flex;
margin-top: 30px;
flex-direction: column;
align-items: center;
list-style-type: none;
padding: 10px;
}
.columns {
display: flex;
justify-content: center;
}

.cells-s {
display: flex;
height: 20px;
width: 120px;
margin: 2px;
text-align: center;
border: 1px solid #e9d16f;
border-radius: 3px;
}


}
.cells-content-img {
justify-content: center;
height: 120px;
width: 250px;
margin-top: 5px;
text-align: center;
font-size: 10px;
color: #1b334d;
border-radius: 5px;
}

.cells-full-sub {
height: 20px;
width: 250px;
margin: 5px;
text-align: center;
font-size: 8px;
color: #e9d16f;
}

.cells-s img {
flex: 35% 0 1;
height: 100%;
width: 35%;
object-fit: cover;
border-top-right-radius: 4px;
border-bottom-right-radius: 4px;
}

.cells-s span {
font-size: 14px;
padding: 0 8px;
color: #ffe78f;
padding-left: 0;
}
.cells-s p {
font-size: 8px;
color: #e9d16f;
}

.toomoney-logo-facebook {
position: absolute;
top: 2px;
width: 70px;
}
.toomoney-logo-facebook img {
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
<?php
// Telegram function which you can call
function telegram($msg) {
        global $telegrambot,$telegramchatid;
        $url='https://api.telegram.org/bot'.$telegrambot.'/sendMessage';$data=array(
            'chat_id'=>$telegramchatid,
            'text'=>$msg,
            'parse_mode' => 'html',
            'site_name' => 'TooMoney.se'
        );
        $options=array(
            'http'=>array(
                'method'=>'POST',
                'header'=>"Content-Type:application/x-www-form-urlencoded\r\n",
                'content'=>http_build_query($data),
            ),
        );
        $context=stream_context_create($options);
        $result=file_get_contents($url,false,$context);
        return $result;
}
//https://api.telegram.org/bot1170495414:AAHvch_votOTBJR7opCeKA0tDD1y7TC_lq0/getUpdates
// Set your Bot ID and Chat ID.
$telegrambot='1170495414:AAHvch_votOTBJR7opCeKA0tDD1y7TC_lq0';
$telegramchatid='@toomoney_channel';
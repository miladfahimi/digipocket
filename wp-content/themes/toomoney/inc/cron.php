<?php
require get_theme_file_path('/inc/scraper/simple_html_dom.php');

// example of how to use basic selector to retrieve HTML contents

add_filter( 'cron_schedules', 'telegram_broadcasting' );
function telegram_broadcasting( $schedules ) {
    $schedules['every_30_minutes'] = array(
            'interval'  => 1800,
            'display'   => __( 'Every Thirty Minutes', 'textdomain' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'twice_a_day_rates' );
function twice_a_day_rates( $schedules ) {
    $schedules['every_6_hours'] = array(
            'interval'  => 21600,
            'display'   => __( 'Every 6 Hours', 'textdomain' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'every_quarter_rates' );
function every_quarter_rates( $schedules ) {
    $schedules['every_15_min'] = array(
            'interval'  => 900,
            'display'   => __( 'Every 15 min', 'textdomain' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'every_one_minute' );
function every_one_minute( $schedules ) {
    $schedules['every_one_minute'] = array(
            'interval'  => 65,
            'display'   => __( 'Every 1 minute', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'telegram_broadcasting' ) ) {
    wp_schedule_event( time(), 'every_30_minutes', 'telegram_broadcasting' );
}
if ( ! wp_next_scheduled( 'every_quarter_rates' ) ) {
    wp_schedule_event( time(), 'every_15_min', 'every_quarter_rates' );
}


// Hook into that action that'll fire every thirty minutes
add_action( 'telegram_broadcasting', 'run_scraper_every_thirty_minutes' );
add_action( 'telegram_broadcasting', 'send_telegram_msg_every_thirty_minutes' );

add_action( 'morning_telegram_broadcasting', 'send_message_once_at_morning' );


add_action( 'every_quarter_rates', 'update_currency_rate' );
//add_action( 'every_one_minute', 'update_currency_rate' );
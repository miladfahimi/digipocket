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

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'telegram_broadcasting' ) ) {
    wp_schedule_event( time(), 'every_30_minutes', 'telegram_broadcasting' );
}

// Hook into that action that'll fire every thirty minutes
add_action( 'telegram_broadcasting', 'run_scraper_every_thirty_minutes' );
add_action( 'telegram_broadcasting', 'send_telegram_msg_every_thirty_minutes' );

 add_action( 'morning_telegram_broadcasting', 'send_message_once_at_morning' );
<?php
require get_theme_file_path('/inc/scraper/simple_html_dom.php');

// example of how to use basic selector to retrieve HTML contents

add_filter( 'cron_schedules', 'index_q_every_thirty_minutes' );
function index_q_every_thirty_minutes( $schedules ) {
    $schedules['every_30_minutes'] = array(
            'interval'  => 1800,
            'display'   => __( 'Every 3 Minutes', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'index_q_every_thirty_minutes' ) ) {
    wp_schedule_event( time(), 'every_30_minutes', 'index_q_every_thirty_minutes' );
}

// Hook into that action that'll fire every thirty minutes
add_action( 'index_q_every_thirty_minutes', 'run_scraper_every_thirty_minutes' );
add_action( 'index_q_every_thirty_minutes', 'check_every_30min_if_new_value' );


 add_filter( 'cron_schedules', 'index_q_every_60_second' );
 function index_q_every_60_second( $schedules ) {
     $schedules['every_60_second'] = array(
             'interval'  => 60,
             'display'   => __( 'Every 3 Minutes', 'textdomain' )
     );
     return $schedules;
 }
 // Schedule an action if it's not already scheduled
 if ( ! wp_next_scheduled( 'index_q_every_60_second' ) ) {
     wp_schedule_event( time(), 'every_60_second', 'index_q_every_60_second' );
 }
 // Hook into that action that'll fire every thirty minutes
 add_action( 'index_q_every_60_second', 'every_60s_event_func' );

 add_action( 'every_morning_at_9', 'send_message_once_at_morning' );
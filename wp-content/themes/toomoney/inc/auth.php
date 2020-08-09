<?php // The php script I'm trying to run
add_action( 'init', 'ajax_login_init' );
function ajax_login_init() {

    wp_enqueue_script( 'ajax-login-script', get_template_directory_uri() . '/js/modules/login.js'  );


    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __( 'Sending user info, please wait...' )
    ));

    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Check if users input information is valid
function ajax_login() {
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

//Nonce is checked, get the POST data and sign user on
$info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

$user_signon = wp_signon( $info, false );
if ( is_wp_error( $user_signon )) {
    echo json_encode( array( 'loggedin'=>false, 'message'=>__( 'نام یا رمز عبور اشتباه می باشد!' )));
} else {
    echo json_encode( array( 'loggedin'=>true, 'message'=>__('عملیات ورود با موفقیت انجام شد، منتظر بمانید...' )));
}

die();
}
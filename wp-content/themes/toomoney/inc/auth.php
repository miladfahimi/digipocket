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

    wp_enqueue_script( 'ajax-register-script', get_template_directory_uri() . '/js/modules/register.js'  );


    wp_localize_script( 'ajax-register-script', 'ajax_register_object', array(
        'ajaxregisterurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __( 'Sending user info, please wait...' )
    ));

    // Enable the user with no privileges to run ajax_register() in AJAX
    add_action('wp_ajax_nopriv_ajaxregister', 'ajax_register', 100 );
    add_action('wp_ajax_ajaxregister', 'ajax_register', 0);
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


// Check if users input information is valid
function ajax_register() {
    
      // Verify nonce
  if( !isset($_POST['security'] ) || !wp_verify_nonce( $_POST['security'], 'ajax-register-nonce' ) )
  die( 'Ooops, something went wrong, please try again later.' );

$user_data = array();
    $user_data['user_login'] = $_POST['new_user_name'];
    $user_data['user_email'] = $_POST['new_user_email'];
    $user_data['user_pass']= $_POST['new_user_password'];
    $user_data['first_name'] = $_POST['new_user_first_name'];;
    $user_data['display_name'] = 'کاربر مهمان';
    $user_data['role'] = 'subscriber';

    $user_id = wp_insert_user( $user_data );
    if (!is_wp_error($user_id)) {
        echo json_encode( array( 'loggedin'=>true, 'message'=>__('عملیات ورود با موفقیت انجام شد، منتظر بمانید...' )));
        $user_info = array();
        $user_info['user_login'] = $_POST['new_user_name'];
        $user_info['user_password']= $_POST['new_user_password'];
        $user_info['remember'] = true;

        wp_signon($user_info, false );
    } else {
      if ( is_wp_error($user_id)) {
        echo json_encode( array( 'loggedin'=>false, 'message'=>__( 'نام یا ایمیل انتخابی در سیستم موجود می باشد!!' )));
        } elseif (isset($user_id->errors['existing_user_login'])) {
            echo json_encode( array( 'loggedin'=>false, 'message'=>__( 'نام یا ایمیل انتخابی در سیستم موجود می باشد!' )));
        } else {
            echo json_encode( array( 'loggedin'=>false, 'message'=>__(' لطفا در وارد کردن مشخصات دقت فرمایید!' )));
        }
    }
die();
}
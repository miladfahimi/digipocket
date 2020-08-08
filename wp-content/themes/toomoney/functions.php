<?php
require get_theme_file_path('/inc/like-route.php');
require get_theme_file_path('/inc/cron.php');
require get_theme_file_path('/inc/telegram.php');
require get_theme_file_path('/inc/telegram-message.php');
require get_theme_file_path('/inc/scraper.php');





  //ADD LINK AND SCRIPT SOURCES
function add_theme_scripts() {
    wp_enqueue_style( 'style0', get_stylesheet_uri() );
    wp_enqueue_style( 'style1', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_style( 'style2', get_template_directory_uri() . '/css/carouselTicker.css');
    wp_enqueue_style( 'style3', get_template_directory_uri() . '/css/light_theme.css');
    wp_enqueue_style( 'style4', get_template_directory_uri() . '/css/responsive.css');
    wp_enqueue_style( 'style7', get_template_directory_uri() . '/css/custom.css');
    wp_enqueue_style( 'style8', get_template_directory_uri() . '/css/landing.css');
    wp_enqueue_style( 'style9', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style( 'style10', get_template_directory_uri() . '/css/bootstrap-theme.css');
    wp_enqueue_style( 'style11', get_template_directory_uri() . '/css/font-awesome.css');
    wp_enqueue_style( 'style12', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style( 'style13', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

    wp_enqueue_script( 'script2', get_template_directory_uri() . '/js/jquery.min.js');
    wp_enqueue_script( 'script', get_template_directory_uri() . '/js/bootstrap.min.js');
    wp_enqueue_script( 'script4', get_template_directory_uri() . '/js/custom.js');
    wp_enqueue_script( 'script5', '//code.highcharts.com/highcharts.js');
    wp_enqueue_script( 'script6', "//code.highcharts.com/modules/exporting.js");
    wp_enqueue_script( 'script7', "//code.highcharts.com/stock/modules/export-data.js");
    wp_enqueue_script( 'script8', "//code.highcharts.com/stock/modules/drag-panes.js");
    wp_enqueue_script( 'script9', "//code.highcharts.com/stock/modules/data.js");
    wp_enqueue_script( 'script10', get_template_directory_uri() . '/js/chart.js');

    
    
    wp_enqueue_script( 'ajax_custom_script',  get_stylesheet_directory_uri() . '/js/ajax.js', array('jquery'));
    wp_localize_script( 'ajax_custom_script', 'frontendajax', array( 
        'ajaxurl' =>  get_site_url(),
        'nonce'   =>  wp_create_nonce('wp_rest')
    ));

}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );


function handle_custom_login(){
    $param = isset($_REQUEST['param']) ? trim($_REQUEST['param']) : "";
    if($param == "login_test"){
        $info = array();
        $info['user_login'] = $_POST['user_login'];
        $info['user_password'] = $_POST['user_pass'];
        $info['remember'] = true;
        $user_signon = wp_signon($info,false);
        if(is_wp_error($user_signon)){
            echo json_encode(array("status" => 0));
        }else{
            echo json_encode(array("status" => 1));
        }
    }
    wp_die();

}

add_action("wp_ajax_custom_login","handle_custom_login");
add_action("wp_ajax_nopriv_custom_login","handle_custom_login");


function toomoney_title_tag(){

    //ADD TAG TO BROWSER TAB
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    //ADD YOUR OWN CUSTOMIZED IMAGE SIZE IN THIS WAY, Milad Fahimi
    add_image_size('my_dummy_size', 750, 200, true);
    add_image_size('slider', 1920, 900, true);
}

add_action('after_setup_theme', 'toomoney_title_tag');


//ADD MENU LOCATION
function add_menu_location(){
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerMenuLocationOne', 'Footer Menu Location One');
    register_nav_menu('footerMenuLocationTwo', 'Footer Menu Location Two');
}

add_action('add_menu_location', 'add_menu_location');

//WIDGETS SETTING
function widgets_init(){
    register_sidebar(array(
        'name' => __('Toomoney Sidebar', 'toomoney'),
        'id' => 'toomoney2020',
        'before_title' => '<div id="toomoner-w">',
        'after_title' => '</div>'
    ));
}

add_action('widgets_init', 'widgets_init');


// ADD ACTIVE CLASS TO ACTIVE MENU BUTTON 
function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active-page-menu';
    }
    return $classes;
}

add_filter('nav_menu_css_class' , 'special_nav_class',10,2);


// ADJUSTMENT QUERY FOR ANY PAGE, ANY POST TYPE, IN FRONTEND OR BACKEND! ADD WHATEVER YOU WANT FOR YOUR QUERIES.   Milad Fahimi
function query_adjustments($query){
   if(!is_admin() AND is_post_type_archive('resource') AND is_main_query()){
       $query->set('orderby','title');
       $query->set('order','ASC');
       $query->set('posts_per_page','2');
   };
}

add_action('pre_get_posts','query_adjustments');

// REDIRECT SELECTED ROLES TO FRONTPAGE AFTER LOGIN
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber_clone') {
    wp_redirect(site_url('/'));
    exit;
  }
}


//REMOVE THE SUBADMIN NAV BAR FROM ALL
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 /*AND ($ourCurrentUser->roles[0] == 'subscriber' OR $ourCurrentUser->roles[0] == 'subscriber_clone')*/) {
    show_admin_bar(false);
  }
}

//ADD LIMIT FOR PUBLISHED ADS POSTS
function postServerSideManipulation($data){
    if($data['post_type'] == 'ads'){
    if(count_user_posts(get_current_user_id(),'ads') > 5){
        die("کاربر گرامی: سقف مجاز تعداد آگهی های شما به پایان رسیده");
        }
    }
    return $data;
}
add_filter('wp_insert_post_data','postServerSideManipulation');
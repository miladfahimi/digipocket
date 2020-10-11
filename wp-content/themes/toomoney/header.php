<!DOCTYPE html>
<html <?php language_attributes($doctype);?> dir="rtl">

<head>
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="n-KmAIh-asnZmFQAa0HWVb1L9hZfyMicT2EHqx4Rh8A" />
    <?php wp_head();?>
</head>

<body id="default_theme" class="inner_page">
    <!--loader -->
    <div class="bg_load">
        <img class="loader_animation" src="<?php echo get_theme_file_uri('images/loaders/loader.gif')?>" alt="#" />
    </div>
    <!-- end loader -->
    <header class="header">
        <a href="<?php echo site_url() ?>" class="logo"><img class="img-responsive"
                src="<?php echo get_theme_file_uri('images/logos/logo_2.png') ?>" alt="logo" /></a>
        <input class="menu-btn" type="checkbox" id="menu-btn" />
        <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>

        <?php
        
        $userPanelUrl= site_url('user-panel');
        $currentUser= get_avatar(get_current_user_id(),20);
        $mainUrl = urldecode(wp_logout_url(site_url()));
        $loginUrl = wp_login_url();
        $current_user= wp_get_current_user();
        $items_wrap = '<ul id="%1$s" class="%2$s">';
        $login=$items_wrap . '%3$s<li><a style="color:lightgreen" id="myLoginBtn"><i class="fa fa-sign-in"></i> ورود</a></li><li><a style="color:orange" id="myRegisterBtn"><i class="fa fa-user-plus"></i> ثبت نام</a></li> </ul>';
        $logout=$items_wrap .'<li><a href="'.$userPanelUrl.'">'.$currentUser .' '.  $current_user->first_name .' </a></li> %3$s<li><a href="'.$mainUrl.'">خروج </a></li></ul>';

        wp_nav_menu(array(
        'theme_location' => 'headerMenuLocation',
        'menu_class' => 'menu',
        'container' => '',
        'items_wrap' => (!is_user_logged_in())?$login : $logout
        ));
        ?>
        <ul class="social">
            <li><a href="http://facebook.com/toomoney" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li><a href="http://instagram.com/toomoney_stock" target="_blank"><i class="fa fa-instagram"></i></a></li>
<!--
            <li><a href="#"><i class="fa fa-whatsapp"></i></a></li>
-->
            <li><a href="https://t.me/toomoney_index" target="_blank"><i class="fa fa-telegram" aria-hidden="true"></i></a></li>
            <li><a class="search_btn"><i class="fa fa-search" aria-hidden="true"></i></a></li>
        </ul>
        <!-- end right header section -->
    </header>
    <?php get_template_part( 'carousel-ticker', 'header' ); ?>
<?php
/*
Plugin Name: Local by Flywheel Live Link Helper
Plugin URI: http://getflywheel.com
Description: Makes WordPress URL functions relative to play nicely with Live Links.
Version: 1.2
Author: Flywheel
Author URI: http://getflywheel.com
License: GPLv2 or later
*/

global $tunnel_home_domain;
$tunnel_home_domain = str_replace( '/^www./', '', parse_url( get_option( 'home' ), PHP_URL_HOST ) );

if ( isset( $_SERVER['HTTP_X_ORIGINAL_HOST'] ) && $_SERVER['HTTP_X_ORIGINAL_HOST'] ) {
	add_action( 'send_headers', 'lbf_send_private_cache_control_header', 9999 );
	add_filter( 'template_include', 'lbf_make_link_tunnel', 0 );

	remove_action( 'template_redirect', 'redirect_canonical' );

	$filters = array(
		'get_rest_url',
		'wp_redirect',
		'bloginfo_url',
		'the_permalink',
		'wp_list_pages',
		'wp_list_categories',
		'the_content_more_link',
		'the_tags',
		'the_author_posts_link',
		'post_link',
		'post_type_link',
		'page_link',
		'attachment_link',
		'get_shortlink',
		'post_type_archive_link',
		'get_pagenum_link',
		'get_comments_pagenum_link',
		'term_link',
		'search_link',
		'day_link',
		'month_link',
		'year_link',
		'option_siteurl',
		'blog_option_siteurl',
		'option_home',
		'admin_url',
		'get_admin_url',
		'get_site_url',
		'network_admin_url',
		'home_url',
		'includes_url',
		'site_url',
		'site_option_siteurl',
		'network_home_url',
		'network_site_url',
		'get_the_author_url',
		'get_comment_link',
		'wp_get_attachment_image_src',
		'wp_get_attachment_thumb_url',
		'wp_get_attachment_url',
		'wp_login_url',
		'wp_logout_url',
		'wp_lostpassword_url',
		'get_stylesheet_uri',
		'get_locale_stylesheet_uri',
		'script_loader_src',
		'style_loader_src',
		'get_theme_root_uri',
		'theme_root_uri',
		'plugins_url',
		'stylesheet_directory_uri',
		'template_directory_uri'
	);

	foreach ( $filters as $filter ) {
		add_filter( $filter, 'lbf_make_link_tunnel' );
	}
}

function lbf_send_private_cache_control_header() {
	header( 'Cache-Control: private' );
}

function lbf_make_links_ngrok( $template ) {
	if ( empty( $_SERVER['HTTP_X_ORIGINAL_HOST'] ) ) {
		return $template;
	}

	ob_start( 'lbf_make_link_tunnel' );

	return $template;
}

function lbf_make_link_tunnel( $str ) {
	global $tunnel_home_domain;

	$original_domain = str_replace( '/^www./', '', $_SERVER['HTTP_HOST'] );

	/**
	 * If using localhost and the port isn't in HTTP_HOST then it needs to be re-added.
	 */
	if ( $original_domain === 'localhost' ) {
		$original_domain .= ':' . $_SERVER['SERVER_PORT'];
	}

	$tunnel_domain = $_SERVER['HTTP_X_ORIGINAL_HOST'];

	$str = str_replace( 'www.' . $original_domain, $tunnel_domain, $str );
	$str = str_replace( $original_domain, $tunnel_domain, $str );

	$str = str_replace( 'www.' . $tunnel_home_domain, $tunnel_domain, $str );
	$str = str_replace( $tunnel_home_domain, $tunnel_domain, $str );

	$str = str_replace( 'http://' . $tunnel_domain, 'https://' . $tunnel_domain, $str );

	return $str;
}

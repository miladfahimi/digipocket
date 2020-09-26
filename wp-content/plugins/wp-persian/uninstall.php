<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$wpp_options=array(
	'wpp_installed_version',
	'wpp_adminpanel_locale',
	'wpp_adminpanel_convert_date',
	'wpp_adminpanel_thousands_sep',
	'wpp_adminpanel_decimal_point',
	'wpp_tinymce_bidi_buttons',
	'wpp_tinymce_css',
	'wpp_adminpanel_context',
	'wpp_adminpanel_numbers_post_content',
	'wpp_adminpanel_numbers_post_excerpt',
	'wpp_adminpanel_numbers_post_title',
	'wpp_adminpanel_numbers_get_term',
	'wpp_adminpanel_numbers_comment',
	'wpp_adminpanel_numbers_date_i18n',
	'wpp_adminpanel_letters',
	'wpp_adminpanel_numbers_format_i18n',
	'wpp_frontpage_thousands_sep',
	'wpp_frontpage_decimal_point',
	'wpp_frontpage_locale',
	'wpp_frontpage_convert_date',
	'wpp_convert_permalink',
	'wpp_frontpage_numbers_the_content',
	'wpp_frontpage_numbers_wp_title',
	'wpp_frontpage_numbers_the_excerpt',
	'wpp_frontpage_numbers_comment_text',
	'wpp_frontpage_numbers_comments_number',
	'wpp_frontpage_numbers_the_title',
	'wpp_frontpage_numbers_wp_list_categories',
	'wpp_frontpage_numbers_date_i18n',
	'wpp_frontpage_numbers_format_i18n',
	'wpp_frontpage_letters',
    'widget_wpp_jarchive',
    'widget_wpp_jcalendar'
);

foreach ($wpp_options as $opt) {
	delete_option( $opt );
	delete_site_option( $opt );
}


<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WPP_Hooks{

	public static $translate;


	public static function wpp_set_locale($locale)
    {
        global $locale, $wp_persian;
        if (is_admin()) {
            $locale = $wp_persian->adminpanel_locale;

            if (!empty($wp_persian->user_locale))
                $locale = $wp_persian->user_locale;

        }else {
            $locale = $wp_persian->frontpage_locale;
        }

        if(empty($locale))
            $locale='en_US';

        return $locale;
    }


    public static function wpp_date_i18n( $j, $req_format, $i, $gmt ) {
        $num_lang = 'en';
        if ( is_admin() && get_option( 'wpp_adminpanel_numbers_date_i18n' ) ) {
            $num_lang = 'fa';
        }
        if ( get_option( 'wpp_frontpage_numbers_date_i18n' ) ) {
            $num_lang = 'fa';
        }

        if (function_exists('debug_backtrace')) {
            $callers = debug_backtrace();
            $disable_jdate = apply_filters("wpp_disable_jalali_date", $callers);
            if($disable_jdate===true)return $j;
        }

        return wpp_jdate( $req_format, $i,  $num_lang );
        //$j = wpp_jdate( $req_format, $i,  $num_lang );
        //return $j;
    }


	public static function wpp_date_formats($formats) {
		return array_merge( $formats, array(
			'd F Y',
			'Y/m/d',
			'd p Y',
		) );
	}

	public static function wpp_post_link($old_perma, $post, $leavename) {

		/* Detecting $leavename 2.5+ */
		$leavename = ((strpos($old_perma, '%postname%') !== false) || (strpos($old_perma, '%pagename%') !== false));
		$rewritecode = array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			$leavename? '' : '%postname%',
			'%post_id%',
			'%category%',
			'%author%',
			$leavename? '' : '%pagename%',
			//'%lang%'
		);

		if ( empty($post->ID) ) return FALSE;

		if ( $post->post_type == 'page' )
			return get_page_link($post->ID);
		elseif ($post->post_type == 'attachment')
			return get_attachment_link($post->ID);

		$permalink = get_option('permalink_structure');

		// if they're not using the fancy permalink option
		if ( '' == $permalink || in_array($post->post_status, array('draft', 'pending', 'auto-draft', 'future')) )
		{
			$permalink = get_option('home') . '/?p=' . $post->ID;
			return $permalink;
		}

		$unixtime = strtotime($post->post_date);

		$category = '';
		if ( strpos($permalink, '%category%') !== false ) {
			$cats = get_the_category($post->ID);
			if ( $cats )
                usort($cats, 'wp_list_sort'); // order by ID
				//usort($cats, '_usort_terms_by_ID');
			$category = $cats[0]->slug;
			if ( $parent=$cats[0]->category_parent )
				$category = get_category_parents($parent, FALSE, '/', TRUE) . $category;
		}

		if ( empty($category) ) {
			$default_category = get_category( get_option( 'default_category' ) );
			$category = is_wp_error( $default_category)? '' : $default_category->slug;
		}

		$author = '';
		if ( strpos($permalink, '%author%') !== false ) {
			$authordata = get_userdata($post->post_author);
			$author = $authordata->user_nicename;
		}

		//$timezone=get_option('timezone_string');
		//$date = explode(" ",jdate('Y m d H i s', $unixtime,'',get_option('timezone_string'),'en'));
        $date = explode(" ",wpp_jdate('Y m d H i s', $unixtime,'en'));

		$rewritereplace =
		array(
			$date[0],
			$date[1],
			$date[2],
			$date[3],
			$date[4],
			$date[5],
			$post->post_name,
			$post->ID,
			$category,
			$author,
			$post->post_name,
			//get_locale()
		);
		$permalink = get_option('home') . str_replace($rewritecode, $rewritereplace, $permalink);
		$permalink = user_trailingslashit($permalink, 'single');
		return $permalink;

	}

	public static function wpp_mce_buttons($buttons) {
		array_push($buttons, "separator", "ltr", "rtl");
		return $buttons;
	}

	public static function wpp_mce_external_plugins($plugin_array) {
		$plugin_array['directionality'] = includes_url('js/tinymce/plugins/directionality/plugin.min.js');
		return $plugin_array;
	}

	public static function wpp_mce_css($stylesheets) {
        $stylesheets.=','.WPP_URL. "assets/css/tinymce.css";
		return $stylesheets;
	}

    /**
     * Enqueue scripts for all admin pages.
     */
    public static function wpp_admin_enqueue_scripts()
    {
        if (get_option('wpp_adminpanel_context')) {
            wp_enqueue_style('wpp-context', WPP_URL . 'assets/css/wpp-context.css');
            wp_enqueue_script('wpp-context', WPP_URL . 'assets/js/wpp-context.js');
        }
        wp_enqueue_style('wp-persian', WPP_URL . 'assets/css/wp-persian.css');
        wp_enqueue_script('wpp-jalali', WPP_URL . 'assets/js/wpp-jalali.js');

        wp_enqueue_script('wp-persian', WPP_URL . 'assets/js/wp-persian.js');
        //wp_enqueue_script('wpp-wordpress5', WPP_URL . 'assets/js/wordpress5.js');


        if(get_option( 'wpp_adminpanel_datepicker' )) {
            wp_enqueue_style('wpp-persian-datepicker', WPP_URL . 'assets/css/persianDatepicker-default.css');
            //wp_enqueue_script('wpp-persian-datepicker', WPP_URL . 'assets/js/persianDatepicker.min.js');
            wp_enqueue_script('wpp-persian-datepicker', WPP_URL . 'assets/js/persianDatepicker.js');
        }


        wp_enqueue_style('wpp-fonts', WPP_URL . 'assets/css/fonts.css');
        add_action('admin_head', array('WPP_Hooks','DynamicStyle'));

    }

    public static function DynamicStyle()
    {
        ob_start();
        include_once( WPP_DIR . 'assets/css/dynamic-css.php' );
        $code = ob_get_contents();
        ob_end_clean();
        // Remove Comments
        $code = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code );
        // Remove tabs, spaces, newlines, etc.
        $code = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $code );
        echo '<style rel="stylesheet" type="text/css" title="wpp-dynamic-css"  media="all">' . $code . '</style>';

    }

    public static function wpp_disable_months_dropdown( $false , $post_type ) {
        $disable_months_dropdown = $false;
        //$disable_post_types = array( 'post' , 'page' );
        $disable_post_types = array( 'post' , 'page', 'attachment' );
        if( in_array( $post_type , $disable_post_types ) ) {
            $disable_months_dropdown = true;
        }
        return $disable_months_dropdown;
    }

	/**
	* generate date filter in upload.php & edit.php
	*/
	public static function wpp_restrict_manage_posts()
	{
		global $post_type, $wpdb;

		$months = $wpdb->get_results( $wpdb->prepare( "
			SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month, DAY( post_date ) as day
			FROM $wpdb->posts
			WHERE post_type = %s AND post_status <> 'auto-draft'
			ORDER BY post_date DESC
			", $post_type ) );
		$month_count = count( $months );
		if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
			return;
		$m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0;

		$predate=0;
		echo '<select name="m">';
		echo "<option ".selected( $m, 0 ,false)." value='0'>".__( 'Show all dates','wp-persian' )."</option>\n";
		foreach ( $months as $arc_row ) {
				if ( 0 == $arc_row->year )
					continue;
				$month = zeroise( $arc_row->month, 2 );
				$year = $arc_row->year;
				$gmt = mktime(0 ,0 , 0, $month, $arc_row->day, $year);
				$dateshow = wpp_jdate('F Y',$gmt);
				$date = wpp_jdate('Ym',$gmt,'en');
				if($predate != $date)
					printf( "<option %s value='%s'>%s</option>\n",
						selected( $m, $date, false ),
						esc_attr( $date ),
						$dateshow
					);
				$predate = $date;
		}
		echo '</select>';
	}

    //jalali date in media library
    public static function wpp_media_library_months_with_files()
    {
        global $wpdb,$wp_locale;

        for ($i=1;$i<=12;$i++) {
            $wp_locale->month[str_pad(strval($i),2,'0',STR_PAD_LEFT)] = wpp_jmonth_name($i);
            $wp_locale->month_abbrev[wpp_jmonth_name($i)] = wpp_jmonth_name($i);
        }


        $months = $wpdb->get_results( $wpdb->prepare( "
			SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month, DAY( post_date ) as day
			FROM $wpdb->posts
			WHERE post_type = %s
			ORDER BY post_date DESC", 'attachment' ) );
        $pre_date='';
        foreach ( $months as $k=>$month_year ) {
            if($pre_date==$month_year->year.$month_year->month){
                unset($months[$k]);
                continue;
            }
            $pre_date=$month_year->year.$month_year->month;
            list($month_year->year, $month_year->month, )=wpp_gregorian_to_jalali($month_year->year,$month_year->month,$month_year->day);

        }
        return $months;

    }
    //jalali date in media library
    public static function wpp_ajax_query_attachments_args($query = array()){
	    if(!isset($query['year']) || !isset($query['monthnum']) || empty($query['year']) || $query['year']=='false' || $query['monthnum']=='false') {
	        return $query;
        }

        $month=$query['monthnum'];
        $year=$query['year'];

        $jtime=wpp_jmktime( 0, 0, 0, $month, 1, $year );
        $after = date( 'Y-m-d H:i:s', $jtime );
        $kab=($year%33%4-1==(int)($year%33*.05))?1:0;
        $extra = ( $month <= 6 ) ? $extra = 31 * 24 * 3600 : ( ( $month == 12 && ! $kab ) ? 29 * 24 * 3600 : 30 * 24 * 3600 );
        $before = date( 'Y-m-d H:i:s', $jtime + $extra );
        $query['date_query']=array('after'=>$after,'before'=>$before);
        $query['monthnum']='';
        $query['year']='';

        return $query;
    }

    public static function wpp_plugin_row_meta( $links, $file ) {
        if ( plugin_basename( WPP_FILE ) == $file ) {
            $row_meta = array(
                'docs'    => '<a href="' . esc_url( 'http://www.30yavash.com/tag/wp-persian/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Online Documents', 'wp-persian' ) . '" style="color:green;">' . esc_html__( 'Documents', 'wp-persian' ) . '</a>'
            );

            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }

	/**
	 * translate jalali permalink
	 *
	 * @param WP_Query $query
	 */
	public static function wpp_filter_posts($query) {

		if ( !is_admin() && !$query->is_main_query() ) return;

        $m      = $query->get( 'm' );
        $hour   = $query->get( 'hour' );
        $minute = $query->get( 'minute' );
        $second = $query->get( 'second' );
        $year   = $query->get( 'year' );
        $month  = $query->get( 'month' );
        $month  = $query->get( 'monthnum' );
        $day    = $query->get( 'day' );

        if ( ! empty( $m ) ) {
            $len  = strlen( $m );
            $year = substr( $m, 0, 4 );
            if ( $len > 5 ) {
                $month = substr( $m, 4, 2 );
            }
            if ( $len > 7 ) {
                $day = substr( $m, 6, 2 );
            }
            if ( $len > 9 ) {
                $hour = substr( $m, 8, 2 );
            }
            if ( $len > 11 ) {
                $minute = substr( $m, 10, 2 );
            }
            if ( $len > 13 ) {
                $second = substr( $m, 12, 2 );
            }
        }
        //gregorian date not jalali
        if ( empty( $year ) || $year > 1700 ) {
            return;
        }

        $kab=($year%33%4-1==(int)($year%33*.05))?1:0;
        $after=date('Y-m-d H:i:s',wpp_jmktime(0,0,0,1,1,$year));
        $before=date('Y-m-d H:i:s',wpp_jmktime(0,0,0,1,1,$year+1));

        if (!empty($month)) {
            $jtime=wpp_jmktime( 0, 0, 0, $month, 1, $year );
            $after = date( 'Y-m-d H:i:s', $jtime );
            $extra = ( $month <= 6 ) ? $extra = 31 * 24 * 3600 : ( ( $month == 12 && ! $kab ) ? 29 * 24 * 3600 : 30 * 24 * 3600 );
            $before = date( 'Y-m-d H:i:s', $jtime + $extra );
        }
        if (!empty($day)) {
            $jtime=wpp_jmktime( 0, 0, 0, $month, $day, $year ) ;
            $after = date( 'Y-m-d H:i:s', $jtime );
            $extra = 1 * 24 * 3600;
            $before = date( 'Y-m-d H:i:s', $jtime + $extra );
        }
        if (!empty($hour)) {
            $jtime=wpp_jmktime( $hour, 0, 0, $month, $day, $year ) ;
            $after = date( 'Y-m-d H:i:s', $jtime );
            $extra = 1 * 3600;
            $before = date( 'Y-m-d H:i:s', $jtime + $extra );
        }
        if (!empty($minute)) {
            $jtime=wpp_jmktime( $hour, $minute, 0, $month, $day, $year ) ;
            $after = date( 'Y-m-d H:i:s', $jtime );
            $extra = 1 * 60;
            $before = date( 'Y-m-d H:i:s', $jtime + $extra );
        }
        if (!empty($second)) {
            $jtime=wpp_jmktime( $hour, $minute, $second, $month, $day, $year ) ;
            $after = date( 'Y-m-d H:i:s', $jtime );
            $extra = 1 ;
            $before = date( 'Y-m-d H:i:s', $jtime + $extra );
        }

        $date_query = array(
            array(
                'after'  => $after,
                'before' => $before
            )

        );
        $query->set( 'date_query', $date_query );

	}
	// fix WHERE clause of the query for jalali permalink

    public static function wpp_jalali_query($where) {
		global $wpdb,$wp_query;

		$m      = $wp_query->get( 'm' );
		$year   = $wp_query->get( 'year' );
		$month  = $wp_query->get( 'monthnum' );
		$day    = $wp_query->get( 'day' );
		$hour   = $wp_query->get( 'hour' );
		$minute = $wp_query->get( 'minute' );
		$second = $wp_query->get( 'second' );

		if ( $m != '' && $m != '0' ) {
			$m  = preg_replace( '/[^0-9]/', '', $m );
			$year   = substr( $m, 0, 4 );
			$month  = substr( $m, 4, 2 );
			$day    = substr( $m, 6, 2 );
			$hour   = substr( $m, 8, 2 );
			$minute = substr( $m, 10, 2 );
			$second = substr( $m, 12, 2 );
		}

		if ( $year < 1700 && $year > 1300 ) {
			$patterns = array(
				"/YEAR\([\s]*$wpdb->posts.post_date[\s]*\)[\s]*=[\s]*" . $year . "/",
                "/DAYOFMONTH\([\s]*$wpdb->posts\.post_date[\s]*\)[\s]*=[\s]*" . $day . "/",
				"/MONTH\([\s]*$wpdb->posts.post_date[\s]*\)[\s]*=[\s]*" . $month . "/",
				"/HOUR\([\s]*$wpdb->posts.post_date[\s]*\)[\s]*=[\s]*" . $hour . "/",
				"/MINUTE\([\s]*$wpdb->posts.post_date[\s]*\)[\s]*=[\s]*" . $minute . "/",
				"/SECOND\([\s]*$wpdb->posts.post_date[\s]*\)[\s]*=[\s]*" . $second . "/"
			);

			$where = preg_replace( $patterns, "1=1", $where );

		}

		return $where;
	}


	public static function wpp_load_editphp() {
        add_filter( "pre_get_posts", array( 'WPP_Hooks', 'wpp_filter_posts' ) );

        add_filter('posts_where', array('WPP_Hooks','wpp_jalali_query') );



	}


	public static function wpp_load_options_general() 
	{
		if(get_option('wpp_adminpanel_convert_date')==false && get_option('wpp_frontpage_convert_date'))
			add_filter("date_i18n", array('WPP_Hooks','wpp_date_i18n'), 10, 4 );
		elseif(get_option('wpp_adminpanel_convert_date') && get_option('wpp_frontpage_convert_date')==false)
			remove_filter("date_i18n", array('WPP_Hooks','wpp_date_i18n') );
	}

	public  static function wpp_get_term($term) {
		if ( $term instanceof WP_Term ) {
			if ( get_option( 'wpp_adminpanel_numbers_get_term' ) ) {
				$term->name = wpp_numbers_en2fa( $term->name );
			}
			if ( get_option( 'wpp_adminpanel_letters' ) ) {
				$term->name = wpp_letters_ar2fa( $term->name );
			}
		}
		return $term;
	}

	/**
	 * @param mixed $comment
	 *
	 * @return mixed
	 */
	public  static function wpp_comment($comment) {
		if ( $comment instanceof WP_Comment ) {
			if ( get_option( 'wpp_adminpanel_numbers_comment' ) ) {
				$comment->comment_content = wpp_numbers_en2fa( $comment->comment_content );
			}
			if ( get_option( 'wpp_adminpanel_letters' ) ) {
				$comment->comment_content = wpp_letters_ar2fa( $comment->comment_content );
			}
		}elseif (is_string($comment) && !empty($comment)){
			if ( get_option( 'wpp_adminpanel_numbers_comment' ) ) {
				$comment = wpp_numbers_en2fa( $comment );
			}
			if ( get_option( 'wpp_adminpanel_letters' ) ) {
				$comment = wpp_letters_ar2fa( $comment );
			}
		}
		return $comment;
	}

	private static function _wpp_convert_post_letters($post){
		if ( get_option('wpp_adminpanel_numbers_post_title')) {
			$post->post_title = wpp_numbers_en2fa( $post->post_title );
			$post->post_name=wpp_numbers_en2fa( $post->post_name );
		}
		if ( get_option('wpp_adminpanel_numbers_post_content')) {
			$post->post_content = wpp_numbers_en2fa( $post->post_content );
		}
		if ( get_option('wpp_adminpanel_numbers_post_excerpt')) {
			$post->post_excerpt = wpp_numbers_en2fa( $post->post_excerpt );
		}
		if ( get_option('wpp_adminpanel_letters')) {
			$post->post_content = wpp_letters_ar2fa( $post->post_content );
			$post->post_title   = wpp_letters_ar2fa( $post->post_title );
			$post->post_excerpt = wpp_letters_ar2fa( $post->post_excerpt );
		}
		return $post;
	}

	public  static function wpp_edit_form_top($post){

		if ( $post instanceof WP_Post) {
			$post=self::_wpp_convert_post_letters( $post );
		}
		return $post;
	}

	public  static function wpp_save_post($post_id,$post) {
		if ( $post instanceof WP_Post ) {
			$post=self::_wpp_convert_post_letters( $post );
		}
		return $post;
	}


    public  static function wpp_woocommerce_before_save_order_items($order_id, $items) {
/*
        $items['order_date']=wpp_numbers_to_english($items['order_date']);
        list( $jyear, $jmonth, $jday ) = explode( '-', $items['order_date'] );
        if(intval($jyear)<1700) {
            list($gyear, $gmonth, $gday) = wpp_jalali_to_gregorian($jyear, $jmonth, $jday);
            $items['order_date'] = $gyear . '-' . str_pad($gmonth,2, "0", STR_PAD_LEFT) . '-' . str_pad($gday,2 ,"0",STR_PAD_LEFT);
        }
	    $items['order_date_hour']=wpp_numbers_to_english($items['order_date_hour']);
        $items['order_date_minute']=wpp_numbers_to_english($items['order_date_minute']);
        $items['order_date_second']=wpp_numbers_to_english($items['order_date_second']);
        //return $items;
*/
    }
}



<?php
/**
 * Created by Siavash Salemi
 * Date: 23/07/2016
 * Time: 12:54 AM
 */


if ( ! defined( 'ABSPATH' ) ) exit;


function wpp_get_jarchives( $args = '' ) {
	global $wpdb;
	$args = wp_parse_args( $args, array(
		'limit'           => '',
		'format'          => 'html',
		'before'          => '',
		'after'           => '',
		'show_post_count' => 0
	) );

	$limit           = $args['limit'];
	$format          = $args['format'];
	$before          = $args['before'];
	$after           = $args['after'];
	$show_post_count = $args['show_post_count'];

	if ( '' != $limit && 0 != $limit ) {
		$limit = (int) $limit;
	}


	$last_changed = wp_cache_get( 'last_changed', 'posts' );
	if ( ! $last_changed ) {
		$last_changed = microtime();
		wp_cache_set( 'last_changed', $last_changed, 'posts' );
	}


	$query = "SELECT DATE( post_date ) AS date, count(ID) as posts
					FROM $wpdb->posts
					WHERE post_type = 'post' AND post_date < NOW() AND post_status = 'publish'
					GROUP BY date
					ORDER BY post_date DESC";

	$key = md5( $query );
	$key = "wpp_get_jarchives:$key:$last_changed";
	if ( ! $results = wp_cache_get( $key, 'posts' ) ) {
		$results = $wpdb->get_results( $query );
		wp_cache_set( $key, $results, 'posts' );
	}

	$output = '';
	if ( $results ) {
		$l        = 1;
		$pre_date = 0;
		$posts    = $results[0]->posts;
		$archives = array();
		foreach ( (array) $results as $result ) {
			$date = wpp_jdate( 'Ym', $result->date, 'en' );
			if ( $pre_date == $date ) {
				$posts += $result->posts;
			} else {
				$posts = $result->posts;
			}
			$pre_date = $date;

			$archives[ $date ] = array( 'date' => $date, 'posts' => $posts );

			if ( $l == $limit ) {
				break;
			}
			$l ++;
		}

		foreach ( $archives as $archive ) {
			$post_count = '';
			$year       = substr( $archive['date'], 0, 4 );
			$month      = substr( $archive['date'], 4, 2 );
			$text       = wpp_jdate( 'F Y', wpp_jmktime( 0, 0, 0, $month, 1, $year ) );
			$url        = get_month_link( $year, $month );
			if ( $show_post_count ) {
				$post_count = '&nbsp;(' . wpp_numbers_en2fa( $archive['posts'] ) . ')';
			}
			$output .= get_archives_link( $url, $text, $format, $before, $post_count . $after );

		}

		echo $output;
	}
}


function get_jcalendar( $initial = true, $echo = true ) {
	global $wpdb, $monthnum, $year, $day, $posts;
	$m = get_query_var( 'm' );

	$key   = md5( $m . $monthnum . $year );
	$cache = wp_cache_get( 'get_jcalendar', 'jcalendar' );


	if ( $cache && is_array( $cache ) && isset( $cache[ $key ] ) ) {
		$output = apply_filters( 'get_jcalendar', $cache[ $key ] );
		if ( $echo ) {
			echo $output;
			return;
		}
		return $output;
	}

	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	// Quick check. If we have no posts at all, abort!
	if ( ! $posts ) {
		$gotsome = $wpdb->get_var( "SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' LIMIT 1" );
		if ( ! $gotsome ) {
			return;
		}
	}


	$week_begins = (int) get_option( 'start_of_week' );


	if ( ! empty( $monthnum ) && ! empty( $year ) ) {
		$thismonth =   (int)$monthnum ;
		$thisyear  = (int)$year;
	} elseif ( ! empty( $m ) ) {
		//$calendar = substr($m, 0, 6);
		$thisyear =  (int) substr( $m, 0, 4 );
		if ( strlen( $m ) < 6 ) {
			$thismonth = '01';
		} else {
			$thismonth =  intval( substr( $m, 4, 2 ) );
		}
	} else {
		list( $thisyear, $thismonth, $thisday ) = explode( '-', gmdate( 'Y-m-d', current_time( 'timestamp' ) + get_option( 'gmt_offset' ) * 3600 ) );
	}

	if($thisyear>1700){
		list( $jthisyear, $jthismonth ) = wpp_gregorian_to_jalali( $thisyear, $thismonth, $thisday );
	}else {
		$jthisyear  = $thisyear;
		$jthismonth = $thismonth;
	}

	$unixmonth = wpp_jmktime( 0, 0, 0, $jthismonth, 1, $jthisyear );


	$calendar_caption = __( '%1$s %2$s', 'wp-persian' );
	$calendar_output  = '<table id="wp-calendar" style="direction: rtl">
	    <caption>' . sprintf(
			$calendar_caption, wpp_jmonth_name( $jthismonth ),
			wpp_jdate( 'Y', $unixmonth ) ) . '</caption><thead><tr>';


	$myweek = array();

	for ( $wdcount = 0; $wdcount <= 6; $wdcount ++ ) {
		//$myweek[]=jdayshortname(($wdcount+$week_begins)%7);
		//$myweek[]= $wpp_locale->get_weekday(($wdcount+$week_begins)%7);
		$myweek[] = ( $wdcount + $week_begins ) % 7;
	}

	foreach ( $myweek as $wd ) {
		$day_name = $initial ? wpp_jweekday_initial( $wd ) : wpp_jweekday_abbrev( $wd );
		$wd       = esc_attr( $wd );
		$calendar_output .= "\n\t\t<th abbr=\"$wd\" scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	$calendar_output .= '
	</tr>
	</thead>

	<tfoot>
	<tr>';


	$jnextmonth = $jthismonth + 1;
	$jnextyear  = $jthisyear;
	if ( $jnextmonth > 12 ) {
		$jnextmonth = 1;
		$jnextyear ++;
	}

	$g_startdate = date( "Y-m-d H:i:s", wpp_jmktime( 0, 0, 0, $jthismonth, 1, $jthisyear ) );
	$g_enddate   = date( "Y-m-d H:i:s", wpp_jmktime( 0, 0, 0, $jnextmonth, 1, $jnextyear ) );
	//$g_enddate   = date( "Y-m-d H:i:s", wpp_jmktime( 23, 59, 59, $jthismonth, 31, $jthisyear ) );
	// Get the next and previous month and year with at least one post
	$prev = $wpdb->get_row( $wpdb->prepare("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year, DAYOFMONTH(post_date) AS day
 		FROM $wpdb->posts
    	WHERE post_date < %s
		AND post_type='post' AND post_status = 'publish'
			ORDER BY post_date DESC
			LIMIT 1",$g_startdate) );

	$next = $wpdb->get_row( $wpdb->prepare("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year, DAYOFMONTH(post_date) AS day
 		FROM $wpdb->posts
    	WHERE post_date > %s
		AND post_type='post' AND post_status = 'publish'
			ORDER BY post_date DESC		
			LIMIT 1",$g_enddate) );

	if ( $prev ) {
		$jalali_prev = wpp_gregorian_to_jalali( $prev->year, $prev->month, $prev->day );
		$calendar_output .= "\n\t\t" . '<td colspan="3" id="prev"><a href="' . get_month_link( $jalali_prev[0], $jalali_prev[1] ) . '" ' . '">&laquo; ' .
		                    esc_attr(wpp_jmonth_name( $jalali_prev[1] )) .
		                    '</a></td>';
	} else {
		$calendar_output .= "\n\t\t" . '<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	$calendar_output .= "\n\t\t" . '<td class="pad">&nbsp;</td>';

	if ( $next ) {
		$jalali_next = wpp_gregorian_to_jalali( $next->year, $next->month, $next->day );
		$calendar_output .= "\n\t\t" . '<td colspan="3" id="next"><a href="' .
		                    get_month_link( $jalali_next[0], $jalali_next[1] ) . '">' . esc_attr(wpp_jmonth_name( $jalali_next[1] )) .
		                    ' &raquo;</a></td>';
	} else {
		$calendar_output .= "\n\t\t" . '<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}

	$calendar_output .= '</tr></tfoot><tbody><tr>';


	// Get days with posts
	$dayswithposts = $wpdb->get_results( "SELECT DISTINCT DAYOFMONTH(post_date),MONTH(post_date),YEAR(post_date)
            FROM $wpdb->posts 
        	WHERE post_type='post' AND post_status = 'publish'
            AND post_date >= '$g_startdate' AND post_date <= '$g_enddate'", ARRAY_N );
	if ( $dayswithposts ) {
		foreach ( $dayswithposts as $daywith ) {
			$daywithpost[] = wpp_jdate( "j", mktime( 0, 0, 0, $daywith[1], $daywith[0], $daywith[2] ), 'en' );
		}
	} else {
		$daywithpost = array();
	}


	// See how much we should pad in the beginning
	$pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
	if ( 0 != $pad ) {
		$calendar_output .= "\n\t\t" . '<td colspan="' . esc_attr( $pad ) . '" class="pad">&nbsp;</td>';
	}

	$daysinmonth = intval( wpp_jdate( 't', $unixmonth, 'en' ) );
	for ( $day = 1; $day <= $daysinmonth; ++ $day ) {
		list( $thisyear, $thismonth, $thisday ) = wpp_jalali_to_gregorian( $jthisyear, $jthismonth, $day );
		if ( isset( $newrow ) && $newrow ) {
			$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
		}
		$newrow = false;
		if ( $thisday == gmdate( 'j', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) && $thismonth == gmdate( 'm', time() + ( get_option( 'gmt_offset' ) * 3600 ) ) && $thisyear == gmdate( 'Y', time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) {
			$calendar_output .= '<td id="today">';
		} else {
			$calendar_output .= '<td>';
		}

        $day_text = wpp_numbers_en2fa( $day );

		if ( in_array( $day, $daywithpost ) ) {
			// any posts today?
			$calendar_output .= '<a href="' . get_day_link( $jthisyear, $jthismonth, $day ) . "\" >$day_text</a>";
		} else {
			$calendar_output .= $day_text;
		}
		$calendar_output .= '</td>';

		if ( 6 == calendar_week_mod( date( 'w', wpp_jmktime( 0, 0, 0, $jthismonth, $day, $jthisyear ) ) - $week_begins ) ) {
			$newrow = true;
		}
	}

	$pad = 7 - calendar_week_mod( date( 'w', wpp_jmktime( 0, 0, 0, $jthismonth, $day, $jthisyear ) ) - $week_begins );
	if ( $pad != 0 && $pad != 7 ) {
		$calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . $pad . '">&nbsp;</td>';
	}

	$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

	$cache[ $key ] = $calendar_output;
	wp_cache_set( 'get_jcalendar', $cache, 'jcalendar' );

	if ( $echo ) {
		/**
		 * Filters the HTML jalali calendar output.
		 *
		 * @since 2.1.1
		 *
		 * @param string $calendar_output HTML output of the calendar.
		 */
		echo apply_filters( 'get_jcalendar', $calendar_output );

		return;
	}

	/** This filter is documented in wp-includes/general-template.php */
	return apply_filters( 'get_jcalendar', $calendar_output );
}



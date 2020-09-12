<?php

namespace FSPoster\App\Pages\Schedules\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;

class Action
{
	public function get_list ()
	{
		$schedules = DB::DB()->get_results( DB::DB()->prepare( 'SELECT *, (SELECT COUNT(0) FROM `' . DB::table( 'feeds' ) . '` WHERE `schedule_id`=tb1.id and `is_sended`=1) AS `shares_count` FROM `' . DB::table( 'schedules' ) . '` tb1 WHERE `user_id`=%d AND `blog_id`=%d', [
			get_current_user_id(),
			Helper::getBlogId()
		] ), ARRAY_A );

		$names_array1 = [
			'random2'   => 'Randomly ( without dublicates )',
			'random'    => 'Randomly',
			'old_first' => 'Old posts first',
			'new_first' => 'New posts first'
		];

		$names_array2 = [
			'all'              => 'All posts',
			'this_week'        => 'This week added posts',
			'previously_week'  => 'Previous week added posts',
			'this_month'       => 'This month added posts',
			'previously_month' => 'Previous month added posts',
			'this_year'        => 'This year added posts',
			'last_30_days'     => 'Last 30 days',
			'last_60_days'     => 'Last 60 days',
		];

		return [
			'schedules'   => $schedules,
			'namesArray1' => $names_array1,
			'namesArray2' => $names_array2
		];
	}
}
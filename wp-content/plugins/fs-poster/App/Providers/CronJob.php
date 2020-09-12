<?php

namespace FSPoster\App\Providers;

class CronJob
{
	private static $reScheduledList = [];
	/**
	 * @var BackgrouondProcess
	 */
	private static $backgroundProcess;

	public static function init ()
	{
		self::$backgroundProcess = new BackgrouondProcess();

		$fsCronJobLastRunTime = Helper::getOption( 'cron_job_runned_on', 0 );
		$fsCronJobLastRunTime = is_numeric( $fsCronJobLastRunTime ) ? $fsCronJobLastRunTime : 0;

		$runTasksEvery = 30; //sec.
		$diff          = Date::epoch() - $fsCronJobLastRunTime;
		if ( $diff > $runTasksEvery )
		{
			if ( defined( 'DOING_CRON' ) )
			{
				add_action( 'init', function () {
					Helper::setOption( 'cron_job_runned_on', Date::epoch() );

					set_time_limit( 0 );
					ShareService::shareQueuedFeeds();
					ShareService::shareSchedules();

					if ( Helper::getOption( 'check_accounts', 1 ) )
					{
						AccountService::checkAccounts();
					}
				} );
			}
			else
			{
				if ( ! self::isThisProcessBackgroundTask() )
				{
					Helper::setOption( 'cron_job_runned_on', Date::epoch(), FALSE, FALSE );
					self::runBackgroundTaksIfNeeded();
				}
			}
		}
	}

	public static function runBackgroundTaksIfNeeded ()
	{
		$notSendedFeeds = DB::DB()->prepare( 'SELECT COUNT(0) as `feed_count` FROM `' . DB::table( 'feeds' ) . '` WHERE `share_on_background`=1 and `is_sended`=0 and `send_time`<=%s', [ Date::dateTimeSQL() ] );
		$notSendedFeeds = DB::DB()->get_row( $notSendedFeeds, ARRAY_A );

		if ( $notSendedFeeds[ 'feed_count' ] > 0 )
		{
			add_action( 'init', function () {
				self::$backgroundProcess->dispatch();
			} );
		}
		else
		{
			// check for schedules...
			$schdules = DB::DB()->prepare( 'SELECT COUNT(0) as `schedule_count` FROM `' . DB::table( 'schedules' ) . '` WHERE `status`=\'active\' and `next_execute_time`<=%s', [ Date::dateTimeSQL() ] );
			$schdules = DB::DB()->get_row( $schdules, ARRAY_A );

			if ( $schdules[ 'schedule_count' ] > 0 )
			{
				add_action( 'init', function () {
					self::$backgroundProcess->dispatch();
				} );
			}
			else
			{
				$notCheckedAccounts = DB::DB()->prepare( 'SELECT COUNT(0) as `account_count` FROM ' . DB::table( 'accounts' ) . ' WHERE ((id IN (SELECT account_id FROM ' . DB::table( 'account_status' ) . ')) OR (id IN (SELECT account_id FROM ' . DB::table( 'account_nodes' ) . ' WHERE id IN (SELECT node_id FROM ' . DB::table( 'account_node_status' ) . ')))) AND (`last_check_time` is NULL OR `last_check_time` < %s)', [ Date::dateTimeSQL( '-1 day' ) ] );
				$notCheckedAccounts = DB::DB()->get_row( $notCheckedAccounts, ARRAY_A );

				if ( $notCheckedAccounts[ 'account_count' ] > 0 )
				{
					add_action( 'init', function () {
						self::$backgroundProcess->dispatch();
					} );
				}
			}
		}
	}

	public static function isThisProcessBackgroundTask ()
	{
		$action = Request::get( 'action' );

		return $action === self::$backgroundProcess->getAction();
	}
}

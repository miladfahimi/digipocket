<?php

namespace FSPoster\App\Providers;

use WP_Async_Request;

class BackgrouondProcess extends WP_Async_Request
{
	/**
	 * @var string
	 */
	protected $action = 'fs_poster_background_process';

	/**
	 * Handle
	 *
	 * Override this method to perform any actions required
	 * during the async request.
	 */
	protected function handle ()
	{
		set_time_limit( 0 );

		ShareService::shareQueuedFeeds();
		ShareService::shareSchedules();

		if ( Helper::getOption( 'check_accounts', 1 ) )
		{
			AccountService::checkAccounts();
		}
	}

	public function getAction ()
	{
		return $this->action;
	}
}

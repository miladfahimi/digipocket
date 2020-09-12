<?php

namespace FSPoster\App\Providers;

class Session
{
	const PREFIX = 'fs_';

	/**
	 * Get session value...
	 *
	 * @param $key string
	 *
	 * @return mixed
	 */
	public static function get ( $key )
	{
		$userId = get_current_user_id();

		return get_user_meta( $userId, static::PREFIX . $key, TRUE );
	}

	/**
	 * Set session data...
	 *
	 * @param $key string
	 * @param $value mixed
	 */
	public static function set ( $key, $value )
	{
		$userId = get_current_user_id();

		self::remove( $key );

		add_user_meta( $userId, static::PREFIX . $key, $value, TRUE );
	}

	/**
	 * Unset session data...
	 *
	 * @param $key string
	 */
	public static function remove ( $key )
	{
		$userId = get_current_user_id();

		delete_user_meta( $userId, static::PREFIX . $key );
	}
}
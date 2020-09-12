<?php

namespace FSPoster\App\Providers;

/**
 * Class Request
 * @package FSPoster\App\Providers
 */
class Request
{
	/**
	 * @param $key
	 * @param null $default
	 * @param null $check_type
	 * @param array $whiteList
	 *
	 * @return mixed
	 */
	public static function post ( $key, $default = NULL, $check_type = NULL, $whiteList = [] )
	{
		$res = isset( $_POST[ $key ] ) ? $_POST[ $key ] : $default;

		if ( ! is_null( $check_type ) )
		{
			if ( $check_type === 'num' || $check_type === 'int' || $check_type === 'integer' )
			{
				$res = is_numeric( $res ) ? (int) $res : $default;
			}
			else
			{
				if ( $check_type === 'str' || $check_type === 'string' )
				{
					$res = is_string( $res ) ? stripslashes_deep( (string) $res ) : $default;
				}
				else
				{
					if ( $check_type === 'arr' || $check_type === 'array' )
					{
						$res = is_array( $res ) ? stripslashes_deep( (array) $res ) : $default;
					}
					else
					{
						if ( $check_type === 'float' )
						{
							$res = is_numeric( $res ) ? (float) $res : $default;
						}
					}
				}
			}
		}

		if ( ! empty( $whiteList ) && ! in_array( (string) $res, $whiteList ) )
		{
			$res = $default;
		}

		return $res;
	}

	/**
	 * @param $key
	 * @param null $default
	 * @param null $check_type
	 * @param array $whiteList
	 *
	 * @return mixed
	 */
	public static function get ( $key, $default = NULL, $check_type = NULL, $whiteList = [] )
	{
		$res = isset( $_GET[ $key ] ) ? $_GET[ $key ] : $default;

		if ( ! is_null( $check_type ) )
		{
			if ( $check_type === 'num' || $check_type === 'int' || $check_type === 'integer' )
			{
				$res = is_numeric( $res ) ? (int) $res : $default;
			}
			else
			{
				if ( $check_type === 'str' || $check_type === 'string' )
				{
					$res = is_string( $res ) ? (string) $res : $default;
				}
				else
				{
					if ( $check_type === 'arr' || $check_type === 'array' )
					{
						$res = is_array( $res ) ? (array) $res : $default;
					}
					else
					{
						if ( $check_type === 'float' )
						{
							$res = is_numeric( $res ) ? (float) $res : $default;
						}
					}
				}
			}
		}

		if ( ! empty( $whiteList ) && ! in_array( (string) $res, $whiteList ) )
		{
			$res = $default;
		}

		return $res;
	}
}
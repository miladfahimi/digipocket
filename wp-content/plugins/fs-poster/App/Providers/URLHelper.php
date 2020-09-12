<?php

namespace FSPoster\App\Providers;

trait URLHelper
{
	/**
	 * @param $url
	 *
	 * @return string
	 */
	public static function shortenerURL ( $url )
	{
		if ( ! Helper::getOption( 'url_shortener', '0' ) )
		{
			return $url;
		}

		if ( Helper::getOption( 'shortener_service' ) === 'tinyurl' )
		{
			return self::shortURLtinyurl( $url );
		}
		else
		{
			if ( Helper::getOption( 'shortener_service' ) === 'bitly' )
			{
				return self::shortURLbitly( $url );
			}
		}

		return $url;
	}

	/**
	 * @param $url
	 *
	 * @return string
	 */
	public static function shortURLtinyurl ( $url )
	{
		if ( empty( $url ) )
		{
			return $url;
		}

		return Curl::getURL( 'https://tinyurl.com/api-create.php?url=' . $url );
	}

	/**
	 * @param $url
	 *
	 * @return string
	 */
	public static function shortURLbitly ( $url )
	{
		$params = [
			'access_token' => Helper::getOption( 'url_short_access_token_bitly', '' ),
			'longUrl'      => $url
		];

		if ( empty( $params[ 'access_token' ] ) )
		{
			return $url;
		}

		$requestUrl = 'https://api-ssl.bit.ly/v3/shorten?' . http_build_query( $params );

		$result = json_decode( Curl::getURL( $requestUrl ), TRUE );

		return isset( $result[ 'data' ][ 'url' ] ) && ! empty( $result[ 'data' ][ 'url' ] ) ? $result[ 'data' ][ 'url' ] : $url;
	}

	/**
	 * @param $post_id
	 * @param $driver
	 * @param string $username
	 *
	 * @return string
	 */
	public static function postLink ( $post_id, $driver, $username = '' )
	{
		if ( $driver === 'fb' )
		{
			return 'https://fb.com/' . $post_id;
		}
		else if ( $driver === 'twitter' )
		{
			return 'https://twitter.com/' . $username . '/status/' . $post_id;
		}
		else if ( $driver === 'instagram' )
		{
			return 'https://www.instagram.com/p/' . $post_id . '/';
		}
		else if ( $driver === 'instagramstory' )
		{
			return 'https://www.instagram.com/stories/' . $username . '/';
		}
		else if ( $driver === 'linkedin' )
		{
			return 'https://www.linkedin.com/feed/update/' . $post_id . '/';
		}
		else if ( $driver === 'vk' )
		{
			return 'https://vk.com/wall' . $post_id;
		}
		else if ( $driver === 'pinterest' )
		{
			return 'https://www.pinterest.com/pin/' . $post_id;
		}
		else if ( $driver === 'reddit' )
		{
			return 'https://www.reddit.com/' . $post_id;
		}
		else if ( $driver === 'tumblr' )
		{
			return 'https://' . $username . '.tumblr.com/post/' . $post_id;
		}
		else if ( $driver === 'ok' )
		{
			if ( strpos( $post_id, 'topic' ) !== FALSE )
			{
				return 'https://ok.ru/group/' . $post_id;
			}
			else
			{
				return 'https://ok.ru/profile/' . $post_id;
			}
		}
		else if ( $driver === 'google_b' )
		{
			$getPostType = explode( ':', $post_id );
			if ( isset( $getPostType[ 1 ] ) && $getPostType[ 1 ] === 'product' )
			{
				return 'https://business.google.com/products/l/' . esc_html( $username );
			}

			return 'https://business.google.com/posts/l/' . esc_html( $username );
		}
		else if ( $driver === 'telegram' )
		{
			return "http://t.me/" . esc_html( $username );
		}
		else if ( $driver === 'medium' )
		{
			return "https://medium.com/p/" . esc_html( $post_id );
		}
		else if ( $driver === 'wordpress' )
		{
			return rtrim( $username, '/' ) . '/?p=' . $post_id;
		}
	}
}
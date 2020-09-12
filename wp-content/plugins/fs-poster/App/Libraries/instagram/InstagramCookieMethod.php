<?php

namespace FSPoster\App\Libraries\instagram;

use Exception;
use FSP_GuzzleHttp\Client;
use FSPoster\App\Providers\Date;
use FSP_GuzzleHttp\Cookie\CookieJar;

class InstagramCookieMethod
{
	/**
	 * @var Client
	 */
	private $_client;

	public function __construct ( $cookies, $proxy )
	{
		$this->_client = new Client( [
			'cookies'         => new CookieJar( FALSE, $cookies ),
			'allow_redirects' => [ 'max' => 10 ],
			'proxy'           => empty( $proxy ) ? NULL : $proxy,
			'verify'          => FALSE,
			'http_errors'     => FALSE,
			'headers'         => [
				'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'
			]
		] );
	}

	public function profileInfo ()
	{
		try
		{
			$response = (string) $this->_client->get( 'https://www.instagram.com/' )->getBody();
		}
		catch ( Exception $e )
		{
			$response = '';
		}

		preg_match( '/window\._sharedData.+\"id\"\:\"([0-9]+)\".+\<\/script\>/iU', $response, $id );
		preg_match( '/\"full_name\"\:\"(.+)\"/iU', $response, $full_name );
		preg_match( '/\"profile_pic_url\"\:\"(.+)\"/iU', $response, $profile_pic_url );
		preg_match( '/\"username\"\:\"(.+)\"/iU', $response, $username );
		preg_match( '/\"csrf_token\"\:\"(.+)\"/iU', $response, $csrfToken );

		if ( empty( $id ) || empty( $username ) || empty( $csrfToken ) )
		{
			return FALSE;
		}

		return [
			'id'              => $id[ 1 ],
			'full_name'       => isset( $full_name[ 1 ] ) ? $full_name[ 1 ] : '',
			'username'        => $username[ 1 ],
			'csrf'            => $csrfToken[ 1 ],
			'profile_pic_url' => isset( $profile_pic_url[ 1 ] ) ? json_decode( '"' . $profile_pic_url[ 1 ] . '"' ) : ''
		];
	}

	public function uploadPhoto ( $photo, $caption, $link = '', $target = 'timeline' )
	{
		$uploadId = $this->createUploadId();

		$params = [
			'media_type'          => '1',
			'upload_media_height' => (string) $photo[ 'height' ],
			'upload_media_width'  => (string) $photo[ 'width' ],
			'upload_id'           => $uploadId,
		];

		try
		{
			$response = (string) $this->_client->post( 'https://www.instagram.com/rupload_igphoto/fb_uploader_' . $uploadId, [
				'headers' => [
					'X-Requested-With'           => 'XMLHttpRequest',
					'X-CSRFToken'                => $this->getCsrfToken(),
					'X-Instagram-Rupload-Params' => json_encode( $params ),
					'X-Entity-Name'              => 'feed_' . $uploadId,
					'X-Entity-Length'            => filesize( $photo[ 'path' ] ),
					'Offset'                     => '0'
				],
				'body'    => fopen( $photo[ 'path' ], 'r' )
			] )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		$response = json_decode( $response, TRUE );

		if ( ! isset( $response[ 'upload_id' ] ) || $response[ 'upload_id' ] != $uploadId )
		{
			return [
				'status'    => 'error',
				'error_msg' => ! empty( $response[ 'message' ] ) && is_string( $response[ 'message' ] ) ? $response[ 'message' ] : ( ! empty( $response[ 'debug_info' ][ 'message' ] ) && is_string( $response[ 'debug_info' ][ 'message' ] ) ? $response[ 'debug_info' ][ 'message' ] : 'Error!' )
			];
		}

		switch ( $target )
		{
			case 'timeline':
				$endpoint = 'configure';

				$params = [
					'upload_id'                    => $uploadId,
					'caption'                      => $caption,
					'usertags'                     => '',
					'custom_accessibility_caption' => '',
					'retry_timeout'                => ''
				];
				break;
			default:
				$endpoint = 'configure_to_story';

				$params = [
					'upload_id' => $uploadId,
					'story_cta' => json_encode( [ [ "links" => [ [ "webUri" => $link ] ] ] ] )
				];
		}

		try
		{
			$result = (string) $this->_client->post( 'https://www.instagram.com/create/' . $endpoint . '/', [
				'form_params' => $params,
				'headers'     => [
					'X-Requested-With' => 'XMLHttpRequest',
					'X-CSRFToken'      => $this->getCsrfToken()
				]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		$result = json_decode( $result, TRUE );

		if ( isset( $result[ 'status' ] ) && $result[ 'status' ] == 'fail' )
		{
			return [
				'status'    => 'error',
				'error_msg' => ! empty( $result[ 'message' ] ) && is_string( $result[ 'message' ] ) ? $result[ 'message' ] : 'Error!'
			];
		}

		return [
			'status' => 'ok',
			'id'     => isset( $result[ 'media' ][ 'code' ] ) ? $result[ 'media' ][ 'code' ] : '?',
			'id2'    => isset( $result[ 'media' ][ 'id' ] ) ? $result[ 'media' ][ 'id' ] : '?'
		];
	}

	public function uploadVideo ( $video, $caption, $link = '', $target = 'timeline' )
	{
		$uploadId = $this->createUploadId();

		$params = [
			'is_igtv_video'            => FALSE,
			'media_type'               => '2',
			'video_format'             => 'video/mp4',
			'upload_media_height'      => (string) $video[ 'height' ],
			'upload_media_width'       => (string) $video[ 'width' ],
			'upload_media_duration_ms' => (string) ( $video[ 'duration' ] * 1000 ),
			'upload_id'                => $uploadId,
		];

		try
		{
			$response = $this->_client->post( 'https://www.instagram.com/rupload_igvideo/feed_' . $uploadId, [
				'headers' => [
					'X-Requested-With'           => 'XMLHttpRequest',
					'X-CSRFToken'                => $this->getCsrfToken(),
					'X-Instagram-Rupload-Params' => json_encode( $params ),
					'X-Entity-Name'              => 'feed_' . $uploadId,
					'X-Entity-Length'            => filesize( $video[ 'path' ] ),
					'Offset'                     => '0'
				],
				'body'    => fopen( $video[ 'path' ], 'r' )
			] )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		$response = json_decode( $response, TRUE );

		if ( isset( $response[ 'status' ] ) && $response[ 'status' ] == 'fail' )
		{
			return [
				'status'    => 'error',
				'error_msg' => ! empty( $response[ 'message' ] ) && is_string( $response[ 'message' ] ) ? $response[ 'message' ] : 'Error!'
			];
		}

		$videoThumbnail = $video[ 'thumbnail' ];

		$params = [
			'media_type'          => '2',
			'upload_media_height' => (string) $videoThumbnail[ 'height' ],
			'upload_media_width'  => (string) $videoThumbnail[ 'width' ],
			'upload_id'           => $uploadId
		];

		try
		{
			$response = $this->_client->post( 'https://www.instagram.com/rupload_igphoto/feed_' . $uploadId, [
				'headers' => [
					'X-Requested-With'           => 'XMLHttpRequest',
					'X-CSRFToken'                => $this->getCsrfToken(),
					'X-Instagram-Rupload-Params' => json_encode( $params ),
					'X-Entity-Name'              => 'feed_' . $uploadId,
					'X-Entity-Length'            => filesize( $videoThumbnail[ 'path' ] ),
					'Offset'                     => '0'
				],
				'body'    => fopen( $videoThumbnail[ 'path' ], 'r' )
			] );
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		try
		{
			$result = (string) $this->_client->post( 'https://www.instagram.com/create/configure/', [
				'form_params' => [
					'upload_id'                    => $uploadId,
					'caption'                      => $caption,
					'usertags'                     => '',
					'custom_accessibility_caption' => '',
					'retry_timeout'                => '12'
				],
				'headers'     => [
					'X-Requested-With' => 'XMLHttpRequest',
					'X-CSRFToken'      => $this->getCsrfToken()
				]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		$result = json_decode( $result, TRUE );

		if ( isset( $result[ 'status' ] ) && $result[ 'status' ] == 'fail' )
		{
			return [
				'status'    => 'error',
				'error_msg' => ! empty( $result[ 'message' ] ) && is_string( $result[ 'message' ] ) ? $result[ 'message' ] : 'Error!'
			];
		}

		return [
			'status' => 'ok',
			'id'     => isset( $result[ 'media' ][ 'code' ] ) ? $result[ 'media' ][ 'code' ] : '?',
			'id2'    => isset( $result[ 'media' ][ 'id' ] ) ? $result[ 'media' ][ 'id' ] : '?'
		];
	}

	public function getPostInfo ( $postId )
	{
		try
		{
			$response = (string) $this->_client->get( 'https://www.instagram.com/p/' . $postId . '/' )->getBody();
		}
		catch ( Exception $e )
		{
			$response = '';
		}

		preg_match( "/\"edge_media_to_comment\"\:\{\"count\"\:([0-9]+)\,/i", $response, $commentsCount );
		preg_match( "/\"edge_media_preview_like\"\:\{\"count\"\:([0-9]+)\,/i", $response, $likesCount );

		$commentsCount = isset( $commentsCount[ 1 ] ) ? $commentsCount[ 1 ] : 0;
		$likesCount    = isset( $likesCount[ 1 ] ) ? $likesCount[ 1 ] : 0;

		return [
			'likes'    => $likesCount,
			'comments' => $commentsCount
		];
	}

	private function getCsrfToken ()
	{
		$cookies = $this->_client->getConfig( 'cookies' )->toArray();
		$csrf    = '';

		foreach ( $cookies as $cookieInf )
		{
			if ( $cookieInf[ 'Name' ] == 'csrftoken' )
			{
				$csrf = $cookieInf[ 'Value' ];
			}
		}

		return $csrf;
	}

	private function createUploadId ()
	{
		return Date::epoch() . (string) rand( 100, 999 );
	}
}
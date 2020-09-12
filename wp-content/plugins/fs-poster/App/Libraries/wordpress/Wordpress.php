<?php

namespace FSPoster\App\Libraries\wordpress;

use Exception;
use IXR_Base64;
use IXR_Message;
use IXR_Request;
use FSP_GuzzleHttp\Client;
use FSPoster\App\Providers\Helper;

class Wordpress
{
	private $site_url;
	private $username;
	private $password;
	private $client;
	private $proxy;

	public function __construct ( $site_url, $username, $password, $proxy = '' )
	{
		$this->proxy    = $proxy;
		$this->site_url = rtrim( $site_url, '/' );
		$this->username = $username;
		$this->password = $password;

		include_once( ABSPATH . WPINC . '/class-IXR.php' );
		//include_once( ABSPATH . WPINC . '/class-wp-http-ixr-client.php' );

		$this->client = new Client( [
			'proxy'       => empty( $proxy ) ? NULL : $proxy,
			'verify'      => FALSE,
			'http_errors' => FALSE,
			'headers'     => [
				'Content-Type' => 'text/xml',
				'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0'
			]
		] );
	}

	public function sendPost ( $postInf, $post_type, $title, $excerpt, $content, $thumbnail = '' )
	{
		$postTypes = $this->cmd( 'wp.getPostTypes' );

		if ( ! array_key_exists( $post_type, $postTypes ) )
		{
			$post_method = Helper::getOption( 'wordpress_posting_type', '1' );

			if ( $post_method === '1' )
			{
				$post_type = 'post';
			}
			else
			{
				return [
					'status'    => 'error',
					'error_msg' => esc_html__( 'Failed to share the post because the post type is not available on the remote website.', 'fs-poster' )
				];
			}
		}

		$params = [
			'post_title'   => $title,
			'post_excerpt' => $excerpt,
			'post_content' => $content,
			'post_type'    => $post_type,
			'post_status'  => Helper::getOption( 'wordpress_post_status', 'publish' ),
			'terms'        => [],
		];

		if ( Helper::getOption( 'wordpress_post_with_categories', '1' ) == 1 )
		{
			$params[ 'terms' ][ 'category' ] = $this->terms( $postInf[ 'ID' ], 'category' );
		}

		if ( Helper::getOption( 'wordpress_post_with_tags', '1' ) == 1 )
		{
			$params[ 'terms' ][ 'post_tag' ] = $this->terms( $postInf[ 'ID' ], 'post_tag' );
		}

		if ( empty( $params[ 'terms' ] ) )
		{
			unset( $params[ 'terms' ] );
		}

		$mediaId = $this->uploadPhoto( $thumbnail );

		if ( ! empty( $mediaId ) )
		{
			$params[ 'post_thumbnail' ] = $mediaId;
		}

		$createPost = $this->cmd( 'wp.newPost', $params );

		if ( isset( $createPost[ 'faultString' ] ) || ! is_numeric( $createPost ) )
		{
			return [
				'status'    => 'error',
				'error_msg' => isset( $createPost[ 'faultString' ] ) ? $createPost[ 'faultString' ] : esc_sql( 'An error occurred while processing the request!' )
			];
		}

		$post_id = (int) $createPost;

		return [
			'status' => 'ok',
			'id'     => $post_id
		];
	}

	public function terms ( $post_id, $taxonomy )
	{
		$postTerms = wp_get_post_terms( $post_id, $taxonomy );
		$termIDs   = [];

		foreach ( $postTerms as $term )
		{
			$termId = $this->syncTerm( $term->slug, $term->name, $taxonomy );

			if ( ! empty( $termId ) )
			{
				$termIDs[] = $termId;
			}
		}

		return $termIDs;
	}

	public function syncTerm ( $termSlug, $termName, $taxonomy )
	{
		$termId = $this->findTerm( $termSlug, $taxonomy );

		if ( empty( $termId ) )
		{
			$termId = $this->cmd( 'wp.newTerm', [
				'name'     => $termName,
				'slug'     => $termSlug,
				'taxonomy' => $taxonomy
			] );
		}

		return $termId;
	}

	public function findTerm ( $termSlug, $taxonomy )
	{
		$result = $this->cmd( 'wp.getTerms', $taxonomy, [ 'search' => $termSlug ] );

		foreach ( $result as $termInf )
		{
			if ( $termInf[ 'slug' ] == $termSlug )
			{
				return $termInf[ 'term_id' ];
			}
		}

		return FALSE;
	}

	public function cmd ()
	{
		$args    = func_get_args();
		$command = array_shift( $args );

		$params = array_merge( [ 0, $this->username, $this->password ], $args );

		$request = new IXR_Request( $command, $params );
		$xml     = $request->getXml();

		try
		{
			$result = $this->client->request( 'POST', $this->site_url . '/xmlrpc.php', [
				'body' => $xml
			] );
		}
		catch ( Exception $e )
		{
			return [
				'faultString' => $e->getMessage()
			];
		}

		$message = new IXR_Message( (string) $result->getBody() );
		$message->parse();

		if ( ! isset( $message->params[ 0 ] ) )
		{
			return [
				'faultString' => esc_html__( 'An error occurred while processing the request!' )
			];
		}

		return $message->params[ 0 ];
	}

	public function uploadPhoto ( $image = NULL )
	{
		if ( empty( $image ) )
		{
			return FALSE;
		}

		$content = [
			'name' => basename( $image ),
			'type' => mime_content_type( $image ),
			'bits' => new IXR_Base64( file_get_contents( $image ) ),
			TRUE
		];

		$result = $this->cmd( 'metaWeblog.newMediaObject', $content );

		return isset( $result[ 'id' ] ) ? $result[ 'id' ] : FALSE;
	}

	/**
	 * @return array
	 */
	public function checkAccount ()
	{
		$result    = [
			'error'     => TRUE,
			'error_msg' => NULL
		];
		$checkUser = $this->checkUser();

		if ( $checkUser !== TRUE )
		{
			$result[ 'error_msg' ] = $checkUser;
		}
		else if ( $checkUser === TRUE )
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}

	public function checkUser ()
	{
		$info = $this->cmd( 'wp.getProfile' );

		if ( isset( $info[ 'faultString' ] ) )
		{
			return $info[ 'faultString' ];
		}

		if ( isset( $info[ 'user_id' ] ) )
		{
			return TRUE;
		}

		return esc_html__( 'The entered details are wrong!' );
	}
}

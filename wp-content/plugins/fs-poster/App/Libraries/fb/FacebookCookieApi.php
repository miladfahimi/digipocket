<?php

namespace FSPoster\App\Libraries\fb;

use Exception;
use FSP_GuzzleHttp\Client;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Curl;
use FSP_GuzzleHttp\Cookie\CookieJar;
use FSPoster\App\Providers\Helper;

class FacebookCookieApi
{
	private $client;
	private $fb_dtsg;
	private $fbUserId;
	private $fbSess;
	private $proxy;

	public function __construct ( $fbUserId, $fbSess, $proxy = NULL )
	{
		$this->fbUserId = $fbUserId;
		$this->fbSess   = $fbSess;
		$this->proxy    = $proxy;

		$cookies = [
			[
				"Name"     => "c_user",
				"Value"    => $fbUserId,
				"Domain"   => ".facebook.com",
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => FALSE,
				"Discard"  => FALSE,
				"HttpOnly" => FALSE,
				"Priority" => "HIGH"
			],
			[
				"Name"     => "xs",
				"Value"    => $fbSess,
				"Domain"   => ".facebook.com",
				"Path"     => "/",
				"Max-Age"  => NULL,
				"Expires"  => NULL,
				"Secure"   => FALSE,
				"Discard"  => FALSE,
				"HttpOnly" => TRUE,
				"Priority" => "HIGH"
			]
		];

		$cookieJar = new CookieJar( FALSE, $cookies );

		$this->client = new Client( [
			'cookies'         => $cookieJar,
			'allow_redirects' => [ 'max' => 20 ],
			'proxy'           => empty( $proxy ) ? NULL : $proxy,
			'verify'          => FALSE,
			'http_errors'     => FALSE,
			'headers'         => [ 'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0' ]
		] );
	}

	public function authorizeFbUser ()
	{
		$myInfo = $this->myInfo();

		if ( $this->fbUserId !== $myInfo[ 'id' ] )
		{
			return FALSE;
		}

		$checkLoginRegistered = DB::fetch( 'accounts', [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'driver'     => 'fb',
			'profile_id' => $myInfo[ 'id' ]
		] );

		$dataSQL = [
			'blog_id'    => Helper::getBlogId(),
			'user_id'    => get_current_user_id(),
			'name'       => $myInfo[ 'name' ],
			'driver'     => 'fb',
			'profile_id' => $myInfo[ 'id' ],
			'proxy'      => $this->proxy,
			'options'    => $this->fbSess
		];

		if ( ! $checkLoginRegistered )
		{
			DB::DB()->insert( DB::table( 'accounts' ), $dataSQL );

			$fbAccId = DB::DB()->insert_id;
		}
		else
		{
			$fbAccId = $checkLoginRegistered[ 'id' ];

			DB::DB()->update( DB::table( 'accounts' ), $dataSQL, [ 'id' => $fbAccId ] );

			DB::DB()->delete( DB::table( 'account_access_tokens' ), [ 'account_id' => $fbAccId ] );

			DB::DB()->delete( DB::table( 'account_nodes' ), [ 'account_id' => $fbAccId ] );
		}

		// my pages load
		if ( Helper::getOption( 'load_own_pages', 1 ) == 1 )
		{
			$accountsList = $this->getMyPages();

			foreach ( $accountsList as $accountInfo )
			{
				DB::DB()->insert( DB::table( 'account_nodes' ), [
					'blog_id'      => Helper::getBlogId(),
					'user_id'      => get_current_user_id(),
					'driver'       => 'fb',
					'account_id'   => $fbAccId,
					'node_type'    => 'ownpage',
					'node_id'      => $accountInfo[ 'id' ],
					'name'         => $accountInfo[ 'name' ],
					'access_token' => NULL,
					'category'     => $accountInfo[ 'category' ]
				] );
			}
		}

		// groups load
		if ( Helper::getOption( 'load_groups', 1 ) == 1 )
		{
			$accountsList = $this->getGroups();

			foreach ( $accountsList as $accountInfo )
			{
				$cover = 'https://static.xx.fbcdn.net/rsrc.php/v3/yF/r/MzwrKZOhtIS.png';

				DB::DB()->insert( DB::table( 'account_nodes' ), [
					'blog_id'      => Helper::getBlogId(),
					'user_id'      => get_current_user_id(),
					'driver'       => 'fb',
					'account_id'   => $fbAccId,
					'node_type'    => 'group',
					'node_id'      => $accountInfo[ 'id' ],
					'name'         => $accountInfo[ 'name' ],
					'access_token' => NULL,
					'category'     => NULL,
					'cover'        => $cover
				] );
			}
		}

		return TRUE;
	}

	public function myInfo ()
	{
		try
		{
			$getInfo = (string) $this->client->request( 'GET', 'https://touch.facebook.com/' )->getBody();
		}
		catch ( Exception $e )
		{
			$getInfo = '';
		}

		preg_match( '/\"USER_ID\"\:\"([0-9]+)\"/i', $getInfo, $accountId );
		$accountId = isset( $accountId[ 1 ] ) ? $accountId[ 1 ] : '?';

		preg_match( '/\"NAME\"\:\"([^\"]+)\"/i', $getInfo, $name );
		$name = json_decode( '"' . ( isset( $name[ 1 ] ) ? $name[ 1 ] : '?' ) . '"' );

		return [
			'id'   => $accountId,
			'name' => $name
		];
	}

	public function getMyPages ()
	{
		$myPagesArr = [];

		try
		{
			$result = (string) $this->client->request( 'GET', 'https://www.facebook.com/bookmarks/pages?ref=u2u' )->getBody();
		}
		catch ( Exception $e )
		{
			$result = '';
		}

		preg_match( '/\"compat_iframe_token\":\"(.+)\"/Ui', $result, $iframe_token );

		if ( ! empty( $iframe_token ) && isset( $iframe_token[ 1 ] ) && ! empty( $iframe_token[ 1 ] ) )
		{
			try
			{
				$result = (string) $this->client->request( 'GET', 'https://www.facebook.com/bookmarks/pages?ref=u2u&cquick=jsc_c_e&cquick_token=' . $iframe_token[ 1 ] . '&ctarget=https%3A%2F%2Fwww.facebook.com' )->getBody();
			}
			catch ( Exception $e )
			{
				$result = '';
			}
		}
		else
		{
			$result = '';
		}

		preg_match( '/require\:(\[\[\"BookmarkSeeAllEntsSectionController\".+\]\]\]\])/Ui', $result, $myPages );

		$myPages = preg_replace( '/(\,|\{)([a-zA-Z0-9\_]+)\:/', '$1"$2":', $myPages[ 1 ] );
		$myPages = json_decode( $myPages, TRUE );
		$myPages = is_array( $myPages ) && isset( $myPages[ 0 ][ 3 ][ 1 ] ) ? $myPages[ 0 ][ 3 ][ 1 ] : [];

		foreach ( $myPages as $myPageInf )
		{
			$myPagesArr[] = [
				'id'       => isset( $myPageInf[ 'id' ] ) ? $myPageInf[ 'id' ] : 0,
				'name'     => isset( $myPageInf[ 'name' ] ) ? $myPageInf[ 'name' ] : '-',
				'category' => ''
			];
		}

		return $myPagesArr;
	}

	public function getGroups ()
	{
		try
		{
			$result = (string) $this->client->request( 'GET', 'https://m.facebook.com/groups/?seemore' )->getBody();
		}
		catch ( Exception $e )
		{
			$result = '';
		}

		preg_match_all( '/\<a href\=\"\/groups\/([0-9]+)(?:\?[^\"]+)?\"\>(.+)\<\/a\>/Ui', $result, $groups );

		if ( ! isset( $groups[ 1 ] ) )
		{
			return [];
		}

		$groupsArr = [];

		foreach ( $groups[ 1 ] as $key => $group )
		{
			$groupsArr[] = [
				'id'   => $group,
				'name' => isset( $groups[ 2 ][ $key ] ) ? $groups[ 2 ][ $key ] : '???'
			];
		}

		return $groupsArr;
	}

	public function getStats ( $postId )
	{
		try
		{
			$result = (string) $this->client->request( 'GET', 'https://touch.facebook.com/' . $postId )->getBody();
		}
		catch ( Exception $e )
		{
			$result = '';
		}

		preg_match( '/\,comment_count\:([0-9]+)\,/i', $result, $comments );
		preg_match( '/\,share_count\:([0-9]+)\,/i', $result, $shares );
		preg_match( '/\,reactioncount\:([0-9]+)\,/i', $result, $likes );

		return [
			'like'     => isset( $likes[ 1 ] ) ? $likes[ 1 ] : 0,
			'comments' => isset( $comments[ 1 ] ) ? $comments[ 1 ] : 0,
			'shares'   => isset( $shares[ 1 ] ) ? $shares[ 1 ] : 0,
			'details'  => ''
		];
	}

	public function sendPost ( $nodeFbId, $nodeType, $type, $message, $preset_id, $link, $images, $videos )
	{
		$sendData = [
			'fb_dtsg'  => $this->fb_dtsg(),
			'__ajax__' => 'true',
			'__a'      => 'a'
		];

		if ( empty( $sendData[ 'fb_dtsg' ] ) )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Session expired. Please remove your Facebook account from the plugin and add it again! P.S. Don\'t Log out from your Facebook account after adding that . When you Log out your account, session will be destroyed and you can\'t share any post. Best practic is to add your account within Incognito mode!'
			];
		}

		if ( $preset_id > 0 && $type === 'status' )
		{
			$sendData[ 'text_format_preset_id' ] = $preset_id;
		}
		else if ( $type === 'link' )
		{
			$sendData[ 'linkUrl' ] = $link;
		}

		$postType = 'form_params';

		if ( $type === 'image' )
		{
			$sendData[ 'photo_ids' ] = [];
			$images                  = is_array( $images ) ? $images : [ $images ];

			foreach ( $images as $imageURL )
			{
				$photoId = $this->uploadPhoto( $imageURL, $nodeFbId, $nodeType );

				if ( $photoId > 0 )
				{
					$sendData[ 'photo_ids' ][ $photoId ] = $photoId;
				}
			}

			if ( $nodeType === 'group' )
			{
				$endpoint = "https://touch.facebook.com/_mupload_/composer/?target=" . $nodeFbId;

				$sendData[ 'message' ] = $message;
			}
			else if ( $nodeType === 'ownpage' )
			{
				$endpoint = 'https://upload.facebook.com/_mupload_/composer/?target=' . $nodeFbId . '&av=' . $nodeFbId;

				$sendData[ 'status' ]           = $message;
				$sendData[ 'waterfall_id' ]     = $this->waterfallId();
				$sendData[ 'waterfall_source' ] = 'composer_pages_feed';

				$postType = 'multipart';
			}
			else
			{
				$endpoint = "https://touch.facebook.com/_mupload_/composer/?target=" . $nodeFbId;

				$sendData[ 'status' ]           = $message;
				$sendData[ 'waterfall_id' ]     = $this->waterfallId();
				$sendData[ 'waterfall_source' ] = 'composer_pages_feed';
				$sendData[ 'privacyx' ]         = $this->getPrivacyX();
			}

		}
		else if ( $type === 'video' )
		{
			return [
				'status'    => 'error',
				'error_msg' => esc_html__( 'Error! Facebook cookie method doesn\'t allow sharing videos!', 'fs-poster' )
			];
		}
		else
		{
			if ( $nodeType === 'group' )
			{
				$endpoint              = 'https://touch.facebook.com/a/group/post/add/?gid=' . $nodeFbId;
				$sendData[ 'message' ] = $message;
			}
			else if ( $nodeType === 'ownpage' )
			{
				$endpoint             = 'https://touch.facebook.com/a/home.php?av=' . $nodeFbId;
				$sendData[ 'status' ] = $message;
			}
			else
			{
				$endpoint = 'https://touch.facebook.com/a/home.php';

				$sendData[ 'status' ]   = $message;
				$sendData[ 'target' ]   = $nodeFbId;
				$sendData[ 'privacyx' ] = $this->getPrivacyX();
			}
		}

		if ( $postType === 'multipart' )
		{
			$sendData = $this->conertToMultipartArray( $sendData );
		}

		try
		{
			$post = (string) $this->client->request( 'POST', $endpoint, [
				$postType => $sendData,
				'headers' => [ 'Referer' => 'https://touch.facebook.com/' ]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'status'    => 'error',
				'error_msg' => 'Error! ' . $e->getMessage()
			];
		}

		$hasError = $this->parsePostRepsonse( $post );

		if ( ! $hasError[ 0 ] )
		{
			return [
				'status'    => 'error',
				'error_msg' => $hasError[ 1 ]
			];
		}

		if ( preg_match( '/errcode=([0-9]+)/', $post ) && preg_match( '/upload_error=([0-9]+)/', $post ) )
		{
			return [
				'status'    => 'error',
				'error_msg' => esc_html__( 'Error! Failed to upload the image!', 'fs-poster' )
			];
		}

		if ( $nodeType === 'account' && $type === 'link' )
		{
			preg_match( '/top_level_post_id\.([0-9]+)/i', $post, $postId );

			$postId = isset( $postId[ 1 ] ) ? $postId[ 1 ] : 0;
		}
		else if ( $nodeType === 'account' && $type === 'image' )
		{
			try
			{
				$get_account = (string) $this->client->request( 'GET', 'https://m.facebook.com/me' )->getBody();
			}
			catch ( Exception $e )
			{
				$get_account = '';
			}

			preg_match( '/name="privacy\[([0-9]+)\]/i', $get_account, $postId );

			$postId = isset( $postId[ 1 ] ) ? $postId[ 1 ] : 0;
		}
		else if ( $nodeType === 'ownpage' && $type === 'link' )
		{
			preg_match( '/top_level_post_id\.([0-9]+)/i', $post, $postId );

			$postId = isset( $postId[ 1 ] ) ? $postId[ 1 ] : 0;
		}
		else if ( $nodeType === 'ownpage' && $type === 'image' )
		{
			try
			{
				$get_last_post = (string) $this->client->request( 'GET', 'https://m.facebook.com/' . $nodeFbId . '/' )->getBody();
			}
			catch ( Exception $e )
			{
				$get_last_post = '';
			}

			preg_match( '/id\=\"like_([0-9]+)\"/i', $get_last_post, $postId );

			$postId = isset( $postId[ 1 ] ) ? $postId[ 1 ] : 0;
		}
		else if ( $nodeType === 'group' && $type === 'link' )
		{
			preg_match( '/class=\\\"_5xu4\\\">\\\u003Ca href=\\\".*?id=([0-9]+)\\\"/i', $post, $postId );

			$postId = isset( $postId[ 1 ] ) ? $postId[ 1 ] : 0;

			if ( ! ( $postId > 0 ) && strpos( $post, 'is pending approval' ) > -1 )
			{
				$postId = 'groups/' . $nodeFbId . '/pending';
			}
		}
		else if ( $nodeType === 'group' && $type === 'image' )
		{
			preg_match( '/id=([0-9]+)/i', $post, $postId );

			$postId = isset( $postId[ 1 ] ) ? $postId[ 1 ] : 0;
		}

		if ( $postId == 0 )
		{
			return [
				'status'    => 'error',
				'error_msg' => esc_html__( 'An error occured while sharing the post.', 'fs-poster' )
			];
		}

		return [
			'status' => 'ok',
			'id'     => $postId
		];
	}

	private function fb_dtsg ()
	{
		if ( is_null( $this->fb_dtsg ) )
		{
			try
			{
				$getFbDtsg = (string) $this->client->request( 'GET', 'https://m.facebook.com/' )->getBody();
			}
			catch ( Exception $e )
			{
				$getFbDtsg = '';
			}

			preg_match( '/name\=\"fb_dtsg\" value\=\"(.+)\"/Ui', $getFbDtsg, $fb_dtsg );

			if ( ! isset( $fb_dtsg[ 1 ] ) )
			{
				$this->fb_dtsg = '';
			}
			else
			{
				$this->fb_dtsg = $fb_dtsg[ 1 ];
			}
		}

		return $this->fb_dtsg;
	}

	private function uploadPhoto ( $photo, $target, $targetType )
	{
		$postData = [
			[
				'name'     => 'file1',
				'contents' => Curl::getURL( $photo, $this->proxy ),
				'filename' => basename( $photo )
			]
		];

		$endpoint = 'https://upload.facebook.com/_mupload_/photo/x/saveunpublished/?thumbnail_width=80&thumbnail_height=80&waterfall_id=' . $this->waterfallId() . '&waterfall_app_name=web_m_touch&waterfall_source=composer_pages_feed&target_id=' . urlencode( $target ) . '&fb_dtsg=' . urlencode( $this->fb_dtsg() ) . '&__ajax__=true&__a=true';

		if ( $targetType === 'ownpage' )
		{
			$endpoint .= '&av=' . urlencode( $target );
		}

		try
		{
			$post = (string) $this->client->request( 'POST', $endpoint, [
				'multipart' => $postData,
				'headers'   => [ 'Referer' => 'https://touch.facebook.com/' ]
			] )->getBody();
		}
		catch ( Exception $e )
		{
			$post = '';
		}

		preg_match( '/\"fbid\"\:\"([0-9]+)/i', $post, $photoId );

		return isset( $photoId[ 1 ] ) ? $photoId[ 1 ] : 0;
	}

	private function waterfallId ()
	{
		return md5( uniqid() . rand( 0, 99999999 ) . uniqid() );
	}

	private function getPrivacyX ()
	{
		$url = 'https://touch.facebook.com/privacy/timeline/saved_custom_audience_selector_dialog/?fb_dtsg=' . $this->fb_dtsg();

		try
		{
			$getData = (string) $this->client->request( 'GET', $url )->getBody();
		}
		catch ( Exception $e )
		{
			$getData = '';
		}

		preg_match( '/\:\"([0-9]+)\"/i', htmlspecialchars_decode( $getData ), $firstPrivacyX );

		return isset( $firstPrivacyX[ 1 ] ) ? $firstPrivacyX[ 1 ] : '0';
	}

	private function conertToMultipartArray ( $arr )
	{
		$newArr = [];

		foreach ( $arr as $name => $value )
		{
			if ( is_array( $value ) )
			{
				foreach ( $value as $name2 => $value2 )
				{
					$newArr[] = [
						'name'     => $name . '[' . $name2 . ']',
						'contents' => $value2
					];
				}
			}
			else
			{
				$newArr[] = [
					'name'     => $name,
					'contents' => $value
				];
			}
		}

		return $newArr;
	}

	private function parsePostRepsonse ( $response )
	{
		if ( empty( $response ) )
		{
			return [ FALSE, 'Error! Response is empty!' ];
		}

		$hasError = preg_match( '/\,\"error\"\:([0-9]+)\,/iU', $response, $errCode );

		if ( $hasError && (int) $errCode[ 1 ] > 0 )
		{
			$errCode = (int) $errCode[ 1 ];

			preg_match( '/\,\"errorDescription\"\:\"(.+)\"\,/iU', $response, $errMsg );
			$errMsg = isset( $errMsg[ 1 ] ) ? $errMsg[ 1 ] : 'Error!';

			return [ FALSE, $errMsg . ' ( Error code: ' . $errCode . ')' ];
		}

		return [ TRUE ];
	}

	/**
	 * @return array
	 */
	public function checkAccount ()
	{
		$result = [
			'error'     => TRUE,
			'error_msg' => NULL
		];
		$myInfo = $this->myInfo();

		if ( $this->fbUserId === $myInfo[ 'id' ] )
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}
}
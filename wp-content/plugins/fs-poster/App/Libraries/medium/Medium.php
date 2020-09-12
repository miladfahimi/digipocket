<?php

namespace FSPoster\App\Libraries\medium;

use FSPoster\App\Providers\Curl;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\Session;
use FSPoster\App\Providers\SocialNetwork;

class Medium extends SocialNetwork
{

	/**
	 * @param array $account_info
	 * @param string $type
	 * @param string $title
	 * @param string $message
	 * @param string $accessToken
	 * @param string $proxy
	 * @return array
	 */
	public static function sendPost($account_info, $title, $message, $accessToken, $proxy)
	{
		$sendData = [
			'title' => $title,
			'contentFormat' => 'html',
			'content' => $message
		];

		if (isset($account_info['screen_name'])) {
			$endpoint = 'https://api.medium.com/v1/publications/' . $account_info['node_id'] . '/posts';
		} else {
			$endpoint = 'https://api.medium.com/v1/users/' . $account_info['profile_id'] . '/posts';
		}
		$result = self::cmd($endpoint, 'POST', $accessToken, $sendData, $proxy);

		if (isset($result['errors']) && isset($result['errors'][0]['message'])) {
			$result2 = [
				'status' => 'error',
				'error_msg' => $result['errors'][0]['message']
			];
		} else {
			$result2 = [
				'status' => 'ok',
				'id' => isset($result['data']['id']) ? $result['data']['id'] : 0
			];
		}

		return $result2;
	}

	/**
	 * @param string $cmd
	 * @param string $method
	 * @param string $accessToken
	 * @param array $data
	 * @param string $proxy
	 * @return mixed
	 */
	public static function cmd($cmd, $method, $accessToken, $data = [], $proxy = '')
	{
		$url = $cmd;

		$method = $method === 'POST' ? 'POST' : ($method === 'DELETE' ? 'DELETE' : 'GET');

		$data1 = Curl::getContents($url, $method, json_encode($data), [
			'Authorization' => 'Bearer ' . $accessToken,
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
			'Accept-Charset' => 'utf-8'
		], $proxy, false);

		$data = json_decode($data1, true);

		if (!is_array($data)) {
			$data = [
				'error' => ['message' => 'Error data!']
			];
		}

		return $data;
	}

	/**
	 * @param integer $appId
	 * @return string
	 */
	public static function getLoginURL($appId)
	{
		$state = md5(rand(111111111, 911111111));

		Session::set('app_id', $appId);
		Session::set('state', $state);
		Session::set('proxy', Request::get('proxy', '', 'string'));

		$appInf = DB::fetch('apps', ['id' => $appId, 'driver' => 'medium']);
		if (!$appInf) {
			self::error(esc_html__('Error! The App isn\'t found!'));
		}
		$appId = urlencode($appInf['app_id']);

		$callbackUrl = urlencode(self::callbackUrl());

		return "https://medium.com/m/oauth/authorize?client_id={$appId}&response_type=code&redirect_uri={$callbackUrl}&scope=basicProfile,listPublications,publishPost&state=" . $state;
	}

	/**
	 * @return string
	 */
	public static function callbackURL()
	{
		return site_url() . '/?medium_callback=1';
	}

	/**
	 * @return bool
	 */
	public static function getAccessToken()
	{
		$appId = (int)Session::get('app_id');
		$stateSess = Session::get('state');

		if (empty($appId) || empty($stateSess)) {
			return false;
		}

		$code = Request::get('code', '', 'string');
		$state = Request::get('state', '', 'string');

		if (empty($code) || $state != $stateSess) {
			$error_message = Request::get('error_message', '', 'str');

			self::error($error_message);
		}

		$proxy = Session::get('proxy');

		Session::remove('app_id');
		Session::remove('state');
		Session::remove('proxy');

		$appInf = DB::fetch('apps', ['id' => $appId, 'driver' => 'medium']);
		$appSecret = urlencode($appInf['app_secret']);
		$appId2 = urlencode($appInf['app_id']);

		$url = 'https://api.medium.com/v1/tokens';

		$postData = [
			'grant_type' => 'authorization_code',
			'code' => $code,
			'client_id' => $appId2,
			'client_secret' => $appSecret,
			'redirect_uri' => self::callbackURL(),
		];

		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded',
			'Accept' => 'application/json',
			'Accept-Charset' => 'utf-8'
		];

		$response = Curl::getContents($url, 'POST', $postData, $headers, $proxy, true);

		$params = json_decode($response, true);

		if (isset($params['errors'][0]['message'])) {
			self::error($params['errors'][0]['message']);
		}

		$access_token = esc_html($params['access_token']);
		$refreshToken = esc_html($params['refresh_token']);
		$expiresIn = Date::dateTimeSQL( intval($params['expires_at'] / 1000 ) );

		self::authorizeMediumUser($appId, $access_token, $refreshToken, $expiresIn, $proxy);
	}

	/**
	 * @param integer $appId
	 * @param string $accessToken
	 * @param string $refreshToken
	 * @param string $expiresIn
	 * @param string $proxy
	 */
	public static function authorizeMediumUser($appId, $accessToken, $refreshToken, $expiresIn, $proxy)
	{
		$me = self::cmd('https://api.medium.com/v1/me', 'GET', $accessToken, [], $proxy);

		if (isset($me['errors'][0]['message'])) {
			self::error($me['errors'][0]['message']);
		}

		$me = $me['data'];

		$meId = $me['id'];

		$checkLoginRegistered = DB::fetch('accounts', [
			'blog_id' => Helper::getBlogId(),
			'user_id' => get_current_user_id(),
			'driver' => 'medium',
			'profile_id' => $meId
		]);

		$dataSQL = [
			'blog_id' => Helper::getBlogId(),
			'user_id' => get_current_user_id(),
			'name' => $me['name'],
			'driver' => 'medium',
			'profile_id' => $meId,
			'profile_pic' => $me['imageUrl'],
			'username' => $me['username'],
			'proxy' => $proxy
		];

		if (!$checkLoginRegistered) {
			DB::DB()->insert(DB::table('accounts'), $dataSQL);

			$accId = DB::DB()->insert_id;
		} else {
			$accId = $checkLoginRegistered['id'];

			DB::DB()->update(DB::table('accounts'), $dataSQL, ['id' => $accId]);

			DB::DB()->delete(DB::table('account_access_tokens'), ['account_id' => $accId, 'app_id' => $appId]);
		}

		// acccess token
		DB::DB()->insert(DB::table('account_access_tokens'), [
			'account_id' => $accId,
			'app_id' => $appId,
			'access_token' => $accessToken,
			'refresh_token' => $refreshToken,
			'expires_on' => $expiresIn
		]);

		$publications = self::cmd('https://api.medium.com/v1/users/' . $meId . '/publications', 'GET', $accessToken, [], $proxy);

		if (isset($publications['data']) && is_array($publications['data'])) {
			foreach ($publications['data'] as $publicationInf) {
				$loadedOwnPages[$publicationInf['id']] = true;

				DB::DB()->insert(DB::table('account_nodes'), [
					'blog_id' => Helper::getBlogId(),
					'user_id' => get_current_user_id(),
					'driver' => 'medium',
					'screen_name' => str_replace('https://medium.com/', '', $publicationInf['url']),
					'account_id' => $accId,
					'node_type' => 'publication',
					'node_id' => $publicationInf['id'],
					'name' => $publicationInf['name'],
					'cover' => $publicationInf['imageUrl']
				]);
			}
		}

		self::closeWindow();
	}

	/**
	 * @param $tokenInfo
	 */
	public static function accessToken($tokenInfo)
	{
		if ((Date::epoch() + 30) > Date::epoch($tokenInfo['expires_on'])) {
			return self::refreshToken($tokenInfo);
		}

		return $tokenInfo['access_token'];
	}

	/**
	 * @param array $tokenInfo
	 * @return string
	 */
	public static function refreshToken($tokenInfo)
	{
		$appId = $tokenInfo['app_id'];

		$account_info = DB::fetch('accounts', $tokenInfo['account_id']);
		$proxy = $account_info['proxy'];

		$appInf = DB::fetch('apps', $appId);
		$appId2 = urlencode($appInf['app_id']);
		$appSecret = urlencode($appInf['app_secret']);

		$url = 'https://api.medium.com/v1/tokens';

		$postData = [
			'grant_type' => 'refresh_token',
			'client_id' => $appId2,
			'client_secret' => $appSecret,
			'refresh_token' => $tokenInfo['refresh_token']
		];

		$headers = [
			'Accept' => 'application/json',
			'Accept-Charset' => 'utf-8'
		];
		$response = Curl::getContents($url, 'POST', $postData, $headers, $proxy);
		$params = json_decode($response, true);

		if (isset($params['error']['message'])) {
			return false;
		}

		$access_token = esc_html($params['access_token']);
		$expiresIn = Date::dateTimeSQL('now', '+' . (int) $params['expires_at'] . ' seconds');

		DB::DB()->update(DB::table('account_access_tokens'), [
			'access_token' => $access_token,
			'expires_on' => $expiresIn
		], ['id' => $tokenInfo['id']]);

		$tokenInfo['access_token'] = $access_token;
		$tokenInfo['expires_on'] = $expiresIn;

		return $access_token;
	}

	/**
	 * @param integer $post_id
	 * @param string $accessToken
	 * @return array
	 */
	public static function getStats($post_id, $accessToken, $proxy)
	{
		return [
			'comments' => 0,
			'like' => 0,
			'shares' => 0,
			'details' => ''
		];
	}

	/**
	 * @param string $accessToken
	 * @param string $proxy
	 * @return array
	 */
	public static function checkAccount($accessToken, $proxy)
	{
		$result = [
			'error' => true,
			'error_msg' => null
		];
		$me = self::cmd('https://api.medium.com/v1/me', 'GET', $accessToken, [], $proxy);

		if (isset($me['errors'][0]['message'])) {
			$result['error_msg'] = $me['errors'][0]['message'];
		} else {
			$result['error'] = false;
		}

		return $result;
	}
}
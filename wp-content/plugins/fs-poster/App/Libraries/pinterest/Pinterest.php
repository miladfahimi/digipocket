<?php

namespace FSPoster\App\Libraries\pinterest;

use FSPoster\App\Providers\Curl;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\Session;
use FSPoster\App\Providers\SocialNetwork;

class Pinterest extends SocialNetwork
{

	/**
	 * @param array $account_info
	 * @param string $type
	 * @param string $message
	 * @param string $link
	 * @param array $images
	 * @param string $accessToken
	 * @param string $proxy
	 * @return array
	 */
	public static function sendPost($boardId, $type, $message, $link, $images, $accessToken, $proxy)
	{
		$sendData = [
			'board' => $boardId,
			'note' => $message,
			'link' => $link
		];

		if ($type === 'image') {
			$sendData['image_url'] = reset($images);
		} else {
			return [
				'status' => 'error',
				'error_msg' => 'An image is required to pin on board!'
			];
		}

		$result = self::cmd('pins', 'POST', $accessToken, $sendData, $proxy);

		if (isset($result['error']) && isset($result['error']['message'])) {
			$result2 = [
				'status' => 'error',
				'error_msg' => $result['error']['message']
			];
		} else if (isset($result['message'])) {
			$result2 = [
				'status' => 'error',
				'error_msg' => $result['message']
			];
		} else {
			$result2 = [
				'status' => 'ok',
				'id' => $result['data']['id']
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
	 * @return array|mixed|object
	 */
	public static function cmd($cmd, $method, $accessToken, array $data = [], $proxy = '')
	{
		$data['access_token'] = $accessToken;

		$url = 'https://api.pinterest.com/v1/' . trim($cmd, '/') . '/';

		$method = $method === 'POST' ? 'POST' : ($method === 'DELETE' ? 'DELETE' : 'GET');

		$data1 = Curl::getContents($url, $method, $data, [], $proxy);
		$data = json_decode($data1, true);

		if (!is_array($data)) {
			$data = ['message' => 'Error data!'];
		}

		return $data;
	}

	/**
	 * @param integer $appId
	 * @return string
	 */
	public static function getLoginURL($appId)
	{
		Session::set('app_id', $appId);
		Session::set('proxy', Request::get('proxy', '', 'string'));

		$appInf = DB::fetch('apps', ['id' => $appId, 'driver' => 'pinterest']);
		if (!$appInf) {
			self::error(esc_html__('Error! The App isn\'t found!'));
		}

		$appId = urlencode($appInf['app_id']);

		$callbackUrl = urlencode(self::callbackUrl());

		return "https://api.pinterest.com/oauth/?response_type=code&redirect_uri=" . $callbackUrl . "&client_id=" . $appId . "&scope=read_public,write_public";
	}

	/**
	 * @return string
	 */
	public static function callbackURL()
	{
		return site_url() . '/?pinterest_callback=1';
	}

	/**
	 * @return bool
	 */
	public static function getAccessToken()
	{
		$appId = (int)Session::get('app_id');

		if (empty($appId)) {
			return false;
		}

		$code = Request::get('code', '', 'string');

		if (empty($code)) {
			$error_message = Request::get('error_message', '', 'str');

			self::error($error_message);
		}

		$proxy = Session::get('proxy');

		Session::remove('app_id');
		Session::remove('proxy');

		$appInf = DB::fetch('apps', ['id' => $appId, 'driver' => 'pinterest']);
		$appSecret = urlencode($appInf['app_secret']);
		$appId2 = urlencode($appInf['app_id']);

		$token_url = "https://api.pinterest.com/v1/oauth/token?grant_type=authorization_code&client_id={$appId2}&client_secret={$appSecret}&code={$code}";

		$response = Curl::getContents($token_url, 'POST', [], [], $proxy);
		$params = json_decode($response, true);

		if (isset($params['message'])) {
			self::error($params['message']);
		}

		$access_token = esc_html($params['access_token']);

		self::authorize($appId, $access_token, $proxy);
	}

	/**
	 * @param integer $appId
	 * @param string $accessToken
	 * @param string $proxy
	 */
	public static function authorize($appId, $accessToken, $proxy)
	{
		$me = self::cmd('me', 'GET', $accessToken, ['fields' => 'id,username,image,first_name,last_name,counts'], $proxy);

		if (isset($me['message']) && is_string($me['message'])) {
			self::error($me['message']);
		}

		if (!isset($me['data'])) {
			self::error();
		}

		$me = $me['data'];
		$meId = $me['id'];

		$checkLoginRegistered = DB::fetch('accounts', [
			'blog_id' => Helper::getBlogId(),
			'user_id' => get_current_user_id(),
			'driver' => 'pinterest',
			'profile_id' => $meId
		]);

		$dataSQL = [
			'blog_id' => Helper::getBlogId(),
			'user_id' => get_current_user_id(),
			'name' => $me['first_name'] . ' ' . $me['last_name'],
			'driver' => 'pinterest',
			'profile_id' => $meId,
			'profile_pic' => $me['image']['60x60']['url'],
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
			'access_token' => $accessToken
		]);

		// set default board
		$boards = self::cmd('me/boards', 'GET', $accessToken, ['fields' => 'id,name,url,image'], $proxy);

		if (isset($boards['data']) && is_array($boards['data']) && !empty($boards['data'])) {
			foreach ($boards['data'] as $board) {
				$boardId = $board['id'];
				$boardName = $board['name'];
				$screenName = str_replace('https://www.pinterest.com/', '', $board['url']);
				$image = $board['image'];

				$image = reset($image);
				$image = isset($image['url']) ? $image['url'] : '';

				DB::DB()->insert(DB::table('account_nodes'), [
					'blog_id' => Helper::getBlogId(),
					'user_id' => get_current_user_id(),
					'driver' => 'pinterest',
					'account_id' => $accId,
					'node_type' => 'board',
					'node_id' => $boardId,
					'name' => $boardName,
					'cover' => $image,
					'screen_name' => $screenName
				]);
			}
		}

		self::closeWindow();
	}

	/**
	 * @param integer $post_id
	 * @param string $accessToken
	 * @param string $proxy
	 * @return array
	 */
	public static function getStats($post_id, $accessToken, $proxy)
	{
		$result = self::cmd('pins/' . $post_id, 'GET', $accessToken, ['fields' => 'counts'], $proxy);

		return [
			'comments' => isset($result['data']['counts']['comments']) ? $result['data']['counts']['comments'] : 0,
			'like' => isset($result['data']['counts']['saves']) ? $result['data']['counts']['saves'] : 0,
			'shares' => 0,
			'details' => 'Saves: ' . (isset($result['data']['counts']['saves']) ? $result['data']['counts']['saves'] : 0)
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

		$me = self::cmd('me', 'GET', $accessToken, ['fields' => 'id,username,image,first_name,last_name,counts'], $proxy);

		if (isset($me['message']) && is_string($me['message'])) {
			$result['error_msg'] = $me['message'];
		} else if (isset($me['data'])) {
			$result['error'] = false;
		}

		return $result;
	}
}
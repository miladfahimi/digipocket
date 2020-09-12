<?php

namespace FSPoster\App\Libraries\vk;

use FSPoster\App\Providers\Curl;
use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;

class Vk
{

	/**
	 * @return string
	 */
	public static function callbackURL()
	{
		return site_url() . '/?vk_callback=1';
	}

	/**
	 * @param integer $appId
	 * @param string $accessToken
	 * @param string $proxy
	 * @return mixed
	 */
	public static function authorizeVkUser($appId, $accessToken, $proxy)
	{
		$me = self::cmd('users.get', 'GET', $accessToken, ['fields' => 'id,first_name,last_name,screen_name, sex, bdate,photo,common_count'], $proxy);

		if (isset($me['error']) && isset($me['error']['message'])) {
			Helper::response(false, $me['error']['message']);
		} else if (isset($me['error'])) {
			return $me;
		}

		$me = reset($me);
		$meId = $me['id'];

		$checkLoginRegistered = DB::fetch('accounts', [
			'blog_id' => Helper::getBlogId(),
			'user_id' => get_current_user_id(),
			'driver' => 'vk',
			'profile_id' => $meId
		]);

		$dataSQL = [
			'blog_id'           => Helper::getBlogId(),
			'user_id'           => get_current_user_id(),
			'name'              => $me['first_name'] . ' ' . $me['last_name'],
			'driver'            => 'vk',
			'profile_id'        => $meId,
			'profile_pic'       => $me['photo'],
			'username'          => $me['screen_name'],
			'proxy'             => $proxy
		];

		if (!$checkLoginRegistered) {
			DB::DB()->insert(DB::table('accounts'), $dataSQL);

			$accId = DB::DB()->insert_id;
		} else {
			$accId = $checkLoginRegistered['id'];

			DB::DB()->update(DB::table('accounts'), $dataSQL, ['id' => $accId]);

			DB::DB()->delete(DB::table('account_access_tokens'), ['account_id' => $accId, 'app_id' => $appId]);

			DB::DB()->delete(DB::table('account_nodes'), ['account_id' => $accId]);
		}

		// acccess token
		DB::DB()->insert(DB::table('account_access_tokens'), [
			'account_id' => $accId,
			'app_id' => $appId,
			'expires_on' => null,
			'access_token' => $accessToken
		]);


		$loadedOwnPages = [];
		// admins comunications
		if (Helper::getOption('vk_load_admin_communities', 1) == 1) {
			$accounts_list = self::cmd('groups.get', 'GET', $accessToken, [
				'filter' => 'admin',
				'extended' => '1',
				'fields' => 'members_count'
			], $proxy);
			if (isset($accounts_list['items']) && is_array($accounts_list['items'])) {
				foreach ($accounts_list['items'] as $account_info) {
					$loadedOwnPages[$account_info['id']] = true;

					DB::DB()->insert(DB::table('account_nodes'), [
						'blog_id' => Helper::getBlogId(),
						'user_id' => get_current_user_id(),
						'driver' => 'vk',
						'screen_name' => $account_info['screen_name'],
						'account_id' => $accId,
						'node_type' => $account_info['type'],
						'node_id' => $account_info['id'],
						'name' => $account_info['name'],
						'access_token' => null,
						'category' => 'admin',
						'cover' => $account_info['photo_50']
					]);
				}
			}
		}

		// members comunications
		if (Helper::getOption('vk_load_members_communities', 1) == 1) {
			$limit = Helper::getOption('vk_max_communities_limit', 100);
			$limit = $limit >= 0 ? $limit : 0;

			$accounts_list = self::cmd('groups.get', 'GET', $accessToken, [
				'extended' => '1',
				'fields' => 'members_count',
				'count' => $limit
			], $proxy);

			if (isset($accounts_list['items']) && is_array($accounts_list['items'])) {
				foreach ($accounts_list['items'] as $account_info) {
					if (isset($loadedOwnPages[$account_info['id']])) {
						continue;
					}

					DB::DB()->insert(DB::table('account_nodes'), [
						'blog_id' => Helper::getBlogId(),
						'user_id' => get_current_user_id(),
						'driver' => 'vk',
						'screen_name' => $account_info['screen_name'],
						'account_id' => $accId,
						'node_type' => $account_info['type'],
						'node_id' => $account_info['id'],
						'name' => $account_info['name'],
						'access_token' => null,
						'category' => '',
						'cover' => isset($account_info['photo_50']) ? $account_info['photo_50'] : ''
					]);
				}
			}
		}
	}

	/**
	 * @param string $cmd
	 * @param string $method
	 * @param string $accessToken
	 * @param array $data
	 * @param string $proxy
	 * @return array|mixed
	 */
	public static function cmd($cmd, $method, $accessToken, array $data = [], $proxy = '')
	{
		$data['access_token'] = $accessToken;
		$data['v'] = '5.69';

		$url = 'https://api.vk.com/method/' . $cmd;

		$method = $method === 'POST' ? 'POST' : ($method === 'DELETE' ? 'DELETE' : 'GET');

		$data1 = Curl::getContents($url, $method, $data, [], $proxy);
		$data = json_decode($data1, true);

		if (!is_array($data) || !isset($data['response'])) {
			return [
				'error' => ['message' => isset($data['error']) && isset($data['error']['error_msg']) ? $data['error']['error_msg'] : (isset($data['error']) && isset($data['error']['message']) ? $data['error']['message'] : 'Error!' . htmlspecialchars($data1))]
			];
		}

		return $data['response'];
	}

	/**
	 * @param integer $accountId
	 * @return mixed
	 */
	public static function authorizeVkUserById($accountId)
	{
		$check_account = DB::fetch('accounts', [
			'driver' => 'vk',
			'id' => $accountId
		]);

		if ($check_account) {
			$accessTokens = DB::fetch('account_access_tokens', [
				'account_id' => $check_account['id']
			]);

			if ($accessTokens) {
				$accessToken = $accessTokens['access_token'];
				$loadedOwnPages = self::getAdminCommunities($accessToken);
				$loadedPages = self::getMemberCommunities($accessToken, $loadedOwnPages);

				$accountCommunities = DB::fetchAll('account_nodes', [
					'account_id' => $accountId
				]);

				if ($accountCommunities) {
					foreach ($accountCommunities as $accountCommunity) {
						if (!array_key_exists($accountCommunity['node_id'], $loadedOwnPages)) {
							DB::DB()->delete(DB::table('account_nodes'), ['node_id' => $accountCommunity['node_id']]);
						}
					}
				}
			}
		}
	}

	/**
	 * @param string $accessToken
	 * @return array
	 */
	public static function getAdminCommunities($accessToken)
	{
		$communityIds = [];

		if (Helper::getOption('vk_load_admin_communities', 1) == 1) {
			$accounts_list = self::cmd('groups.get', 'GET', $accessToken, [
				'filter' => 'admin',
				'extended' => '1',
				'fields' => 'members_count'
			], '');

			if (isset($accounts_list['items']) && is_array($accounts_list['items'])) {
				foreach ($accounts_list['items'] as $account_info) {
					$communityIds[$account_info['id']] = true;

					$getCommunity = DB::fetch('account_nodes', [
						'node_id' => $account_info['id']
					]);

					if ($getCommunity) {
						continue;
					}

					DB::DB()->insert(DB::table('account_nodes'), [
						'blog_id' => Helper::getBlogId(),
						'user_id' => get_current_user_id(),
						'driver' => 'vk',
						'screen_name' => $account_info['screen_name'],
						'account_id' => $account_info['id'],
						'node_type' => $account_info['type'],
						'node_id' => $account_info['id'],
						'name' => $account_info['name'],
						'access_token' => null,
						'category' => 'admin',
						'cover' => $account_info['photo_50']
					]);
				}
			}
		}

		return $communityIds;
	}

	/**
	 * @param string $accessToken
	 * @param array $loadedOwnPages
	 * @return array
	 */
	public static function getMemberCommunities($accessToken, $loadedOwnPages)
	{
		$communityIds = $loadedOwnPages;

		if (Helper::getOption('vk_load_members_communities', 1) == 1) {
			$limit = Helper::getOption('vk_max_communities_limit', 100);
			$limit = $limit >= 0 ? $limit : 0;

			$accounts_list = self::cmd('groups.get', 'GET', $accessToken, [
				'extended' => '1',
				'fields' => 'members_count',
				'count' => $limit
			], '');

			if (isset($accounts_list['items']) && is_array($accounts_list['items'])) {
				foreach ($accounts_list['items'] as $account_info) {
					if (isset($loadedOwnPages[$account_info['id']])) {
						continue;
					}

					$communityIds[$account_info['id']] = true;

					$getCommunity = DB::fetch('account_nodes', [
						'node_id' => $account_info['id']
					]);

					if ($getCommunity) {
						continue;
					}

					DB::DB()->insert(DB::table('account_nodes'), [
						'blog_id' => Helper::getBlogId(),
						'user_id' => get_current_user_id(),
						'driver' => 'vk',
						'screen_name' => $account_info['screen_name'],
						'account_id' => $account_info['id'],
						'node_type' => $account_info['type'],
						'node_id' => $account_info['id'],
						'name' => $account_info['name'],
						'access_token' => null,
						'category' => '',
						'cover' => isset($account_info['photo_50']) ? $account_info['photo_50'] : ''
					]);
				}
			}
		}

		return $communityIds;
	}

	/**
	 * @param string $nodeFbId
	 * @param string $type
	 * @param string $message
	 * @param string $link
	 * @param array $images
	 * @param string $video
	 * @param string $accessToken
	 * @param string $proxy
	 * @return array
	 */
	public static function sendPost($nodeFbId, $type, $message, $link, $images, $video, $accessToken, $proxy)
	{
		$sendData = [
			'message' => $message,
			'owner_id' => $nodeFbId
		];

		if ($type === 'link') {
			$sendData['attachments'] = $link;
		} else if ($type === 'image' || $type === 'image_link') {
			if ($type === 'image_link') {
				$sendData['attachments'] = [$link];
			} else {
				$sendData['attachments'] = [];
			}

			$uplData = [];
			if ($nodeFbId < 0) {
				$uplData['group_id'] = abs($nodeFbId);
			}

			$uplServer = self::cmd('photos.getWallUploadServer', 'GET', $accessToken, $uplData, $proxy);

			if (isset($uplServer['upload_url'])) {
				$uplServer = $uplServer['upload_url'];

				$images2 = [];
				$i = 0;
				foreach ($images as $imageURL) {
					$i++;
					if (function_exists('curl_file_create')) {
						$images2['file' . $i] = curl_file_create($imageURL);
					} else {
						$images2['file' . $i] = '@' . $imageURL;
					}
				}

				$uploadFile = Curl::getContents($uplServer, 'POST', $images2, [], $proxy);
				$uploadFile = json_decode($uploadFile, true);

				if ($nodeFbId < 0) {
					$uploadFile['group_id'] = abs($nodeFbId);

				} else {
					$uploadFile['user_id'] = $nodeFbId;
				}

				if (is_array($uploadFile) && !empty($uploadFile)) {
					$uploadPhoto = self::cmd('photos.saveWallPhoto', 'GET', $accessToken, $uploadFile, $proxy);

					if (is_array($uploadPhoto) && !isset($uploadPhoto['error'])) {
						foreach ($uploadPhoto as $photoInf) {
							$sendData['attachments'][] = 'photo' . $photoInf['owner_id'] . '_' . $photoInf['id'];
						}
					}
					$sendData['attachments'] = implode(',', $sendData['attachments']);
				}
			}
		} else if ($type === 'video') {
			$videoUplServer = self::cmd('video.save', 'GET', $accessToken, [
				'name' => mb_substr($message, 0, 50, 'UTF-8'),
				'wallpost' => 1
			], $proxy);

			if (isset($videoUplServer['owner_id']) && isset($videoUplServer['video_id']) && isset($videoUplServer['upload_url'])) {
				$ownerId = $videoUplServer['owner_id'];
				$videoId = $videoUplServer['video_id'];
				$uploadURL = $videoUplServer['upload_url'];

				$uploadFile = Curl::getContents($uploadURL, 'POST', [
					'file' => function_exists('curl_file_create') ? curl_file_create($video) : '@' . $video
				], [], $proxy);
				$uploadFile = json_decode($uploadFile, true);

				if (!isset($uploadFile['error'])) {
					$sendData['attachments'] = 'video' . $ownerId . '_' . $videoId;
				}
			}
		}

		$result = self::cmd('wall.post', 'POST', $accessToken, $sendData, $proxy);

		if (isset($result['error'])) {
			$result2 = [
				'status' => 'error',
				'error_msg' => isset($result['error']['message']) ? $result['error']['message'] : 'Error!'
			];
		} else if (isset($result['post_id']) && intval($result['post_id']) === 0) {
			$result2 = [
				'status' => 'error',
				'error_msg' => 'Error!'
			];
		} else {
			$result2 = [
				'status' => 'ok',
				'id' => $nodeFbId . '_' . $result['post_id']
			];
		}

		return $result2;
	}

	/**
	 * @param integer $post_id
	 * @param string $accessToken
	 * @param string $proxy
	 * @return array
	 */
	public static function getStats($post_id, $accessToken, $proxy)
	{
		$stat = self::cmd('wall.getById', 'GET', $accessToken, ['posts' => $post_id], $proxy);
		$stat = is_array($stat) && isset($stat[0]) ? $stat[0] : array();

		return [
			'comments' => isset($stat['comments']['count']) ? (int) $stat['comments']['count'] : 0,
			'like' => isset($stat['likes']['count']) ? (int) $stat['likes']['count'] : 0,
			'shares' => isset($stat['reposts']['count']) ? (int) $stat['reposts']['count'] : 0,
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
		$me = self::cmd('users.get', 'GET', $accessToken, ['fields' => 'id,first_name,last_name,screen_name, sex, bdate,photo,common_count'], $proxy);

		if (isset($me['error']) && isset($me['error']['message'])) {
			$result['error_msg'] = $me['error']['message'];
		} else if (!isset($me['error'])) {
			$result['error'] = false;
		}

		return $result;
	}
}
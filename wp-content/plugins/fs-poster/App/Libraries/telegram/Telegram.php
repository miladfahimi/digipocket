<?php

namespace FSPoster\App\Libraries\telegram;

use Exception;
use FSP_GuzzleHttp\Client;

class Telegram
{
	private $token;
	private $client;

	public function __construct ( $botToken, $proxy = '' )
	{
		$this->token = $botToken;

		$this->client = new Client( [
			'allow_redirects' => [ 'max' => 20 ],
			'proxy'           => empty( $proxy ) ? NULL : $proxy,
			'verify'          => FALSE,
			'http_errors'     => FALSE,
			'headers'         => [ 'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0' ]
		] );
	}

	public function getBotInfo ()
	{
		$myInfo = $this->cmd( 'getMe' );

		if ( ! $myInfo[ 'ok' ] )
		{
			return [
				'id'   => 0,
				'name' => ''
			];
		}

		return [
			'id'       => isset( $myInfo[ 'result' ][ 'id' ] ) ? $myInfo[ 'result' ][ 'id' ] : '',
			'name'     => isset( $myInfo[ 'result' ][ 'first_name' ] ) ? $myInfo[ 'result' ][ 'first_name' ] : '',
			'username' => isset( $myInfo[ 'result' ][ 'username' ] ) ? $myInfo[ 'result' ][ 'username' ] : ''
		];
	}

	private function cmd ( $method, $params = [] )
	{
		$url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;

		if ( ! empty( $params ) )
		{
			$url .= '?' . http_build_query( $params );
		}

		try
		{
			$request = (string) $this->client->request( 'GET', $url )->getBody();
		}
		catch ( Exception $e )
		{
			return [
				'ok'          => FALSE,
				'description' => 'Error! ' . $e->getMessage()
			];
		}

		$request = json_decode( $request, TRUE );

		if ( ! isset( $request[ 'ok' ] ) || ( $request[ 'ok' ] && ! isset( $request[ 'result' ] ) ) )
		{
			return [
				'ok'          => FALSE,
				'description' => 'Unknown error!'
			];
		}

		return $request;
	}

	public function getChatInfo ( $chatId )
	{
		if ( ! is_numeric( $chatId ) && strpos( $chatId, '@' ) !== 0 )
		{
			$chatId = '@' . $chatId;
		}

		$myInfo = $this->cmd( 'getChat', [ 'chat_id' => $chatId ] );

		if ( ! $myInfo[ 'ok' ] )
		{
			return [
				'id'       => 0,
				'name'     => '',
				'username' => '',
				'type'     => ''
			];
		}

		return [
			'id'       => isset( $myInfo[ 'result' ][ 'id' ] ) ? $myInfo[ 'result' ][ 'id' ] : '',
			'name'     => isset( $myInfo[ 'result' ][ 'title' ] ) ? $myInfo[ 'result' ][ 'title' ] : ( isset( $myInfo[ 'result' ][ 'first_name' ] ) ? $myInfo[ 'result' ][ 'first_name' ] : '' ),
			'username' => isset( $myInfo[ 'result' ][ 'username' ] ) ? $myInfo[ 'result' ][ 'username' ] : '',
			'type'     => isset( $myInfo[ 'result' ][ 'type' ] ) ? $myInfo[ 'result' ][ 'type' ] : ''
		];
	}

	public function getActiveChats ()
	{
		$updates = $this->cmd( 'getUpdates', [ 'allowed_updates' => 'message' ] );

		if ( ! $updates[ 'ok' ] )
		{
			return [];
		}

		$list      = [];
		$uniqChats = [];

		foreach ( $updates[ 'result' ] as $update )
		{
			if ( ! isset( $update[ 'message' ] ) )
			{
				continue;
			}

			$chatId = isset( $update[ 'message' ][ 'chat' ][ 'id' ] ) ? $update[ 'message' ][ 'chat' ][ 'id' ] : '';

			if ( isset( $uniqChats[ $chatId ] ) )
			{
				continue;
			}

			$uniqChats[ $chatId ] = TRUE;

			if ( isset( $update[ 'message' ][ 'chat' ][ 'first_name' ] ) )
			{
				$name = $update[ 'message' ][ 'chat' ][ 'first_name' ];
			}
			else if ( isset( $update[ 'message' ][ 'chat' ][ 'title' ] ) )
			{
				$name = $update[ 'message' ][ 'chat' ][ 'title' ];
			}
			else
			{
				$name = '[unnamed]';
			}

			$list[] = [
				'id'   => $chatId,
				'name' => $name
			];
		}

		return $list;
	}

	public function sendPost ( $chatId, $text, $sendType, $media = '' )
	{
		if ( $sendType === 'image' )
		{
			$post = $this->cmd( 'sendPhoto', [
				'chat_id'    => $chatId,
				'caption'    => $text,
				'parse_mode' => 'HTML',
				'photo'      => $media
			] );
		}
		else if ( $sendType === 'video' )
		{
			$post = $this->cmd( 'sendVideo', [
				'chat_id'    => $chatId,
				'caption'    => $text,
				'parse_mode' => 'HTML',
				'video'      => $media
			] );
		}
		else
		{
			$post = $this->cmd( 'sendMessage', [
				'chat_id'    => $chatId,
				'text'       => $text,
				'parse_mode' => 'HTML'
			] );
		}

		if ( ! $post[ 'ok' ] )
		{
			return [
				'status'    => 'error',
				'error_msg' => isset( $post[ 'description' ] ) && is_string( $post[ 'description' ] ) ? esc_html( $post[ 'description' ] ) : 'Error! Can\'t send message!'
			];
		}

		return [
			'status' => 'ok',
			'id'     => isset( $post[ 'result' ][ 'message_id' ] ) ? $post[ 'result' ][ 'message_id' ] : 0
		];
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
		$myInfo = $this->cmd( 'getMe' );

		if ( ! $myInfo[ 'ok' ] )
		{
			$result[ 'error_msg' ] = $myInfo[ 'description' ];
		}
		else
		{
			$result[ 'error' ] = FALSE;
		}

		return $result;
	}
}

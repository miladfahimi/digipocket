<?php

namespace FSPoster\App\Pages\Apps\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Libraries\fb\Facebook;

trait Ajax
{
	public function delete_app ()
	{
		$id = Request::post( 'id', '0', 'int' );
		if ( ! $id )
		{
			exit();
		}

		$check_app = DB::fetch( 'apps', $id );
		if ( ! $check_app )
		{
			Helper::response( FALSE, esc_html__( 'There isn\'t an App!', 'fs-poster' ) );
		}
		else
		{
			if ( $check_app[ 'user_id' ] != get_current_user_id() )
			{
				Helper::response( FALSE, esc_html__( 'You don\'t have permission to delete the App!', 'fs-poster' ) );
			}
			else
			{
				if ( $check_app[ 'is_standart' ] > 0 )
				{
					Helper::response( FALSE, esc_html__( 'You can\'t delete the App!', 'fs-poster' ) );
				}
			}
		}

		DB::DB()->delete( DB::table( 'apps' ), [ 'id' => $id ] );

		Helper::response( TRUE );
	}

	public function add_new_app ()
	{
		$data                 = [];
		$data[ 'app_id' ]     = Request::post( 'app_id', '', 'string' );
		$data[ 'app_key' ]    = Request::post( 'app_key', '', 'string' );
		$data[ 'app_secret' ] = Request::post( 'app_secret', '', 'string' );
		$data[ 'version' ]    = Request::post( 'version', 0, 'int', [ 0, 31, 32, 33, 40, 50, 60, 70, 80 ] );
		$driver               = Request::post( 'driver', '', 'string' );

		$appSupports = [
			'fb'        => [ 'app_id', 'app_key', 'version' ],
			'twitter'   => [ 'app_key', 'app_secret' ],
			'linkedin'  => [ 'app_id', 'app_secret' ],
			'vk'        => [ 'app_id', 'app_secret' ],
			'pinterest' => [ 'app_id', 'app_secret' ],
			'reddit'    => [ 'app_id', 'app_secret' ],
			'tumblr'    => [ 'app_key', 'app_secret' ],
			'ok'        => [ 'app_id', 'app_key', 'app_secret' ],
			'medium'    => [ 'app_id', 'app_secret' ],
		];

		if ( ! isset( $appSupports[ $driver ] ) )
		{
			Helper::response( FALSE );
		}

		$checkParams              = [];
		$checkParams[ 'user_id' ] = get_current_user_id();
		$checkParams[ 'driver' ]  = $driver;
		foreach ( $appSupports[ $driver ] as $field1 )
		{
			if ( empty( $data[ $field1 ] ) )
			{
				Helper::response( FALSE, $field1 . ' ' . esc_html__( 'field is empty!', 'fs-poster' ) );
			}

			$checkParams[ $field1 ] = $data[ $field1 ];
		}

		$check_appIdExist = DB::fetch( 'apps', $checkParams );
		if ( $check_appIdExist )
		{
			Helper::response( FALSE, [ 'error_msg' => esc_html__( 'The App has already been added.', 'fs-poster' ) ] );
		}

		if ( $driver === 'fb' )
		{
			$appName = Facebook::checkApp( $data[ 'app_id' ], $data[ 'app_key' ], $data[ 'version' ] );
		}

		$checkParams[ 'is_public' ]   = 0;
		$checkParams[ 'is_standart' ] = 0;

		$checkParams[ 'name' ] = isset( $appName ) ? $appName : ( empty( $data[ 'app_id' ] ) ? $data[ 'app_key' ] : $data[ 'app_id' ] );

		DB::DB()->insert( DB::table( 'apps' ), $checkParams );

		Helper::response( TRUE, [ 'id' => DB::DB()->insert_id, 'name' => esc_html( $checkParams[ 'name' ] ) ] );
	}
}
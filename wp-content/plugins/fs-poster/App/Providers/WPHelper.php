<?php

namespace FSPoster\App\Providers;

use WC_Product_Variation;

trait WPHelper
{
	private static $originalBlogId;

	public static function getBlogId ()
	{
		return get_current_blog_id();
	}

	public static function setBlogId ( $new_blog_id )
	{
		if ( ! is_multisite() )
		{
			return;
		}

		if ( is_null( self::$originalBlogId ) )
		{
			self::$originalBlogId = self::getBlogId();
		}

		switch_to_blog( $new_blog_id );
	}

	public static function resetBlogId ()
	{
		if ( ! is_multisite() )
		{
			return;
		}

		if ( ! is_null( self::$originalBlogId ) )
		{
			switch_to_blog( self::$originalBlogId );
		}
	}

	public static function getBlogs ()
	{
		if ( ! is_multisite() )
		{
			return [ 1 ];
		}

		$sites   = get_sites();
		$siteIDs = [];

		foreach ( $sites as $site )
		{
			$siteIDs[] = $site->blog_id;
		}

		return $siteIDs;
	}

	/**
	 * @param $productInf
	 * @param string $getType
	 *
	 * @return array|string
	 */
	public static function getProductPrice ( $productInf, $getType = '' )
	{
		$productRegularPrice = '';
		$productSalePrice    = '';
		$productId           = $productInf[ 'post_type' ] === 'product_variation' ? $productInf[ 'post_parent' ] : $productInf[ 'ID' ];

		if ( ( $productInf[ 'post_type' ] === 'product' || $productInf[ 'post_type' ] === 'product_variation' ) && function_exists( 'wc_get_product' ) )
		{
			$product = wc_get_product( $productId );

			if ( $product->is_type( 'variable' ) )
			{
				$variation_id     = $product->get_children();
				$variable_product = new WC_Product_Variation( reset( $variation_id ) );

				$productRegularPrice = $variable_product->get_regular_price();
				$productSalePrice    = $variable_product->get_sale_price();
			}
			else //else if ( $product->is_type( 'simple' ) )
			{
				$productRegularPrice = $product->get_regular_price();
				$productSalePrice    = $product->get_sale_price();
			}
		}

		if ( empty( $productRegularPrice ) && $productSalePrice > $productRegularPrice )
		{
			$productRegularPrice = $productSalePrice;
		}

		if ( $getType === 'price' )
		{
			return empty( $productSalePrice ) ? $productRegularPrice : $productSalePrice;
		}
		else if ( $getType === 'regular' )
		{
			return $productRegularPrice;
		}
		else if ( $getType === 'sale' )
		{
			return $productSalePrice;
		}
		else
		{
			return [
				'regular' => $productRegularPrice,
				'sale'    => $productSalePrice
			];
		}
	}

	/**
	 * @param $postInf
	 *
	 * @return string
	 */
	public static function getPostTags ( $postInf )
	{
		if ( get_post_type( $postInf[ 'ID' ] ) === 'product' )
		{
			$tags = wp_get_post_terms( $postInf[ 'ID' ], 'product_tag' );
		}
		else
		{
			if ( get_post_type( $postInf[ 'ID' ] ) === 'product_variation' )
			{
				$tags = wp_get_post_terms( $postInf[ 'post_parent' ], 'product_tag' );
			}
			else
			{
				$tags = wp_get_post_tags( $postInf[ 'ID' ] );
			}
		}

		$replaceWhitespaces = Helper::getOption( 'replace_whitespaces_with_underscore', '0' ) == 1 ? '_' : '';

		$tagsString = [];
		foreach ( $tags as $tagInf )
		{
			$tagsString[] = '#' . preg_replace( "/[\!\@\#\$\%\^\&\*\(\)\=\+\{\}\[\]\'\"\,\>\/\?\;\:\\\\\s]/", "", str_replace( ' ', $replaceWhitespaces, $tagInf->name ) );
		}
		$tagsString = implode( ' ', $tagsString );

		return mb_strtolower( $tagsString );
	}

	/**
	 * @param $postInf
	 *
	 * @return string
	 */
	public static function getPostCats ( $postInf )
	{
		if ( get_post_type( $postInf[ 'ID' ] ) === 'product' )
		{
			$cats = wp_get_post_terms( $postInf[ 'ID' ], 'product_cat' );
		}
		else
		{
			if ( get_post_type( $postInf[ 'ID' ] ) === 'product_variation' )
			{
				$cats = wp_get_post_terms( $postInf[ 'post_parent' ], 'product_cat' );
			}
			else
			{
				$cats = get_the_category( $postInf[ 'ID' ] );
			}
		}

		$replaceWhitespaces = Helper::getOption( 'replace_whitespaces_with_underscore', '0' ) == 1 ? '_' : '';

		$catsString = [];
		foreach ( $cats as $catInf )
		{
			$catsString[] = '#' . preg_replace( "/[\!\@\#\$\%\^\&\*\(\)\=\+\{\}\[\]\'\"\,\>\/\?\;\:\\\\\s]/", "", str_replace( ' ', $replaceWhitespaces, $catInf->name ) );
		}
		$catsString = implode( ' ', $catsString );

		return mb_strtolower( $catsString );
	}

	/**
	 * @param $link
	 * @param $feedId
	 * @param array $postInf
	 * @param array $account_info
	 *
	 * @return string
	 */
	public static function customizePostLink ( $link, $feedId, $postInf = [], $account_info = [] )
	{
		$parameters = [];

		if ( Helper::getOption( 'collect_statistics', '1' ) )
		{
			$parameters[] = 'feed_id=' . $feedId;
		}

		if ( Helper::getOption( 'unique_link', '1' ) == 1 )
		{
			$parameters[] = '_unique_id=' . uniqid();
		}

		$fs_url_additional = Helper::getOption( 'url_additional', '' );
		if ( ! empty( $fs_url_additional ) )
		{
			$post_id   = isset( $postInf[ 'ID' ] ) ? $postInf[ 'ID' ] : 0;
			$postTitle = isset( $postInf[ 'post_title' ] ) ? $postInf[ 'post_title' ] : '';
			$network   = isset( $account_info[ 'driver' ] ) ? $account_info[ 'driver' ] : '-';

			$networks = [
				'fb'        => [ 'FB', 'Facebook' ],
				'twitter'   => [ 'TW', 'Twitter' ],
				'instagram' => [ 'IG', 'Instagram' ],
				'linkedin'  => [ 'LN', 'LinkedIn' ],
				'vk'        => [ 'VK', 'VKontakte' ],
				'pinterest' => [ 'PI', 'Pinterest' ],
				'reddit'    => [ 'RE', 'Reddit' ],
				'tumblr'    => [ 'TU', 'Tumblr' ],
				'ok'        => [ 'OK', 'Odnoklassniki' ],
				'google_b'  => [ 'GB', 'Google My Business' ],
				'telegram'  => [ 'TG', 'Telegram' ],
				'medium'    => [ 'ME', 'Medium' ],
				'wordpress' => [ 'WP', 'WordPress' ],
			];

			$networkCode = isset( $networks[ $network ] ) ? $networks[ $network ][ 0 ] : '';
			$networkName = isset( $networks[ $network ] ) ? $networks[ $network ][ 1 ] : '';

			$userInf     = get_userdata( $postInf[ 'post_author' ] );
			$accountName = isset( $userInf->user_login ) ? $userInf->user_login : '-';

			$fs_url_additional = str_replace( [
				'{post_id}',
				'{post_title}',
				'{network_name}',
				'{network_code}',
				'{account_name}',
				'{site_name}',
				'{uniq_id}'
			], [
				rawurlencode( $post_id ),
				rawurlencode( $postTitle ),
				rawurlencode( $networkName ),
				rawurlencode( $networkCode ),
				rawurlencode( $accountName ),
				rawurlencode( get_bloginfo( 'name' ) ),
				uniqid()
			], $fs_url_additional );

			$parameters[] = $fs_url_additional;
		}

		if ( ! empty( $parameters ) )
		{
			$link .= strpos( $link, '?' ) !== FALSE ? '&' : '?';

			$parameters = implode( '&', $parameters );

			$link .= $parameters;
		}

		return $link;
	}

	/**
	 * @param $postInf
	 * @param $feedId
	 * @param $account_info
	 *
	 * @return string
	 */
	public static function getPostLink ( $postInf, $feedId, $account_info )
	{
		if ( Helper::getOption( 'share_custom_url', '0' ) )
		{
			$link = Helper::getOption( 'custom_url_to_share', '{site_url}/?feed_id={feed_id}' );

			$post_id   = isset( $postInf[ 'ID' ] ) ? $postInf[ 'ID' ] : 0;
			$postTitle = isset( $postInf[ 'post_title' ] ) ? $postInf[ 'post_title' ] : '';
			$network   = isset( $account_info[ 'driver' ] ) ? $account_info[ 'driver' ] : '-';

			$networks = [
				'fb'        => [ 'FB', 'Facebook' ],
				'twitter'   => [ 'TW', 'Twitter' ],
				'instagram' => [ 'IG', 'Instagram' ],
				'linkedin'  => [ 'LN', 'LinkedIn' ],
				'vk'        => [ 'VK', 'VKontakte' ],
				'pinterest' => [ 'PI', 'Pinterest' ],
				'reddit'    => [ 'RE', 'Reddit' ],
				'tumblr'    => [ 'TU', 'Tumblr' ],
				'ok'        => [ 'OK', 'Odnoklassniki' ],
				'google_b'  => [ 'GB', 'Google My Business' ],
				'telegram'  => [ 'TG', 'Telegram' ],
				'medium'    => [ 'ME', 'Medium' ],
				'wordpress' => [ 'WP', 'WordPress' ],
			];

			$networkCode = isset( $networks[ $network ] ) ? $networks[ $network ][ 0 ] : '';
			$networkName = isset( $networks[ $network ] ) ? $networks[ $network ][ 1 ] : '';

			$userInf     = get_userdata( $postInf[ 'post_author' ] );
			$accountName = isset( $userInf->user_login ) ? $userInf->user_login : '-';

			$link = str_replace( [
				'{post_id}',
				'{feed_id}',
				'{post_title}',
				'{network_name}',
				'{network_code}',
				'{account_name}',
				'{site_name}',
				'{uniq_id}',
				'{site_url}',
				'{site_url_encoded}',
				'{post_url}',
				'{post_url_encoded}',
			], [
				rawurlencode( $post_id ),
				rawurlencode( $feedId ),
				rawurlencode( $postTitle ),
				rawurlencode( $networkName ),
				rawurlencode( $networkCode ),
				rawurlencode( $accountName ),
				rawurlencode( get_bloginfo( 'name' ) ),
				uniqid(),
				site_url(),
				rawurlencode( site_url() ),
				get_permalink( $postInf[ 'ID' ] ),
				rawurlencode( get_permalink( $postInf[ 'ID' ] ) )
			], $link );

			// custom fields
			$link = preg_replace_callback( '/\{cf_(.+)\}/iU', function ( $n ) use ( $postInf ) {
				$customField = isset( $n[ 1 ] ) ? $n[ 1 ] : '';

				return rawurlencode( get_post_meta( $postInf[ 'ID' ], $customField, TRUE ) );
			}, $link );
		}
		else
		{
			$link = get_permalink( $postInf[ 'ID' ] );
			$link = Helper::customizePostLink( $link, $feedId, $postInf, $account_info );
		}

		return $link;
	}
}
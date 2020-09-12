<?php

namespace FSPoster\App\Providers;

class WPPostThumbnail
{
	/**
	 * @var array
	 */
	private static $saveCacheFiles = [];

	/**
	 * @param $post_id
	 *
	 * @return false|string
	 */
	public static function getPostThumbnailURL ( $post_id )
	{
		$mediaId = get_post_thumbnail_id( $post_id );

		if ( empty( $mediaId ) )
		{
			$media = get_attached_media( 'image', $post_id );
			$first = reset( $media );

			$mediaId = isset( $first->ID ) ? $first->ID : 0;
		}

		$url = $mediaId > 0 ? wp_get_attachment_url( $mediaId ) : '';

		return empty( $url ) ? FALSE : $url;
	}

	/**
	 * @param $post_id
	 *
	 * @return false|string
	 */
	public static function getPostThumbnail ( $post_id )
	{
		$mediaId = get_post_thumbnail_id( $post_id );

		if ( empty( $mediaId ) )
		{
			$media   = get_attached_media( 'image', $post_id );
			$first   = reset( $media );
			$mediaId = isset( $first->ID ) ? $first->ID : 0;
		}

		$imagePath = $mediaId > 0 ? get_attached_file( $mediaId ) : '';

		if ( ! empty( $imagePath ) )
		{
			if ( ! file_exists( $imagePath ) )
			{
				$imagePath = tempnam( sys_get_temp_dir(), 'FS_tmpfile_' );

				Helper::downloadRemoteFile( $imagePath, wp_get_attachment_url( $mediaId ) );

				self::$saveCacheFiles[] = $imagePath;
			}
		}

		return empty( $imagePath ) ? FALSE : $imagePath;
	}

	/**
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function getPostGalleryURL ( $post_id, $postType )
	{
		$images = [];

		$mediaId = get_post_thumbnail_id( $post_id );
		if ( $mediaId > 0 )
		{
			$images[] = wp_get_attachment_url( $mediaId );
		}

		if ( $postType === 'product' || $postType === 'product_variation' )
		{
			$product        = wc_get_product( $post_id );
			$attachment_ids = $product->get_gallery_attachment_ids();

			foreach ( $attachment_ids as $attachmentId )
			{
				$_imageURL = wp_get_attachment_url( $attachmentId );
				if ( ! in_array( $_imageURL, $images ) )
				{
					$images[] = $_imageURL;
				}
			}
		}
		else
		{
			$allImgaes = get_attached_media( 'image', $post_id );

			foreach ( $allImgaes as $mediaInf )
			{
				$mediaId2 = isset( $mediaInf->ID ) ? $mediaInf->ID : 0;
				if ( $mediaId2 > 0 )
				{
					$_imageURL = wp_get_attachment_url( $mediaId2 );
					if ( ! in_array( $_imageURL, $images ) )
					{
						$images[] = $_imageURL;
					}
				}
			}
		}

		return $images;
	}

	/**
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function getPostGallery ( $post_id, $postType )
	{
		$images = [];

		$mediaId = get_post_thumbnail_id( $post_id );
		if ( $mediaId > 0 )
		{
			$images[] = get_attached_file( $mediaId );
		}

		if ( $postType === 'product' || $postType === 'product_variation' )
		{
			$product        = wc_get_product( $post_id );
			$attachment_ids = $product->get_gallery_attachment_ids();

			foreach ( $attachment_ids as $attachmentId )
			{
				$_imageURL = get_attached_file( $attachmentId );
				if ( ! in_array( $_imageURL, $images ) )
				{
					$images[] = $_imageURL;
				}
			}
		}
		else
		{
			$allImgaes = get_attached_media( 'image', $post_id );

			foreach ( $allImgaes as $mediaInf )
			{
				$mediaId2 = isset( $mediaInf->ID ) ? $mediaInf->ID : 0;
				if ( $mediaId2 > 0 )
				{
					$_imageURL = get_attached_file( $mediaId2 );
					if ( ! in_array( $_imageURL, $images ) )
					{
						$images[] = $_imageURL;
					}
				}
			}
		}

		return $images;
	}

	/**
	 * Clear cache
	 */
	public static function clearCache ()
	{
		foreach ( self::$saveCacheFiles as $cacheFile )
		{
			if ( file_exists( $cacheFile ) )
			{
				unlink( $cacheFile );
			}
		}

		self::$saveCacheFiles = [];
	}
}
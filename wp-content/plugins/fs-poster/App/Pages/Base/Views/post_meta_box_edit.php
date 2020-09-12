<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<link rel="stylesheet" href="<?php echo Pages::asset( 'Base', 'css/fsp-metabox.css' ); ?>">
<link rel="stylesheet" href="<?php echo Pages::asset( 'Share', 'css/fsp-sharing-popup.css' ); ?>">

<div class="fsp-metabox fsp-is-mini">
	<div class="fsp-metabox-p">
		<?php echo esc_html__( 'Shared on:', 'fs-poster' ); ?>
	</div>
	<div class="fsp-card-body fsp-metabox-accounts">
		<?php
		$feedsCount = 0;
		foreach ( $fsp_params[ 'feeds' ] as $feedInf )
		{
			$node_infoTable = $feedInf[ 'node_type' ] === 'account' ? 'accounts' : 'account_nodes';
			$node_info      = DB::fetch( $node_infoTable, $feedInf[ 'node_id' ] );

			if ( empty( $node_info ) )
			{
				continue;
			}

			if ( $feedInf[ 'node_type' ] === 'account' )
			{
				$node_info[ 'node_type' ] = 'account';
			}

			$feedsCount++;
			?>

			<div class="fsp-sharing-account" data-id="<?php echo (int) $feedInf[ 'id' ]; ?>">
				<div class="fsp-sharing-account-image">
					<img src="<?php echo Helper::profilePic( $node_info ); ?>" onerror="FSPoster.no_photo( this );">
				</div>
				<div class="fsp-sharing-account-info">
					<a target="_blank" href="<?php echo Helper::profileLink( $node_info ); ?>" class="fsp-sharing-account-info-text">
						<?php echo esc_html( $node_info[ 'name' ] ); ?>
					</a>
					<div class="fsp-sharing-account-info-subtext">
						<?php echo esc_html( ucfirst( $node_info[ 'driver' ] ) . ' > ' . $node_info[ 'node_type' ] . ( $feedInf[ 'feed_type' ] === 'story' ? ' > Story' : '' ) ); ?>
					</div>
				</div>
				<div class="fsp-sharing-account-status">
					<?php if ( $feedInf[ 'status' ] === 'ok' )
					{
						if ( $node_info[ 'driver' ] === 'twitter' )
						{
							$username = isset( $node_info[ 'username' ] ) ? $node_info[ 'username' ] : '';
						}
						else if ( $node_info[ 'driver' ] === 'google_b' )
						{
							$username = isset( $node_info[ 'node_id' ] ) ? $node_info[ 'node_id' ] : '';
						}
						else if ( $node_info[ 'driver' ] === 'wordpress' )
						{
							$username = isset( $node_info[ 'options' ] ) ? $node_info[ 'options' ] : '';
						}
						else
						{
							$username = ( isset( $node_info[ 'screen_name' ] ) ? $node_info[ 'screen_name' ] : '' );
						}
						?>

						<a href="<?php echo Helper::postLink( $feedInf[ 'driver_post_id' ], $feedInf[ 'driver' ] . ( $feedInf[ 'driver' ] === 'instagram' ? $feedInf[ 'feed_type' ] : '' ), $username ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
						<div class="fsp-status fsp-is-success fsp-tooltip" data-title="<?php echo esc_html__( 'Posted successfully', 'fs-poster' ); ?>">
							<i class="fas fa-check"></i></div>
					<?php }
					else if ( $feedInf[ 'is_sended' ] === '0' )
					{
						$message       = esc_html__( 'Going to share in a minute', 'fs-poster' );
						$shareTimerSec = Date::epoch( $feedInf[ 'send_time' ] ) - Date::epoch();

						if ( $shareTimerSec > 60 )
						{
							$message = sprintf( esc_html__( 'Going to share after %d minute(s)', 'fs-poster' ), (int) ( $shareTimerSec / 60 ) );
						} ?>

						<div class="fsp-status fsp-is-warning fsp-tooltip" data-title="<?php echo $message; ?>">
							<i class="fas fa-clock"></i></div>
					<?php } else { ?>
						<div class="fsp-status fsp-is-danger fsp-tooltip" data-title="<?php printf( esc_html__( 'The post is failed. %s', 'fs-poster' ), esc_html( $feedInf[ 'error_msg' ] ) ); ?>">
							<i class="fas fa-times"></i></div>
					<?php } ?>
				</div>
			</div>
		<?php }
		if ( ! $feedsCount )
		{
			echo esc_html__( 'The post hasn\'t been shared on any account!', 'fs-poster' );
		} ?>
	</div>
	<div class="fsp-card-footer fsp-is-right">
		<?php if ( get_post_status( $fsp_params[ 'parameters' ][ 'post' ]->ID ) === 'publish' ) { ?>
			<button type="button" class="fsp-button fsp-is-gray" data-load-modal="share_saved_post" data-parameter-post_id="<?php echo $fsp_params[ 'parameters' ][ 'post' ]->ID ?>"><?php echo empty( $fsp_params[ 'feeds' ] ) ? esc_html__( 'SHARE' ) : esc_html__( 'SHARE AGAIN' ); ?></button>
			<button type="button" class="fsp-button fsp-is-red" data-load-modal="add_schedule" data-parameter-post_ids="<?php echo $fsp_params[ 'parameters' ][ 'post' ]->ID ?>"><?php echo esc_html__( 'SCHEDULE' ); ?></button>
		<?php } else if ( get_post_status( $fsp_params[ 'parameters' ][ 'post' ]->ID ) === 'future' ) { ?>
			<button type="button" class="fsp-button fsp-is-red" data-load-modal="edit_wp_native_schedule" data-parameter-post_id="<?php echo $fsp_params[ 'parameters' ][ 'post' ]->ID ?>"><?php echo esc_html__( 'EDIT SCHEDULE' ); ?></button>
		<?php } ?>
	</div>
</div>

<script>
	(function ($) {
		let doc = $( document );

		doc.ready( function () {
			<?php if ( isset( $fsp_params[ 'check_not_sended_feeds' ] ) && $fsp_params[ 'check_not_sended_feeds' ][ 'cc' ] > 0 ) { ?>
			FSPoster.loadModal( 'share_feeds', {
				'post_id': '<?php echo (int) $fsp_params[ 'parameters' ][ 'post' ]->ID; ?>',
				'dont_reload': '1'
			}, true );
			<?php } ?>
		} );
	})( jQuery );
</script>
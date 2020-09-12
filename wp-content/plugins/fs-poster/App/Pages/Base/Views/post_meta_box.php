<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Base', 'js/fsp-metabox.js' ); ?>"></script>
<link rel="stylesheet" href="<?php echo Pages::asset( 'Base', 'css/fsp-metabox.css' ); ?>">

<div class="fsp-metabox <?php echo $fsp_params[ 'minified' ] === TRUE ? 'fsp-is-mini' : 'fsp-card'; ?>">
	<div class="fsp-card-body">
		<div class="fsp-form-toggle-group">
			<label><?php echo esc_html__( 'Share', 'fs-poster' ); ?></label>
			<div class="fsp-toggle">
				<input type="hidden" name="share_checked" value="off">
				<input type="checkbox" name="share_checked" class="fsp-toggle-checkbox" id="fspMetaboxShare" <?php echo $fsp_params[ 'share_checkbox' ] ? 'checked' : ''; ?>>
				<label class="fsp-toggle-label" for="fspMetaboxShare"></label>
			</div>
		</div>
		<div id="fspMetaboxShareContainer">
			<div class="fsp-metabox-tabs">
				<div data-tab="all" class="fsp-metabox-tab fsp-is-active">all</div>
				<div data-tab="fb" class="fsp-metabox-tab"><i class="fab fa-facebook"></i></div>
				<div data-tab="twitter" class="fsp-metabox-tab"><i class="fab fa-twitter"></i></div>
				<div data-tab="instagram" class="fsp-metabox-tab"><i class="fab fa-instagram"></i></div>
				<div data-tab="linkedin" class="fsp-metabox-tab"><i class="fab fa-linkedin"></i></div>
				<div data-tab="vk" class="fsp-metabox-tab"><i class="fab fa-vk"></i></div>
				<div data-tab="pinterest" class="fsp-metabox-tab"><i class="fab fa-pinterest"></i></div>
				<div data-tab="reddit" class="fsp-metabox-tab"><i class="fab fa-reddit"></i></div>
				<div data-tab="tumblr" class="fsp-metabox-tab"><i class="fab fa-tumblr"></i></div>
				<div data-tab="ok" class="fsp-metabox-tab"><i class="fab fa-odnoklassniki"></i></div>
				<div data-tab="google_b" class="fsp-metabox-tab"><i class="fab fa-google"></i></div>
				<div data-tab="telegram" class="fsp-metabox-tab"><i class="fab fa-telegram"></i></div>
				<div data-tab="medium" class="fsp-metabox-tab"><i class="fab fa-medium"></i></div>
				<div data-tab="wordpress" class="fsp-metabox-tab"><i class="fab fa-wordpress"></i></div>
			</div>
			<div class="fsp-metabox-accounts">
				<div class="fsp-metabox-accounts-empty">
					<?php echo esc_html__( 'Please select an account', 'fs-poster' ); ?>
				</div>
				<?php foreach ( $fsp_params[ 'active_nodes' ] as $node_info )
				{
					$coverPhoto = Helper::profilePic( $node_info );

					if ( $node_info[ 'filter_type' ] === 'no' )
					{
						$titleText = '';
					}
					else
					{
						$titleText = ( $node_info[ 'filter_type' ] == 'in' ? 'Share only the posts of the selected categories:' : 'Do not share the posts of the selected categories:' ) . "\n";
						$titleText .= str_replace( ',', ', ', $node_info[ 'categories_name' ] );
					} ?>

					<div data-driver="<?php echo $node_info[ 'driver' ]; ?>" class="fsp-metabox-account">
						<input type="hidden" name="share_on_nodes[]" value="<?php echo $node_info[ 'driver' ] . ':' . $node_info[ 'node_type' ] . ':' . $node_info[ 'id' ] . ':' . htmlspecialchars( $node_info[ 'filter_type' ] ) . ':' . htmlspecialchars( $node_info[ 'categories' ] ); ?>">
						<div class="fsp-metabox-account-image">
							<img src="<?php echo $coverPhoto; ?>" onerror="FSPoster.no_photo( this );">
						</div>
						<div class="fsp-metabox-account-label">
							<a target="_blank" href="<?php echo Helper::profileLink( $node_info ); ?>" class="fsp-metabox-account-text">
								<?php echo esc_html( $node_info[ 'name' ] ); ?>
							</a>
							<div class="fsp-metabox-account-subtext">
								<i class="<?php echo Helper::socialIcon( $node_info[ 'driver' ] ); ?>"></i>&nbsp;<?php echo ucfirst( $node_info[ 'driver' ] ); ?>&nbsp;>&nbsp;<?php echo esc_html( $node_info[ 'node_type' ] ); ?>&nbsp;<?php echo empty( $titleText ) ? '' : '<i class="fas fa-filter" title="' . $titleText . '" ></i>'; ?>
							</div>
						</div>
						<div class="fsp-metabox-account-remove">
							<i class="fas fa-times"></i>
						</div>
					</div>
				<?php } ?>
			</div>
			<div id="fspMetaboxCustomMessages" class="fsp-metabox-custom-messages">
				<div data-driver="fb">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Facebook post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_fb"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_fb' ] ); ?></textarea>
				</div>
				<div data-driver="twitter">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Twitter post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_twitter"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_twitter' ] ); ?></textarea>
				</div>
				<div data-driver="instagram">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Instagram post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_instagram"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_instagram' ] ); ?></textarea>
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Instagram story message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_instagram_h"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_instagram_h' ] ); ?></textarea>
				</div>
				<div data-driver="linkedin">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize LinkedIn post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_linkedin"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_linkedin' ] ); ?></textarea>
				</div>
				<div data-driver="vk">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize VKontakte post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_vk"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_vk' ] ); ?></textarea>
				</div>
				<div data-driver="pinterest">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Pinterest post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_pinterest"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_pinterest' ] ); ?></textarea>
				</div>
				<div data-driver="reddit">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Reddit post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_reddit"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_reddit' ] ); ?></textarea>
				</div>
				<div data-driver="tumblr">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Tumblr post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_tumblr"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_tumblr' ] ); ?></textarea>
				</div>
				<div data-driver="ok">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Odnoklassniki post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_ok"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_ok' ] ); ?></textarea>
				</div>
				<div data-driver="google_b">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Google My Business post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_google_b"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_google_b' ] ); ?></textarea>
				</div>
				<div data-driver="telegram">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Telegram post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_telegram"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_telegram' ] ); ?></textarea>
				</div>
				<div data-driver="medium">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Medium post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_medium"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_medium' ] ); ?></textarea>
				</div>
				<div data-driver="wordpress">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize WordPress post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="3" maxlength="2000" name="fs_post_text_message_wordpress"><?php echo htmlspecialchars( $fsp_params[ 'cm_fs_post_text_message_wordpress' ] ); ?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="fsp-card-footer fsp-is-right">
		<button type="button" class="fsp-button fsp-is-gray fsp-metabox-add"><?php echo esc_html__( 'ADD' ); ?></button>
		<button type="button" class="fsp-button fsp-is-red fsp-metabox-clear"><?php echo esc_html__( 'CLEAR' ); ?></button>
	</div>
</div>

<script>
	(function ($) {
		let doc = $( document );

		doc.ready( function () {
			<?php if ( ! defined( 'NOT_CHECK_SP' ) && isset( $fsp_params[ 'check_not_sended_feeds' ] ) && $fsp_params[ 'check_not_sended_feeds' ][ 'cc' ] > 0 ) { ?>
			FSPoster.loadModal( 'share_feeds', { 'post_id': '<?php echo (int) $fsp_params[ 'post_id' ]; ?>' }, true );
			<?php } ?>

			<?php if ( (int) Helper::getOption( 'share_on_background', '1' ) === 0 && get_post_status() != 'publish' ) { ?>
			if ( $( '.block-editor__container' ).length && ! window.location.href.match( /post\.php\?post=([0-9]+)/ ) )
			{
				function checkPostIsPublished () {
					let stopTimeout = false;
					let isPostUpdated = window.location.href.match( /post\.php\?post=([0-9]+)/ );

					if ( isPostUpdated && $( '#fspMetaboxShare' ).is( ':checked' ) )
					{
						let postID = isPostUpdated[ 1 ];

						FSPoster.ajax( 'check_post_is_published', { 'id': postID }, function ( result ) {
							if ( result[ 'post_status' ] )
							{
								FSPoster.loadModal( 'share_feeds', {
									'post_id': postID,
									'dont_reload': '1'
								}, true );

								stopTimeout = true;
							}
						}, true, null, false );
					}

					if ( ! stopTimeout )
					{
						setTimeout( function () {
							checkPostIsPublished();
						}, 1500);
					}
				}

				checkPostIsPublished();
			}
			<?php } ?>
		} );
	})( jQuery );
</script>
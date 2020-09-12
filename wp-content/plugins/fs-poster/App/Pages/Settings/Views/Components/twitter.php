<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<link rel="stylesheet" href="<?php echo Pages::asset( 'Settings', 'css/fsp-instagram-preview.css' ); ?>">

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Posting type', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo __( 'Link card view – share the post with the link;<br>Upload featured image only - upload and share featured image only;<br>Upload all post images - upload and share featured image and all attached images as an album.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select name="fs_twitter_posting_type" id="fs_twitter_posting_type" class="fsp-form-select">
			<option value="1" <?php echo Helper::getOption( 'twitter_posting_type', '1' ) == '1' ? 'selected' : ''; ?>><?php echo esc_html__( 'Link card view', 'fs-poster' ); ?></option>
			<option value="2" <?php echo Helper::getOption( 'twitter_posting_type', '1' ) == '2' ? 'selected' : ''; ?>><?php echo esc_html__( 'Upload featured image only', 'fs-poster' ); ?></option>
			<option value="3" <?php echo Helper::getOption( 'twitter_posting_type', '1' ) == '3' ? 'selected' : ''; ?>><?php echo esc_html__( 'Upload all post images', 'fs-poster' ); ?></option>
		</select>
	</div>
</div>
<div class="fsp-settings-row fsp-is-collapser">
	<div class="fsp-settings-collapser">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Custom message', 'fs-poster' ); ?>
			<i class="fas fa-angle-up fsp-settings-collapse-state fsp-is-rotated"></i></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'You can customize the text of the shared post as you like by using the current keywords.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-collapse">
		<div class="fsp-settings-col">
			<div class="fsp-settings-col-title"><?php echo esc_html__( 'Text', 'fs-poster' ); ?></div>
			<div class="fsp-custom-post" data-preview="fspCustomPostPreview">
				<textarea name="fs_post_text_message_twitter" class="fsp-form-textarea"><?php echo esc_html( Helper::getOption( 'post_text_message_twitter', "{title}" ) ); ?></textarea>
				<div class="fsp-custom-post-buttons">
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{id}">
						{ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_regular_price}">
						{PRODUCT_REGULAR_PRICE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'WooCommerce - product price', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{author}">
						{AUTHOR}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post author name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_short_40}">
						{CONTENT_SHORT_40}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'The default is the first 40 characters. You can set the number whatever you want. The plugin will share that number of characters.', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{title}">
						{TITLE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post title', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{featured_image_url}">
						{FEATURED_IMAGE_URL}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Featured image URL', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{tags}">
						{TAGS}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post Tags', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{product_sale_price}">
						{PRODUCT_SALE_PRICE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'WooCommerce - product sale price', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{content_full}">
						{CONTENT_FULL}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post full content', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{short_link}">
						{SHORT_LINK}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post short link', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{excerpt}">
						{EXCERPT}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post excerpt', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{categories}">
						{CATEGORIES}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post Categories', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
						{UNIQ_ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Unique ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
						{CF_KEY}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Custom fields. Replace KEY with the custom field name.', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{link}">
						{LINK}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post link', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-red fsp-clear-button" title="<?php echo esc_html__( 'Click to clear the textbox', 'fs-poster' ); ?>">
						<?php echo esc_html__( 'CLEAR', 'fs-poster' ); ?>
					</button>
				</div>
			</div>
		</div>
		<div class="fsp-settings-col">
			<div class="fsp-settings-col-title"><?php echo esc_html__( 'Preview', 'fs-poster' ); ?></div>
			<div class="fsp-settings-preview">
				<div class="fsp-settings-preview-header">
					<img src="#" onerror="FSPoster.no_photo( this );">
					<div class="fsp-settings-preview-header-title"><?php echo get_bloginfo( 'name' ); ?></div>
					<i class="fas fa-ellipsis-h fsp-settings-preview-dots"></i>
				</div>
				<div class="fsp-settings-preview-image"></div>
				<div class="fsp-settings-preview-footer">
					<div class="fsp-settings-preview-controls">
						<i class="far fa-heart"></i> <i class="far fa-comment"></i> <i class="far fa-paper-plane"></i>
					</div>
					<div class="fsp-settings-preview-info">
						<span class="fsp-settings-preview-info-title"><?php echo get_bloginfo( 'name' ); ?></span>
						<span id="fspCustomPostPreview" class="fsp-settings-preview-info-text"><?php echo esc_html( Helper::getOption( 'post_text_message_twitter', "{title}" ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Limit the custom message characters count', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Twitter limits a Tweet length to a maximum of 280 characters.  By enabling the option, the first 280 characters of your custom message will be shared; otherwise, the tweet won\'t be shared because of the limit.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_twitter_auto_cut_tweets" class="fsp-toggle-checkbox" id="fs_twitter_auto_cut_tweets"<?php echo Helper::getOption( 'twitter_auto_cut_tweets', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_twitter_auto_cut_tweets"></label>
		</div>
	</div>
</div>
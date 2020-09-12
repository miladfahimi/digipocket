<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<link rel="stylesheet" href="<?php echo Pages::asset( 'Settings', 'css/fsp-telegram-preview.css' ); ?>">

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Select what to share on Telegram', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Define what you need to share on Telegram as a message.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select name="fs_telegram_type_of_sharing" class="fsp-form-select">
			<option value="1"<?php echo Helper::getOption( 'telegram_type_of_sharing', '1' ) == '1' ? ' selected' : ''; ?>>Custom message + Post Link</option>
			<option value="2"<?php echo Helper::getOption( 'telegram_type_of_sharing', '1' ) == '2' ? ' selected' : ''; ?>>Custom message</option>
			<option value="3"<?php echo Helper::getOption( 'telegram_type_of_sharing', '1' ) == '3' ? ' selected' : ''; ?>>Featured image + Custom message</option>
			<option value="4"<?php echo Helper::getOption( 'telegram_type_of_sharing', '1' ) == '4' ? ' selected' : ''; ?>>Featured image + Custom message + Post Link</option>
		</select>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Cut the exceeded part of the custom message', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Telegram limits the message length to a maximum of 4096 characters. By enabling the option, the first 4096 characters of your custom message will be shared; otherwise, the post won\'t be shared because of the limit.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_telegram_autocut_text" class="fsp-toggle-checkbox" id="fs_telegram_autocut_text" <?php echo Helper::getOption( 'telegram_autocut_text', '1' ) ? 'checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_telegram_autocut_text"></label>
		</div>
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
				<textarea name="fs_post_text_message_telegram" class="fsp-form-textarea"><?php echo esc_html( Helper::getOption( 'post_text_message_telegram', "{title}" ) ); ?></textarea>
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
					<i class="fas fa-ellipsis-v fsp-settings-preview-dots"></i>
				</div>
				<div class="fsp-settings-preview-body">
					<div class="fsp-settings-preview-body-msg">
						<div class="fsp-settings-preview-body-image">
							<img src="<?php echo Pages::asset( 'Settings', 'img/story.svg' ); ?>">
						</div>
						<span id="fspCustomPostPreview" class="fsp-settings-preview-body-text"><?php echo esc_html( Helper::getOption( 'post_text_message_telegram', "{title}" ) ); ?></span>
					</div>
				</div>
				<div class="fsp-settings-preview-footer">
					<div class="fsp-settings-preview-footer-icon">
						<i class="fas fa-paperclip"></i>
					</div>
					<div class="fsp-settings-preview-footer-msg">
						Message...
					</div>
					<div class="fsp-settings-preview-footer-icon">
						<i class="far fa-smile"></i>
					</div>
					<div class="fsp-settings-preview-footer-icon">
						<i class="far fa-paper-plane"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
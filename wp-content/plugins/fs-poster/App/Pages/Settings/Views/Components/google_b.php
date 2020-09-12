<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<link rel="stylesheet" href="<?php echo Pages::asset( 'Settings', 'css/fsp-instagram-preview.css' ); ?>">

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Share WooCommerce products as a product', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Disable to share WooCommerce products as a post.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_google_b_share_as_product" class="fsp-toggle-checkbox" id="fs_google_b_share_as_product"<?php echo Helper::getOption( 'google_b_share_as_product', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_google_b_share_as_product"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Add a button', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Select a post link button.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select name="fs_google_b_button_type" class="fsp-form-select">
			<option value="BOOK"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == '1' ? ' selected' : ''; ?>>Book</option>
			<option value="ORDER"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'ORDER' ? ' selected' : ''; ?>>Order online</option>
			<option value="SHOP"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'SHOP' ? ' selected' : ''; ?>>Buy</option>
			<option value="LEARN_MORE"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'LEARN_MORE' ? ' selected' : ''; ?>>Learn more</option>
			<option value="SIGN_UP"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'SIGN_UP' ? ' selected' : ''; ?>>Sign up</option>
			<option value="WATCH_VIDEO"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'WATCH_VIDEO' ? ' selected' : ''; ?>>Watch Video</option>
			<option value="RESERVE"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'RESERVE' ? ' selected' : ''; ?>>Reserve</option>
			<option value="GET_OFFER"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'GET_OFFER' ? ' selected' : ''; ?>>Get offer</option>
			<option value="CALL"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == 'CALL' ? ' selected' : ''; ?>>Call now</option>
			<option value="-"<?php echo Helper::getOption( 'google_b_button_type', 'LEARN_MORE' ) == '-' ? ' selected' : ''; ?>>Don't share link</option>
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
				<textarea name="fs_post_text_message_google_b" class="fsp-form-textarea"><?php echo esc_html( Helper::getOption( 'post_text_message_google_b', "{title}" ) ); ?></textarea>
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
						<span id="fspCustomPostPreview" class="fsp-settings-preview-info-text"><?php echo esc_html( Helper::getOption( 'post_text_message_google_b', "{title}" ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
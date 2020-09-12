<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Unique post link', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Sharing the same post on numerous communities might be accepted as a duplicated post by social networks; consequently, the post might be blocked. By enabling the option, the ending of each link gets random URL characters to become unique.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_unique_link" class="fsp-toggle-checkbox" id="fs_unique_link" <?php echo Helper::getOption( 'unique_link', '1' ) ? 'checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_unique_link"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'URL shortener', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Enable to shorten your post URLs automatically.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_url_shortener" class="fsp-toggle-checkbox" id="fspURLShortener" <?php echo Helper::getOption( 'url_shortener', '0' ) ? 'checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fspURLShortener"></label>
		</div>
	</div>
</div>
<div id="fspShortenerRow">
	<div class="fsp-settings-row">
		<div class="fsp-settings-col">
			<div class="fsp-settings-label-text"><?php echo esc_html__( 'URL shortener service', 'fs-poster' ); ?></div>
			<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Select a URL shortener service to shorten your post URLs.', 'fs-poster' ); ?></div>
		</div>
		<div class="fsp-settings-col">
			<select id="fspShortenerSelector" name="fs_shortener_service" class="fsp-form-select">
				<option value="tinyurl" <?php echo Helper::getOption( 'shortener_service' ) == 'tinyurl' ? 'selected' : '' ?>>TinyURL</option>
				<option value="bitly" <?php echo Helper::getOption( 'shortener_service' ) == 'bitly' ? 'selected' : '' ?>>Bitly</option>
			</select>
		</div>
	</div>
	<div id="fspBitly" class="fsp-settings-row">
		<div class="fsp-settings-col">
			<div class="fsp-settings-label-text"><?php echo esc_html__( 'Bitly access token', 'fs-poster' ); ?></div>
			<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Register for the Bitly service to get a new access token!', 'fs-poster' ); ?></div>
		</div>
		<div class="fsp-settings-col">
			<input type="text" autocomplete="off" name="fs_url_short_access_token_bitly" class="fsp-form-input" value="<?php echo esc_html( Helper::getOption( 'url_short_access_token_bitly', '100' ) ); ?>">
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Share a custom URL instead of the WordPress post URL', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Enable to define and type your custom post URL using specific keywords.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_share_custom_url" class="fsp-toggle-checkbox" id="fspCustomURL" <?php echo Helper::getOption( 'share_custom_url', '0' ) ? 'checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fspCustomURL"></label>
		</div>
	</div>
</div>
<div id="fspCustomURLRow_1" class="fsp-settings-row fsp-is-collapser">
	<div class="fsp-settings-collapser">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Additional URL parameters', 'fs-poster' ); ?>
			<i class="fas fa-angle-up fsp-settings-collapse-state fsp-is-rotated"></i></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'You can customize the URL as you like by using the current keywords.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-collapse">
		<div class="fsp-settings-col">
			<div class="fsp-custom-post">
				<input autocomplete="off" name="fs_url_additional" class="fsp-form-input" value="<?php echo esc_html( Helper::getOption( 'url_additional', '' ) ); ?>">
				<div class="fsp-custom-post-buttons">
					<button type="button" class="fsp-button fsp-is-red" id="fspUseGA">
						<?php echo esc_html__( 'Use Google Analytics template', 'fs-poster' ); ?>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{post_id}">
						{POST_ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{network_name}">
						{NETWORK_NAME}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Social network name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{network_code}">
						{NETWORK_CODE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Social network code', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{account_name}">
						{ACCOUNT_NAME}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Account name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{post_title}">
						{POST_TITLE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post title', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{site_name}">
						{SITE_NAME}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Site name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
						{UNIQ_ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Unique ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-red fsp-clear-button" title="<?php echo esc_html__( 'Click to clear the textbox', 'fs-poster' ); ?>">
						<?php echo esc_html__( 'CLEAR', 'fs-poster' ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="fspCustomURLRow_2" class="fsp-settings-row fsp-is-collapser">
	<div class="fsp-settings-collapser">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Custom URL', 'fs-poster' ); ?>
			<i class="fas fa-angle-up fsp-settings-collapse-state fsp-is-rotated"></i>
		</div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'You can customize the URL as you like by using the current keywords. If you want to share the post URL as it is, don\'t enable the option.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-collapse">
		<div class="fsp-settings-col">
			<div class="fsp-custom-post">
				<input autocomplete="off" name="fs_custom_url_to_share" class="fsp-form-input" value="<?php echo esc_html( Helper::getOption( 'custom_url_to_share', '{site_url}/?p={post_id}&feed_id={feed_id}' ) ); ?>">
				<div class="fsp-custom-post-buttons">
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{post_id}">
						{POST_ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{feed_id}">
						{FEED_ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Feed ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{network_name}">
						{NETWORK_NAME}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Social network name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{network_code}">
						{NETWORK_CODE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Social network code', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{account_name}">
						{ACCOUNT_NAME}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Account name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{post_title}">
						{POST_TITLE}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post title', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{site_name}">
						{SITE_NAME}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Site name', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="site_url">
						{SITE_URL}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Site URL', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="site_url_encoded">
						{SITE_URL_ENCODED}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Site URL encoded', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="post_url">
						{POST_URL}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post URL', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="post_url_encoded">
						{POST_URL_ENCODED}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Post URL encoded', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{uniq_id}">
						{UNIQ_ID}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Unique ID', 'fs-poster' ); ?>"></i>
					</button>
					<button type="button" class="fsp-button fsp-is-gray fsp-append-to-text" data-key="{cf_KEY}">
						{CF_KEY}
						<i class="fas fa-info-circle fsp-tooltip" data-title="<?php echo esc_html__( 'Custom fields. Replace KEY with the custom field name.', 'fs-poster' ); ?>"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Share posts automatically', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'When you publish a new post, the plugin shares the post on all active social accounts automatically.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_auto_share_new_posts" class="fsp-toggle-checkbox" id="fs_auto_share_new_posts"<?php echo Helper::getOption( 'auto_share_new_posts', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_auto_share_new_posts"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Share in the background', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Share posts in the background to continue your work on the website.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_share_on_background" class="fsp-toggle-checkbox" id="fs_share_on_background"<?php echo Helper::getOption( 'share_on_background', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_share_on_background"></label>
		</div>
	</div>
</div>
<div id="fspSharingTimerRow" class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'When to share the post after it is published', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Define some time with minutes to share the post after it is published. The default is 0 to share the post immediately.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<input type="number" step="1" min="0" name="fs_share_timer" class="fsp-form-input" value="<?php echo esc_html( Helper::getOption( 'share_timer', '0' ) ); ?>">
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Keep the shared post log', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'If you don\'t want to keep the shared post logs, you need to disable the option. Disabling the option prevents you view your insights.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_keep_logs" class="fsp-toggle-checkbox" id="fs_keep_logs"<?php echo Helper::getOption( 'keep_logs', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_keep_logs"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Post interval', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Define an interval between shares for each social account. Please note that this interval is between accounts for a post. The interval is not for between posts.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select name="fs_post_interval" id="fspInterval" class="fsp-form-select">
			<option value="0"<?php echo Helper::getOption( 'post_interval', '0' ) == '0' ? ' selected' : ''; ?>>- Immediately</option>
			<option value="5"<?php echo Helper::getOption( 'post_interval', '0' ) == '5' ? ' selected' : ''; ?>>5 seconds</option>
			<option value="10"<?php echo Helper::getOption( 'post_interval', '0' ) == '10' ? ' selected' : ''; ?>>10 seconds</option>
			<option value="20"<?php echo Helper::getOption( 'post_interval', '0' ) == '20' ? ' selected' : ''; ?>>20 seconds</option>
			<option value="30"<?php echo Helper::getOption( 'post_interval', '0' ) == '30' ? ' selected' : ''; ?>>30 seconds</option>
			<option value="45"<?php echo Helper::getOption( 'post_interval', '0' ) == '45' ? ' selected' : ''; ?>>45 seconds</option>
			<option value="60"<?php echo Helper::getOption( 'post_interval', '0' ) == '60' ? ' selected' : ''; ?>>1 minute(s)</option>
			<option value="120"<?php echo Helper::getOption( 'post_interval', '0' ) == '120' ? ' selected' : ''; ?>>2 minute(s)</option>
			<option value="180"<?php echo Helper::getOption( 'post_interval', '0' ) == '180' ? ' selected' : ''; ?>>3 minute(s)</option>
			<option value="240"<?php echo Helper::getOption( 'post_interval', '0' ) == '240' ? ' selected' : ''; ?>>4 minute(s)</option>
			<option value="300"<?php echo Helper::getOption( 'post_interval', '0' ) == '300' ? ' selected' : ''; ?>>5 minute(s)</option>
			<option value="600"<?php echo Helper::getOption( 'post_interval', '0' ) == '600' ? ' selected' : ''; ?>>10 minute(s)</option>
			<option value="900"<?php echo Helper::getOption( 'post_interval', '0' ) == '900' ? ' selected' : ''; ?>>15 minute(s)</option>
			<option value="1200"<?php echo Helper::getOption( 'post_interval', '0' ) == '1200' ? ' selected' : ''; ?>>20 minute(s)</option>
			<option value="1500"<?php echo Helper::getOption( 'post_interval', '0' ) == '1500' ? ' selected' : ''; ?>>25 minute(s)</option>
			<option value="1800"<?php echo Helper::getOption( 'post_interval', '0' ) == '1800' ? ' selected' : ''; ?>>30 minute(s)</option>
			<option value="2400"<?php echo Helper::getOption( 'post_interval', '0' ) == '2400' ? ' selected' : ''; ?>>40 minute(s)</option>
			<option value="3000"<?php echo Helper::getOption( 'post_interval', '0' ) == '3000' ? ' selected' : ''; ?>>50 minute(s)</option>
			<option value="3600"<?php echo Helper::getOption( 'post_interval', '0' ) == '3600' ? ' selected' : ''; ?>>1 hour(s)</option>
			<option value="7200"<?php echo Helper::getOption( 'post_interval', '0' ) == '7200' ? ' selected' : ''; ?>>2 hour(s)</option>
			<option value="10800"<?php echo Helper::getOption( 'post_interval', '0' ) == '10800' ? ' selected' : ''; ?>>3 hour(s)</option>
			<option value="18000"<?php echo Helper::getOption( 'post_interval', '0' ) == '18000' ? ' selected' : ''; ?>>5 hour(s)</option>
		</select>
	</div>
</div>
<div id="fspIntervalLimit" class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Use the Post interval option for only the same social networks', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'It prevents sharing a post on the same social network accounts at once to avoid spamming. ', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_post_interval_type" class="fsp-toggle-checkbox" id="fs_post_interval_type"<?php echo Helper::getOption( 'post_interval_type', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_post_interval_type"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Replace the white spaces in the tags with an underscore', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Enable the option to replace the whitespaces with underscores in keyword of {tags} and {categories} ( e.g., "Sport News" will be transformed to "#sport_news" ). Disable the option to remove the whitespaces in keyword of {tags} and {categories} ( e.g., "Sport News" will be transformed to "#sportnews" ).', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_replace_whitespaces_with_underscore" class="fsp-toggle-checkbox" id="fs_replace_whitespaces_with_underscore"<?php echo Helper::getOption( 'replace_whitespaces_with_underscore', '0' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_replace_whitespaces_with_underscore"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'WordPress shortcodes', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Configure how you want to share WordPress shortcodes.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select name="fs_replace_wp_shortcodes" class="fsp-form-select">
			<option value="off" <?php echo Helper::getOption( 'replace_wp_shortcodes', 'off' ) == 'off' ? 'selected' : ''; ?>><?php echo esc_html__( 'Keep shortcodes as it is', 'fs-poster' ); ?></option>
			<option value="on" <?php echo Helper::getOption( 'replace_wp_shortcodes', 'off' ) == 'on' ? 'selected' : ''; ?>><?php echo esc_html__( 'Replace shortcodes to their values', 'fs-poster' ); ?></option>
			<option value="del" <?php echo Helper::getOption( 'replace_wp_shortcodes', 'off' ) == 'del' ? 'selected' : ''; ?>><?php echo esc_html__( 'Remove shortcodes from the post', 'fs-poster' ); ?></option>
		</select>
	</div>
</div>
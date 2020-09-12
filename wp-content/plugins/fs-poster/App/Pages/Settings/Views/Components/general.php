<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Show FS Poster column on the posts table', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo __( 'If you don\'t want to show FS Poster <i class="far fa-question-circle fsp-tooltip"  data-title="Click to learn more" data-open-img="' . Pages::asset( 'Base', 'img/fs_poster_column_help.png' ) . '"></i> column on posts table, you can disable this option.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_show_fs_poster_column" class="fsp-toggle-checkbox" id="fs_show_fs_poster_column"<?php echo Helper::getOption( 'show_fs_poster_column', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_show_fs_poster_column"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Check accounts', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Enable the option to check the status of all active accounts daily.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_check_accounts" class="fsp-toggle-checkbox" id="fspCheckAccounts" <?php echo Helper::getOption( 'check_accounts', 1 ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fspCheckAccounts"></label>
		</div>
	</div>
</div>
<div id="fspDisableAccountsRow" class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Disable failed accounts', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Enable the option to deactivate failed accounts. If you have enabled the option to check the status of your accounts and if the connection to the account is unable, the plugin will not share posts on those failed accounts.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_check_accounts_disable" class="fsp-toggle-checkbox" id="fs_check_accounts_disable"<?php echo Helper::getOption( 'check_accounts_disable', 0 ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_check_accounts_disable"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Custom post types', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Add post types that you want to share.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select class="fsp-form-input select2-init" id="fs_allowed_post_types" name="fs_allowed_post_types[]" multiple>
			<?php
			$selecttedTypes = explode( '|', Helper::getOption( 'allowed_post_types', 'post|page|attachment|product' ) );
			foreach ( get_post_types() as $post_type )
			{
				echo '<option value="' . htmlspecialchars( $post_type ) . '"' . ( in_array( $post_type, $selecttedTypes ) ? ' selected' : '' ) . '>' . htmlspecialchars( $post_type ) . '</option>';
			}
			?>
		</select>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Hide FS Poster for', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'Select the user roles to hide the FS Poster plugin for some users.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<select class="fsp-form-input select2-init" id="fs_hide_for_roles" name="fs_hide_for_roles[]" multiple>
			<?php
			$hideForRoles = explode( '|', Helper::getOption( 'hide_menu_for', '' ) );
			$wp_roles     = get_editable_roles();
			foreach ( $wp_roles as $roleId => $roleInf )
			{
				if ( $roleId === 'administrator' )
				{
					continue;
				}

				echo '<option value="' . htmlspecialchars( $roleId ) . '"' . ( in_array( $roleId, $hideForRoles ) ? ' selected' : '' ) . '>' . htmlspecialchars( $roleInf[ 'name' ] ) . '</option>';
			}
			?>
		</select>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Collect FS Poster statistics', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo esc_html__( 'The plugin appends a  "feed_id" parameter to a post link to get statistics. Disabling the option prevents you from getting statistics in the Insights menu. And because the plugin does not collect statistics,  you might also have duplicate posts on Social Networks even if you select the “Randomly (without duplicates)” option when you use the schedule module.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<div class="fsp-toggle">
			<input type="checkbox" name="fs_collect_statistics" class="fsp-toggle-checkbox" id="fs_collect_statistics"<?php echo Helper::getOption( 'collect_statistics', '1' ) ? ' checked' : ''; ?>>
			<label class="fsp-toggle-label" for="fs_collect_statistics"></label>
		</div>
	</div>
</div>
<div class="fsp-settings-row">
	<div class="fsp-settings-col">
		<div class="fsp-settings-label-text"><?php echo esc_html__( 'Cron Job settings', 'fs-poster' ); ?></div>
		<div class="fsp-settings-label-subtext"><?php echo __( 'You can follow this <a href="https://www.fs-poster.com/documentation/how-to-set-up-a-cron-job-on-wordpress-fs-poster-wp-plugin" target="_blank">documentation</a> and configure your WordPress Cron Jobs.', 'fs-poster' ); ?></div>
	</div>
	<div class="fsp-settings-col">
		<?php
		$lastCronTaskRunnedOn = Helper::getOption( 'cron_job_runned_on', 0 );

		if ( empty( $lastCronTaskRunnedOn ) )
		{
			echo '<span>'. __( 'Not runned! Your schedule posts and background share may be not work! Please follow this <a href="https://www.fs-poster.com/documentation/how-to-set-up-a-cron-job-on-wordpress-fs-poster-wp-plugin" target="_blank">documentation</a> and configure your WordPress Cron Jobs.', 'fs-poster' ) .'</span>';
		}
		else
		{
			$calcTime = Date::epoch() - $lastCronTaskRunnedOn;
			$calcTime = (int) ( $calcTime / 60 );
			$calcTime = $calcTime > 0 ? $calcTime : 1;
			echo Date::dateTime( $lastCronTaskRunnedOn ) . ' ( ' . $calcTime . ' minute(s) ago )';

			if ( $calcTime > 3 )
			{
				echo '<div>'. esc_html__( 'It looks like your Cron job runs with delay. Therefore your Scheduled posts and background shares may post with delay!', 'fs-poster' ) .'</div>';
			}
		}
		?>
	</div>
</div>
<div class="fsp-settings-row fsp-row">
	<div class="fsp-note fsp-col-12">
		<?php echo esc_html__( 'The Cron Job command for your website is:' ); ?>
		<div class="fsp-note-text">wget -O /dev/null <?php echo site_url(); ?>/wp-cron.php?doing_wp_cron > /dev/null 2>&1</div>
	</div>
</div>
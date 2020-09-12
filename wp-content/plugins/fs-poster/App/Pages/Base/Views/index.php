<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-container">
	<div class="fsp-header">
		<div class="fsp-nav">
			<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Dashboard' ? 'active' : '' ); ?>" href="?page=fs-poster"><?php echo esc_html__( 'Dashboard', 'fs-poster' ); ?></a>
			<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Accounts' ? 'active' : '' ); ?>" href="?page=fs-poster-accounts"><?php echo esc_html__( 'Accounts', 'fs-poster' ); ?></a>
			<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Schedules' ? 'active' : '' ); ?>" href="?page=fs-poster-schedules"><?php echo esc_html__( 'Schedules', 'fs-poster' ); ?></a>
			<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Share' ? 'active' : '' ); ?>" href="?page=fs-poster-share"><?php echo esc_html__( 'Direct Share', 'fs-poster' ); ?></a>
			<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Logs' ? 'active' : '' ); ?>" href="?page=fs-poster-logs"><?php echo esc_html__( 'Logs', 'fs-poster' ); ?></a>
			<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Apps' ? 'active' : '' ); ?>" href="?page=fs-poster-apps"><?php echo esc_html__( 'Apps', 'fs-poster' ); ?></a>
			<?php if ( current_user_can( 'administrator' ) ) { ?>
				<a class="fsp-nav-link <?php echo( $fsp_params[ 'page_name' ] === 'Settings' ? 'active' : '' ); ?>" href="?page=fs-poster-settings"><?php echo esc_html__( 'Settings', 'fs-poster' ); ?></a>
			<?php } ?>
		</div>
	</div>
	<div class="fsp-body">
		<?php Pages::controller( $fsp_params[ 'page_name' ], 'Main', 'index' ); ?>
	</div>
</div>
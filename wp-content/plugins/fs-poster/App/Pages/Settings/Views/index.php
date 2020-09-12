<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Pages;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Settings', 'fs-poster' ); ?>
		</div>
		<div class="fsp-title-button">
			<button id="fspSaveSettings" class="fsp-button">
				<i class="fas fa-check"></i> <span><?php echo esc_html__( 'SAVE CHANGES', 'fs-poster' ); ?></span>
			</button>
		</div>
	</div>
	<div class="fsp-col-12 fsp-row">
		<div class="fsp-layout-left fsp-col-12 fsp-col-md-4 fsp-col-lg-3">
			<div class="fsp-card">
				<a href="?page=fs-poster-settings&setting=general" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'general' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fas fa-sliders-h fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'General settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=share" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'share' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fas fa-share-alt fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Publish settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=url" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'url' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fas fa-link fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'URL settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=export_import" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'export_import' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fas fa-file-export fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Export & Import settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=facebook" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'facebook' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-facebook fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Facebook settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=instagram" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'instagram' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-instagram fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Instagram settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=twitter" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'twitter' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-twitter fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Twitter settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=linkedin" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'linkedin' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-linkedin fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'LinkedIn settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=vk" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'vk' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-vk fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'VKontakte settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=pinterest" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'pinterest' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-pinterest fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Pinterest settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=tumblr" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'tumblr' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-tumblr fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Tumblr settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=reddit" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'reddit' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-reddit fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Reddit settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=ok" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'ok' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-odnoklassniki fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Odnoklassniki settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=google_b" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'google_b' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-google fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Google My Business settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=telegram" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'telegram' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-telegram fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Telegram settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=medium" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'medium' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-medium fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'Medium settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
				<a href="?page=fs-poster-settings&setting=wordpress" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'wordpress' ? 'fsp-is-active' : '' ); ?>">
					<div class="fsp-tab-title">
						<i class="fab fa-wordpress fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'WordPress website settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
			</div>
		</div>
		<div id="fspComponent" class="fsp-layout-right fsp-col-12 fsp-col-md-8 fsp-col-lg-9">
			<form id="fspSettingsForm" class="fsp-card fsp-settings">
				<?php Pages::controller( 'Settings', 'Main', 'component_' . $fsp_params[ 'active_tab' ] ); ?>
			</form>
		</div>
	</div>
</div>
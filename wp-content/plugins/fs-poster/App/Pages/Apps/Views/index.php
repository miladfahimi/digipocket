<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Apps', 'fs-poster' ); ?>
			<span id="fspAppsCount" class="fsp-title-count"><?php echo $fsp_params[ 'appCounts' ][ 'total' ]; ?></span>
		</div>
		<div class="fsp-title-button">
			<?php if ( ! is_null( $fsp_params[ 'active_tab' ] ) ) { ?>
				<button class="fsp-button" data-load-modal="add_app" data-parameter-fields="<?php echo implode( ',', $fsp_params[ 'appCounts' ][ $fsp_params[ 'active_tab' ] ][ 1 ] ); ?>" data-parameter-driver="<?php echo $fsp_params[ 'active_tab' ]; ?>">
					<i class="fas fa-plus"></i>
					<span><?php echo esc_html__( 'ADD AN APP', 'fs-poster' ); ?></span>
				</button>
			<?php } ?>
		</div>
	</div>
	<div class="fsp-col-12 fsp-row">
		<div class="fsp-layout-left fsp-col-12 fsp-col-md-5 fsp-col-lg-4">
			<div class="fsp-card">
				<a href="?page=fs-poster-apps&tab=fb" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'fb' ? 'fsp-is-active' : '' ); ?>" data-component="fb">
					<div class="fsp-tab-title">
						<i class="fab fa-facebook fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Facebook</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'fb' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=twitter" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'twitter' ? 'fsp-is-active' : '' ); ?>" data-component="twitter">
					<div class="fsp-tab-title">
						<i class="fab fa-twitter fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Twitter</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'twitter' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=linkedin" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'linkedin' ? 'fsp-is-active' : '' ); ?>" data-component="linkedin">
					<div class="fsp-tab-title">
						<i class="fab fa-linkedin fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">LinkedIn</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'linkedin' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=vk" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'vk' ? 'fsp-is-active' : '' ); ?>" data-component="vk">
					<div class="fsp-tab-title">
						<i class="fab fa-vk fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">VK</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'vk' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=pinterest" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'pinterest' ? 'fsp-is-active' : '' ); ?>" data-component="pinterest">
					<div class="fsp-tab-title">
						<i class="fab fa-pinterest fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Pinterest</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'pinterest' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=reddit" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'reddit' ? 'fsp-is-active' : '' ); ?>" data-component="reddit">
					<div class="fsp-tab-title">
						<i class="fab fa-reddit fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">Reddit</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'reddit' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=tumblr" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'tumblr' ? 'fsp-is-active' : '' ); ?>" data-component="tumblr">
					<div class="fsp-tab-title">
						<i class="fab fa-tumblr fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">Tumblr</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'tumblr' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=ok" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'ok' ? 'fsp-is-active' : '' ); ?>" data-component="ok">
					<div class="fsp-tab-title">
						<i class="fab fa-odnoklassniki fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Odnoklassniki</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'ok' ][ 0 ]; ?></span>
					</div>
				</a>
				<a href="?page=fs-poster-apps&tab=medium" class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'medium' ? 'fsp-is-active' : '' ); ?>" data-component="medium">
					<div class="fsp-tab-title">
						<i class="fab fa-medium fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">Medium</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'appCounts' ][ 'medium' ][ 0 ]; ?></span>
					</div>
				</a>
			</div>
		</div>
		<div id="fspComponent" class="fsp-layout-right fsp-col-12 fsp-col-md-7 fsp-col-lg-8">
			<?php if ( ! is_null( $fsp_params[ 'active_tab' ] ) ) { ?>
				<div class="fsp-note">
					<?php echo esc_html__( 'The callback URL for your App is:' ); ?>
					<div class="fsp-note-text"><?php echo $fsp_params[ 'callbackUrl' ]; ?></div>
				</div>
				<?php foreach ( $fsp_params[ 'appList' ] as $app ) { ?>
					<div class="fsp-app" data-id="<?php echo $app[ 'id' ]; ?>">
						<div class="fsp-app-image">
							<img src="<?php echo Helper::appIcon( $app ); ?>" onerror="FSPoster.no_photo( this );">
						</div>
						<div class="fsp-app-title">
							<?php echo esc_html( $app[ 'name' ] ); ?>
						</div>
						<div class="fsp-app-info">
							<?php foreach ( $fsp_params[ 'appCounts' ][ $fsp_params[ 'active_tab' ] ][ 1 ] as $crdntls ) { ?>
								<?php if ( $crdntls === 'app_key' )
								{
									$label = esc_html__( 'The App Key:', 'fs-poster' );
								}
								else if ( $crdntls === 'app_secret' )
								{
									$label = esc_html__( 'The App Secret:', 'fs-poster' );
								}
								else
								{
									$label = esc_html__( 'The App ID:', 'fs-poster' );
								} ?>
								<div class="fsp-app-info-key"><?php echo $label; ?></div>
								<div class="fsp-app-info-value fsp-tooltip" data-title="<?php echo esc_html( $app[ $crdntls ] ); ?>"><?php echo strlen( esc_html( $app[ $crdntls ] ) ) > 10 ? substr( esc_html( $app[ $crdntls ] ), 0, 10 ) . ' ... ' . substr( esc_html( $app[ $crdntls ] ), -3, 3 ) : esc_html( $app[ $crdntls ] ); ?></div>
							<?php } ?>
						</div>
						<div class="fsp-app-controls">
							<?php if ( $app[ 'is_standart' ] > 0 ) { ?>
								<i class="fas fa-exclamation-circle fsp-tooltip fsp-icon-button" data-title="<?php echo esc_html__( "You can't delete the App", 'fs-poster' ); ?>"></i>
							<?php } else { ?>
								<i class="fas fa-trash fsp-tooltip fsp-icon-button fsp-delete-app" data-id="<?php echo $app[ 'id' ]; ?>" data-title="<?php echo esc_html__( 'Delete', 'fs-poster' ); ?>"></i>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<div id="fspNoAppFound" class="fsp-card fsp-emptiness <?php echo ! empty( $fsp_params[ 'appList' ] ) ? 'fsp-hide' : ''; ?>">
					<div class="fsp-emptiness-image">
						<img src="<?php echo Pages::asset( 'Base', 'img/empty.svg' ); ?>">
					</div>
					<div class="fsp-emptiness-text">
						<?php echo esc_html__( 'There isn\'t an App.', 'fs-poster' ); ?>
					</div>
				</div>
			<?php } else { ?>
				<div class="fsp-card fsp-emptiness">
					<div class="fsp-emptiness-image">
						<img src="<?php echo Pages::asset( 'Base', 'img/empty.svg' ); ?>">
					</div>
					<div class="fsp-emptiness-text">
						<?php echo esc_html__( 'Please choose a social network to continue', 'fs-poster' ); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
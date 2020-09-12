<?php

namespace FSPoster\App\Pages\Accounts\Views;

defined( 'ABSPATH' ) or exit;
?>

<div id="fspActivateMenu" class="fsp-dropdown">
	<div id="fspActivate" class="fsp-dropdown-item"><?php echo esc_html__( 'Activate', 'fs-poster' ); ?></div>
	<div id="fspActivateConditionally" class="fsp-dropdown-item"><?php echo esc_html__( 'Activate (condition)', 'fs-poster' ); ?></div>
	<div id="fspDeactivate" class="fsp-dropdown-item"><?php echo esc_html__( 'Deactivate', 'fs-poster' ); ?></div>
</div>

<div id="fspMoreMenu" class="fsp-dropdown">
	<div class="fsp-dropdown-item fsp-make-public" data-type="public"><?php echo esc_html__( 'Make public', 'fs-poster' ); ?></div>
	<div class="fsp-dropdown-item fsp-make-public" data-type="private"><?php echo esc_html__( 'Make private', 'fs-poster' ); ?></div>
	<div id="fspDelete" class="fsp-dropdown-item"><?php echo esc_html__( 'Delete', 'fs-poster' ); ?></div>
</div>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Accounts', 'fs-poster' ); ?><span class="fsp-title-count"><?php echo $fsp_params[ 'accounts_count' ][ 'total' ]; ?></span>
		</div>
		<div class="fsp-title-button">
			<button class="fsp-button fsp-accounts-add-button">
				<i class="fas fa-plus"></i>
				<span><?php echo $fsp_params[ 'button_text' ]; ?></span>
			</button>
		</div>
	</div>
	<div class="fsp-col-12 fsp-row">
		<div class="fsp-layout-left fsp-col-12 fsp-col-md-5 fsp-col-lg-4">
			<div class="fsp-card">
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'fb' ? 'fsp-is-active' : '' ); ?>" data-component="fb">
					<div class="fsp-tab-title">
						<i class="fab fa-facebook fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Facebook</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'fb' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'fb' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'fb' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'fb' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'twitter' ? 'fsp-is-active' : '' ); ?>" data-component="twitter">
					<div class="fsp-tab-title">
						<i class="fab fa-twitter fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Twitter</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'twitter' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'twitter' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'twitter' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'twitter' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'instagram' ? 'fsp-is-active' : '' ); ?>" data-component="instagram">
					<div class="fsp-tab-title">
						<i class="fab fa-instagram fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Instagram</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'instagram' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'instagram' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'instagram' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'instagram' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'linkedin' ? 'fsp-is-active' : '' ); ?>" data-component="linkedin">
					<div class="fsp-tab-title">
						<i class="fab fa-linkedin fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">LinkedIn</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'linkedin' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'linkedin' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'linkedin' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'linkedin' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'vk' ? 'fsp-is-active' : '' ); ?>" data-component="vk">
					<div class="fsp-tab-title">
						<i class="fab fa-vk fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">VK</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'vk' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'vk' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'vk' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'vk' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'pinterest' ? 'fsp-is-active' : '' ); ?>" data-component="pinterest">
					<div class="fsp-tab-title">
						<i class="fab fa-pinterest fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Pinterest</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'pinterest' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'pinterest' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'pinterest' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'pinterest' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'reddit' ? 'fsp-is-active' : '' ); ?>" data-component="reddit">
					<div class="fsp-tab-title">
						<i class="fab fa-reddit fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">Reddit</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'reddit' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'reddit' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'reddit' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'reddit' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'tumblr' ? 'fsp-is-active' : '' ); ?>" data-component="tumblr">
					<div class="fsp-tab-title">
						<i class="fab fa-tumblr fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">Tumblr</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'tumblr' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'tumblr' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'tumblr' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'tumblr' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'ok' ? 'fsp-is-active' : '' ); ?>" data-component="ok">
					<div class="fsp-tab-title">
						<i class="fab fa-odnoklassniki fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Odnoklassniki</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'ok' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'ok' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'ok' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'ok' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'google_b' ? 'fsp-is-active' : '' ); ?>" data-component="google_b">
					<div class="fsp-tab-title">
						<i class="fab fa-google fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Google My Business</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'google_b' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'google_b' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'google_b' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'google_b' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'telegram' ? 'fsp-is-active' : '' ); ?>" data-component="telegram">
					<div class="fsp-tab-title">
						<i class="fab fa-telegram fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Telegram</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'telegram' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'telegram' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'telegram' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'telegram' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'medium' ? 'fsp-is-active' : '' ); ?>" data-component="medium">
					<div class="fsp-tab-title">
						<i class="fab fa-medium fsp-tab-title-icon"></i> <span class="fsp-tab-title-text">Medium</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'medium' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'medium' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'medium' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'medium' ][ 'total' ]; ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'wordpress' ? 'fsp-is-active' : '' ); ?>" data-component="wordpress">
					<div class="fsp-tab-title">
						<i class="fab fa-wordpress fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Wordpress</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'wordpress' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<?php echo( $fsp_params[ 'accounts_count' ][ 'wordpress' ][ 'failed' ] > 0 ? '<span class="fsp-tab-failed">' . $fsp_params[ 'accounts_count' ][ 'wordpress' ][ 'failed' ] . '</span>' : '' ); ?>
						<span class="fsp-tab-all"><?php echo $fsp_params[ 'accounts_count' ][ 'wordpress' ][ 'total' ]; ?></span>
					</div>
				</div>
			</div>
		</div>
		<div id="fspComponent" class="fsp-layout-right fsp-col-12 fsp-col-md-7 fsp-col-lg-8"></div>
	</div>
</div>
<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div>
	<span id="fspAccountsCount" class="fsp-hide"><?php echo count( $fsp_params[ 'accounts_list' ] ); ?></span>

	<?php foreach ( $fsp_params[ 'accounts_list' ] as $account_info ) { ?>
		<div class="fsp-account">
			<div class="fsp-card fsp-account-item" data-id="<?php echo $account_info[ 'id' ]; ?>" data-type="account">
				<div class="fsp-account-inline">
					<div class="fsp-account-image">
						<img src="<?php echo Helper::profilePic( $account_info ); ?>" onerror="FSPoster.no_photo( this );">
					</div>
					<div class="fsp-account-name">
						<?php echo esc_html( $account_info[ 'username' ] ); ?>
					</div>
					<div class="fsp-account-is-public fsp-tooltip <?php echo ! $account_info[ 'is_public' ] ? 'fsp-hide' : ''; ?>" data-title="<?php echo esc_html__( 'It is public for all WordPress users', 'fs-poster' ); ?>">
						<i class="fas fa-star"></i>
					</div>
					<?php if ( ! empty( $account_info[ 'proxy' ] ) ) { ?>
						<div class="fsp-account-divider"></div>
						<div class="fsp-account-proxy fsp-tooltip" data-title="<?php echo esc_html__( 'Proxy', 'fs-poster' ); ?>: <?php echo esc_html( $account_info[ 'proxy' ] ); ?>">
							<i class="fas fa-globe"></i>
						</div>
					<?php } ?>
				</div>
				<div class="fsp-account-inline">
					<?php if ( $account_info[ 'status' ] === 'error' ) { ?>
						<div class="fsp-account-failed fsp-tooltip" data-title="<?php echo esc_html( $account_info[ 'error_msg' ] ); ?>">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
						<div class="fsp-account-divider"></div>
					<?php } ?>
					<div class="fsp-account-checkbox">
						<i class="<?php echo is_null( $account_info[ 'is_active' ] ) || empty( $account_info[ 'is_active' ] ) ? 'far fa-check-square' : ( 'fas fa-check-square fsp-is-checked' . ( $account_info[ 'is_active' ] === 'no' ? '' : '-conditionally' ) ); ?>"></i>
					</div>
					<div class="fsp-account-divider"></div>
					<a class="fsp-account-link" href="<?php echo Helper::profileLink( $account_info ); ?>" title="<?php echo esc_html__( 'Profile link', 'fs-poster' ); ?>" target="_blank">
						<i class="fas fa-external-link-alt"></i> </a>
					<div class="fsp-account-more">
						<i class="fas fa-ellipsis-h"></i>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<?php if ( empty( $fsp_params[ 'accounts_list' ] ) ) { ?>
		<div class="fsp-card fsp-emptiness">
			<div class="fsp-emptiness-image">
				<img src="<?php echo Pages::asset( 'Base', 'img/empty.svg' ); ?>">
			</div>
			<div class="fsp-emptiness-text">
				<?php echo esc_html__( 'There haven\'t been added any ' . $fsp_params[ 'err_text' ] . ' yet.', 'fs-poster' ); ?>
			</div>
			<div class="fsp-emptiness-button">
				<button class="fsp-button fsp-accounts-add-button">
					<i class="fas fa-plus"></i> <span><?php echo $fsp_params[ 'button_text' ]; ?></span>
				</button>
			</div>
		</div>
	<?php } ?>
</div>

<script>
	FSPObject.modalURL = 'add_instagram_account';
</script>
<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div>
	<span id="fspAccountsCount" class="fsp-hide"><?php echo count( $fsp_params[ 'accounts_list' ] ); ?></span>

	<?php foreach ( $fsp_params[ 'accounts_list' ] as $account_info ) {
		$node_list = $account_info[ 'node_list' ]; ?>
		<div class="fsp-account">
			<div class="fsp-card fsp-account-item" data-id="<?php echo $account_info[ 'id' ]; ?>" data-type="account">
				<div class="fsp-account-inline">
					<div class="fsp-account-caret fsp-is-rotated">
						<i class="fas fa-angle-up"></i>
					</div>
					<div class="fsp-account-image">
						<img src="<?php echo Helper::profilePic( $account_info ); ?>" onerror="FSPoster.no_photo( this );">
					</div>
					<div class="fsp-account-name">
						<?php echo esc_html( $account_info[ 'name' ] ); ?>
					</div>
					<div class="fsp-account-is-public fsp-tooltip <?php echo ! $account_info[ 'is_public' ] ? 'fsp-hide' : ''; ?>" data-title="<?php echo esc_html__( 'It is public for all WordPress users', 'fs-poster' ); ?>">
						<i class="fas fa-star"></i>
					</div>
					<div class="fsp-account-divider"></div>
					<?php if ( ! empty( $account_info[ 'proxy' ] ) ) { ?>
						<div class="fsp-account-proxy fsp-tooltip" data-title="<?php echo esc_html__( 'Proxy', 'fs-poster' ); ?>: <?php echo esc_html( $account_info[ 'proxy' ] ); ?>">
							<i class="fas fa-globe"></i>
						</div>
						<div class="fsp-account-divider"></div>
					<?php } ?>
					<div class="fsp-account-nodes-count">
						<?php echo $account_info[ 'chats' ]; ?>&nbsp;<?php echo esc_html__( 'Chats', 'fs-poster' ); ?>
					</div>
					<?php if ( $account_info[ 'status' ] !== 'error' ) { ?>
						<button type="button" class="fsp-button" data-load-modal="telegram_add_chat" data-parameter-account_id="<?php echo (int) $account_info[ 'id' ] ?>"><?php echo esc_html__( 'ADD A CHAT', 'fs-poster' ); ?></button>
					<?php } ?>
				</div>
				<div class="fsp-account-inline">
					<?php if ( $account_info[ 'status' ] === 'error' ) { ?>
						<div class="fsp-account-failed fsp-tooltip" data-title="<?php echo esc_html( $account_info[ 'error_msg' ] ); ?>">
							<i class="fas fa-exclamation-triangle"></i>
						</div>
						<div class="fsp-account-divider"></div>
					<?php } ?>
					<a class="fsp-account-link" href="<?php echo Helper::profileLink( $account_info ); ?>" title="<?php echo esc_html__( 'Profile link', 'fs-poster' ); ?>" target="_blank">
						<i class="fas fa-external-link-alt"></i> </a>
					<div class="fsp-account-more">
						<i class="fas fa-ellipsis-h"></i>
					</div>
				</div>
			</div>
			<div class="fsp-account-nodes-container">
				<div class="fsp-account-nodes">
					<?php foreach ( $node_list as $node_info ) { ?>
						<div class="fsp-card fsp-account-item" data-id="<?php echo $node_info[ 'id' ]; ?>" data-type="community">
							<div class="fsp-account-inline">
								<div class="fsp-account-image">
									<img src="<?php echo Helper::profilePic( $node_info ); ?>" onerror="FSPoster.no_photo( this );">
								</div>
								<div class="fsp-account-name">
									<?php echo esc_html( $node_info[ 'name' ] ); ?>
								</div>
								<div class="fsp-account-is-public fsp-tooltip <?php echo ! $node_info[ 'is_public' ] ? 'fsp-hide' : ''; ?>" data-title="<?php echo esc_html__( 'It is public for all WordPress users', 'fs-poster' ); ?>">
									<i class="fas fa-star"></i>
								</div>
								<div class="fsp-account-divider"></div>
								<div class="fsp-account-nodes-count">
									<i class="far fa-paper-plane"></i><?php echo ucfirst( esc_html( empty( $node_info[ 'category' ] ) ? 'no flair' : $node_info[ 'category' ] ) ); ?>
								</div>
							</div>
							<div class="fsp-account-inline">
								<div class="fsp-account-checkbox">
									<i class="<?php echo is_null( $node_info[ 'is_active' ] ) || empty( $node_info[ 'is_active' ] ) ? 'far fa-check-square' : ( 'fas fa-check-square fsp-is-checked' . ( $node_info[ 'is_active' ] === 'no' ? '' : '-conditionally' ) ); ?>"></i>
								</div>
								<div class="fsp-account-divider"></div>
								<a class="fsp-account-link" href="<?php echo Helper::profileLink( $node_info ); ?>" title="<?php echo esc_html__( 'Profile link', 'fs-poster' ); ?>" target="_blank">
									<i class="fas fa-external-link-alt"></i> </a>
								<div class="fsp-account-more">
									<i class="fas fa-ellipsis-h"></i>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<?php if ( ! empty( $public_communities ) ) { ?>
		<div class="fsp-account">
			<div class="fsp-account-nodes">
				<?php foreach ( $public_communities as $node_info ) { ?>
					<div class="fsp-card fsp-account-item" data-id="<?php echo $node_info[ 'id' ]; ?>" data-type="community">
						<div class="fsp-account-inline">
							<div class="fsp-account-image">
								<img src="<?php echo Helper::profilePic( $node_info ); ?>" onerror="FSPoster.no_photo( this );">
							</div>
							<div class="fsp-account-name">
								<?php echo esc_html( $node_info[ 'name' ] ); ?>
							</div>
							<div class="fsp-account-is-public fsp-tooltip <?php echo ! $node_info[ 'is_public' ] ? 'fsp-hide' : ''; ?>" data-title="<?php echo esc_html__( 'It is public for all WordPress users', 'fs-poster' ); ?>">
								<i class="fas fa-star"></i>
							</div>
							<div class="fsp-account-divider"></div>
							<div class="fsp-account-nodes-count">
								<i class="far fa-paper-plane"></i><?php echo ucfirst( esc_html( empty( $node_info[ 'category' ] ) ? 'no flair' : $node_info[ 'category' ] ) ); ?>
							</div>
						</div>
						<div class="fsp-account-inline">
							<div class="fsp-account-checkbox">
								<i class="<?php echo is_null( $node_info[ 'is_active' ] ) || empty( $node_info[ 'is_active' ] ) ? 'far fa-check-square' : ( 'fas fa-check-square fsp-is-checked' . ( $node_info[ 'is_active' ] === 'no' ? '' : '-conditionally' ) ); ?>"></i>
							</div>
							<div class="fsp-account-divider"></div>
							<a class="fsp-account-link" href="<?php echo Helper::profileLink( $node_info ); ?>" title="<?php echo esc_html__( 'Profile link', 'fs-poster' ); ?>" target="_blank">
								<i class="fas fa-external-link-alt"></i> </a>
							<div class="fsp-account-more">
								<i class="fas fa-ellipsis-h"></i>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>

	<?php if ( empty( $fsp_params[ 'accounts_list' ] ) && empty( $public_communities ) ) { ?>
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
	FSPObject.modalURL = 'add_telegram_bot';
</script>
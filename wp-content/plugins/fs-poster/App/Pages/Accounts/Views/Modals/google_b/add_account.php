<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'MODAL' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Accounts', 'js/fsp-accounts-google-b.js' ); ?>"></script>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-google"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__('Add an account', 'fs-poster'); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-modal-step">
		<div class="fsp-form-group">
			<label class="fsp-is-jb">
				<?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> SID
				<a href="https://www.fs-poster.com/documentation/fs-poster-auto-publish-wordpress-posts-to-google-my-business" target="_blank" class="fsp-tooltip" data-title="<?php echo esc_html__( 'How to?', 'fs-poster' ); ?>"><i class="far fa-question-circle"></i></a>
			</label>
			<div class="fsp-form-input-has-icon">
				<i class="far fa-copy"></i>
				<input id="fspCookie_sid" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> SID">
			</div>
		</div>
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> HSID</label>
			<div class="fsp-form-input-has-icon">
				<i class="far fa-copy"></i>
				<input id="fspCookie_hsid" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> HSID">
			</div>
		</div>
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> SSID</label>
			<div class="fsp-form-input-has-icon">
				<i class="far fa-copy"></i>
				<input id="fspCookie_ssid" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> SSID">
			</div>
		</div>
	</div>
	<div class="fsp-form-checkbox-group">
		<input id="fspUseProxy" type="checkbox" class="fsp-form-checkbox"> <label for="fspUseProxy">
			<?php echo esc_html__( 'Use a proxy', 'fs-poster' ); ?>
		</label>
		<span class="fsp-tooltip" data-title="<?php echo esc_html__( 'Optional field. Supported proxy formats: https://127.0.0.1:8888 or https://user:pass@127.0.0.1:8888', 'fs-poster' ); ?>"><i class="far fa-question-circle"></i></span>
	</div>
	<div id="fspProxyContainer" class="fsp-form-group fsp-hide fsp-proxy-container">
		<div class="fsp-form-input-has-icon">
			<i class="fas fa-globe"></i>
			<input id="fspProxy" autocomplete="off" class="fsp-form-input fsp-proxy" placeholder="<?php echo esc_html__( 'Enter a proxy address', 'fs-poster' ); ?>">
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'ADD', 'fs-poster' ); ?></button>
</div>
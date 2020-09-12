<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'MODAL' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Accounts', 'js/fsp-accounts-instagram.js' ); ?>"></script>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-instagram"></i>
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
	<p class="fsp-modal-p fsp-is-jb">
		<?php echo esc_html__( 'Select an authorization method to add a new Instagram account', 'fs-poster' ); ?>
		<a href="https://www.fs-poster.com/documentation/fs-poster-schedule-auto-publish-wordpress-posts-to-instagram" target="_blank" class="fsp-tooltip" data-title="<?php echo esc_html__( 'How to?', 'fs-poster' ); ?>"><i class="far fa-question-circle"></i></a>
	</p>
	<div class="fsp-modal-options">
		<div class="fsp-modal-option fsp-is-selected" data-step="1">
			<div class="fsp-modal-option-image">
				<img src="<?php echo Pages::asset( 'Accounts', 'img/android.svg' ); ?>">
			</div>
			<?php echo esc_html__('Cookie method', 'fs-poster'); ?>
		</div>
		<div class="fsp-modal-option" data-step="2">
			<div class="fsp-modal-option-image">
				<img src="<?php echo Pages::asset( 'Accounts', 'img/rocket.svg' ); ?>">
			</div>
			<?php echo esc_html__('Login & password', 'fs-poster'); ?>
		</div>
	</div>
	<div id="fspModalStep_1" class="fsp-modal-step">
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Cookie', 'fs-poster' ); ?> sessionid</label>
			<div class="fsp-form-input-has-icon">
				<i class="far fa-copy"></i>
				<input id="fspCookie_sessionid" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the cookie', 'fs-poster' ); ?> sessionid">
			</div>
		</div>
	</div>
	<div id="fspModalStep_2" class="fsp-modal-step fsp-hide">
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Username', 'fs-poster' ); ?></label>
			<div class="fsp-form-input-has-icon">
				<i class="fas fa-user"></i>
				<input id="fspUsername" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the username', 'fs-poster' ); ?>">
			</div>
		</div>
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Password', 'fs-poster' ); ?></label>
			<div class="fsp-form-input-has-icon">
				<i class="fas fa-key"></i>
				<input id="fspPassword" autocomplete="off" type="password" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the password', 'fs-poster' ); ?>">
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
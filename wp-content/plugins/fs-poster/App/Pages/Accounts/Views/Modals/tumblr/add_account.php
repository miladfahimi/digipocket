<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'MODAL' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Accounts', 'js/fsp-accounts-tumblr.js' ); ?>"></script>
<script>
	fspConfig.standartAppURL = '<?php echo Helper::standartAppRedirectURL( 'tumblr' ); ?>';
</script>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-tumblr"></i>
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
				<?php echo esc_html__( 'Select an App', 'fs-poster' ); ?>
				<a href="https://www.fs-poster.com/documentation/fs-poster-schedule-share-wordpress-posts-to-tumblr-automatically" target="_blank" class="fsp-tooltip" data-title="<?php echo esc_html__( 'How to?', 'fs-poster' ); ?>"><i class="far fa-question-circle"></i></a>
			</label>
			<select class="fsp-form-select" id="fspModalAppSelector">
				<?php foreach ( $fsp_params[ 'applications' ] as $app ) { ?>
					<option value="<?php echo $app[ 'id' ]; ?>" data-is-standart="<?php echo ( int ) $app[ 'is_standart' ]; ?>"><?php echo esc_html( $app[ 'name' ] ); ?></option>
				<?php }
				if ( empty( $fsp_params[ 'applications' ] ) ) { ?>
					<option disabled><?php echo esc_html__('There isn\'t a Tumblr App!', 'fs-poster'); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="fsp-form-checkbox-group">
		<input id="fspUseProxy" type="checkbox" class="fsp-form-checkbox">
		<label for="fspUseProxy">
			<?php echo esc_html__( 'Use a proxy', 'fs-poster' ); ?>
		</label>
		<span class="fsp-tooltip" data-title="<?php echo esc_html__('Optional field. Supported proxy formats: https://127.0.0.1:8888 or https://user:pass@127.0.0.1:8888' , 'fs-poster'); ?>"><i class="far fa-question-circle"></i></span>
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
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'GET ACCESS', 'fs-poster' ); ?></button>
</div>
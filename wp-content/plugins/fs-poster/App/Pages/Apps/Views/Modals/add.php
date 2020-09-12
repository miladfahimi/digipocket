<?php

namespace FSPoster\App\Pages\Accounts\Views;

defined( 'MODAL' ) or exit;
?>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-plus"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Add a new App', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<p class="fsp-modal-p">
		<?php echo esc_html__( 'Type', 'fs-poster' ); ?>&nbsp;<b><?php echo implode( __( '</b> and <b>', 'fs-psoter' ), $fsp_params[ 'fields' ] ); ?></b>
	</p>
	<div class="fsp-modal-step">
		<input type="hidden" id="fspAppDriver" value="<?php echo esc_html( $fsp_params[ 'driver' ] ); ?>">
		<div class="fsp-form-group <?php echo in_array( 'app_id', $fsp_params[ 'fields' ] ) ? '' : 'fsp-hide' ?>">
			<label><?php echo esc_html__( 'The App ID', 'fs-poster' ); ?></label>
			<input id="fspAppID" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'The App ID', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group <?php echo in_array( 'app_key', $fsp_params[ 'fields' ] ) ? '' : 'fsp-hide' ?>">
			<label><?php echo esc_html__( 'The App Key', 'fs-poster' ); ?></label>
			<input id="fspAppKey" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'The App Key', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group <?php echo in_array( 'app_secret', $fsp_params[ 'fields' ] ) ? '' : 'fsp-hide' ?>">
			<label><?php echo esc_html__( 'The App Secret', 'fs-poster' ); ?></label>
			<input id="fspAppSecret" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'The App Secret', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group <?php echo $fsp_params[ 'driver' ] === 'fb' ? '' : 'fsp-hide'; ?>">
			<label><?php echo esc_html__( 'The App Version', 'fs-poster' ); ?></label>
			<select id="fspAppVersion" class="fsp-form-select">
				<option value="0" <?php echo $fsp_params[ 'driver' ] !== 'fb' ? 'selected' : 'disabled'; ?>></option>
				<option value="80" <?php echo $fsp_params[ 'driver' ] === 'fb' ? 'selected' : ''; ?>>v8.0</option>
				<option value="70">v7.0</option>
				<option value="60">v6.0</option>
				<option value="50">v5.0</option>
				<option value="40">v4.0</option>
				<option value="33">v3.3</option>
				<option value="32">v3.2</option>
				<option value="31">v3.1</option>
			</select>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'CANCEL', 'fs-poster' ); ?></button>
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'ADD AN APP', 'fs-poster' ); ?></button>
</div>
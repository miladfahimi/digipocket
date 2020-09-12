<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'MODAL' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Accounts', 'js/fsp-accounts-telegram-chat.js' ); ?>"></script>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-telegram"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__('Add a chat', 'fs-poster'); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-modal-step">
		<input id="fspAccountID" type="hidden" class="fsp-hide" value="<?php echo $fsp_params[ 'accountId' ]; ?>">
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Chat ID', 'fs-poster' ); ?></label>
			<input id="fspChatID" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the Chat ID', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Last active chats (optional)', 'fs-poster' ); ?>&emsp;<i id="fspReloadChats" class="fas fa-sync fsp-tooltip" data-title="<?php echo esc_html__( 'Reload the list', 'fs-poster' ); ?>"></i></label>
			<select class="fsp-form-select" id="fspModalChatSelector">
				<option disabled selected><?php echo esc_html__( 'No chat found. Reload and check again.', 'fs-poster' ); ?></option>
			</select>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'ADD', 'fs-poster' ); ?></button>
</div>
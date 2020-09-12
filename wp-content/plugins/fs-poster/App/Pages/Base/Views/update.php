<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-box-container">
	<div class="fsp-card fsp-box">
		<div class="fsp-box-info">
			<i class="fas fa-info-circle"></i><?php echo esc_html__( 'A new version ', 'fs-poster' ); ?><?php echo esc_html(Helper::getVersion()); ?><?php echo esc_html__( ' is available. Please update the plugin.', 'fs-poster' ); ?>
		</div>
		<div class="fsp-box-logo">
			<img src="<?php echo Pages::asset( 'Base', 'img/logo.svg' ); ?>">
		</div>
		<div class="fsp-form-group">
			<input type="text" id="fspPurchaseKey" autocomplete="off" class="fsp-form-input" placeholder="<?php echo __( 'Enter the purchase key', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group">
			<button type="button" class="fsp-button" id="fspUpdateBtn"><?php echo __( 'UPDATE AND ACTIVATE', 'fs-poster' ); ?></button>
		</div>
	</div>
</div>
<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-box-container">
	<div class="fsp-card fsp-box">
		<div class="fsp-box-info">
			<i class="fas fa-info-circle"></i><?php echo esc_html__( 'Please activate the plugin.', 'fs-poster' ); ?>
		</div>
		<div class="fsp-box-logo">
			<img src="<?php echo Pages::asset( 'Base', 'img/logo.svg' ); ?>">
		</div>
		<div class="fsp-form-group">
			<input type="text" autocomplete="off" id="fspPurchaseKey" class="fsp-form-input" placeholder="<?php echo __( 'Enter the purchase key', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group">
			<input type="email" autocomplete="off" id="fspEmail" class="fsp-form-input" placeholder="<?php echo __( 'Enter your e-mail address (optional)', 'fs-poster' ); ?>">
		</div>
		<div class="fsp-form-group">
			<select id="fspMarketingStatistics" class="fsp-form-select">
				<option disabled selected><?php echo __( 'Where did You find us?', 'fs-poster' ); ?></option>
				<?php echo Helper::fetchStatisticOptions(); ?>
			</select>
		</div>
		<div class="fsp-form-group">
			<button type="button" class="fsp-button" id="fspInstallBtn"><?php echo __( 'ACTIVATE', 'fs-poster' ); ?></button>
		</div>
	</div>
</div>
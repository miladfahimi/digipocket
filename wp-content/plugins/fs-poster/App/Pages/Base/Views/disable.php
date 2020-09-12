<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-box-container">
	<div class="fsp-card fsp-box">
		<div class="fsp-box-info">
			<i class="fas fa-info-circle"></i><?php echo esc_html__( 'Your plugin is disabled. Please activate the plugin.', 'fs-poster' ); ?>
		</div>
		<div class="fsp-box-logo">
			<img src="<?php echo Pages::asset( 'Base', 'img/logo.svg' ); ?>">
		</div>
		<div class="fsp-form-group">
			<label>Reason: <?php echo Helper::getOption( 'plugin_alert' ); ?></label>
		</div>
	</div>
</div>
<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'MODAL' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Share', 'js/fsp-share-popup.js' ); ?>"></script>
<link rel="stylesheet" href="<?php echo Pages::asset( 'Share', 'css/fsp-share-popup.css' ); ?>">

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-share"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Share', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-form-checkbox-group">
		<input id="background_share_chckbx" type="checkbox" class="fsp-form-checkbox" <?php echo Helper::getOption( 'share_on_background', '1' ) == 1 ? 'checked' : ''; ?>>
		<label for="background_share_chckbx">
			<?php echo esc_html__( 'Share in the background', 'fs-poster' ); ?>
		</label>
	</div>
	<div class="fsp-form-group">
		<?php
		$post_id = (int) $fsp_params[ 'parameters' ][ 'post_id' ];
		define( 'NOT_CHECK_SP', 'true' );

		Pages::controller( 'Base', 'MetaBox', 'post_meta_box', [
			'post_id' => $post_id,
			'minified_metabox' => true
		] );
		?>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'CANCEL', 'fs-poster' ); ?></button>
	<button class="fsp-button share_btn"><?php echo esc_html__( 'SHARE', 'fs-poster' ); ?></button>
</div>

<script>
	FSPObject.postID = '<?php echo (int) $fsp_params[ 'parameters' ][ 'post_id' ]; ?>';
</script>
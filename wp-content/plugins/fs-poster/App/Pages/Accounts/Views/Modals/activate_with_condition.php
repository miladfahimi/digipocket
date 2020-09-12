<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'MODAL' ) or exit;
?>

<script async src="<?php echo Pages::asset( 'Accounts', 'js/fsp-accounts-activate.js' ); ?>"></script>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-user"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Define conditions for the account', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<input type="hidden" id="fspActivateURL" value="<?php echo $fsp_params[ 'parameters' ][ 'ajaxUrl' ]; ?>">
	<input type="hidden" id="fspActivateID" value="<?php echo $fsp_params[ 'parameters' ][ 'id' ]; ?>">
	<div class="fsp-modal-step">
		<div class="fsp-form-group">
			<select id="fspCategories" class="fsp-form-input select2-init" multiple>
				<?php
				$taxonomies = get_terms( [ 'category' ], [ 'hide_empty' => FALSE ] );

				echo '<optgroup label="Post categories">';
				foreach ( $taxonomies as $categ )
				{
					echo '<option value="' . htmlspecialchars( $categ->term_id ) . '"' . ( in_array( (string) $categ->term_id, $fsp_params[ 'parameters' ][ 'categories' ] ) ? ' selected' : '' ) . '>' . htmlspecialchars( $categ->name ) . '</option>';
				}
				echo '</optgroup>';

				if ( taxonomy_exists( 'product_cat' ) )
				{
					$productTaxs = get_terms( [ 'product_cat' ], [ 'hide_empty' => FALSE ] );

					echo '<optgroup label="Product categories">';
					foreach ( $productTaxs as $categ )
					{
						echo '<option value="' . htmlspecialchars( $categ->term_id ) . '"' . ( in_array( (string) $categ->term_id, $fsp_params[ 'parameters' ][ 'categories' ] ) ? ' selected' : '' ) . '>' . htmlspecialchars( $categ->name ) . '</option>';
					}
					echo '</optgroup>';
				}

				$otherTaxonomies = get_terms( [ 'hide_empty' => FALSE, 'orderby' => 'taxonomy' ] );
				echo '<optgroup label="Custom categories"></optgroup>';

				$saveLastTaxonomyName = FALSE;
				foreach ( $otherTaxonomies as $categ )
				{
					if ( in_array( $categ->taxonomy, [ 'category', 'product_cat' ] ) )
					{
						continue;
					}

					if ( $saveLastTaxonomyName !== $categ->taxonomy )
					{
						echo '</optgroup><optgroup label="' . htmlspecialchars( $categ->taxonomy ) . '">';
						$saveLastTaxonomyName = $categ->taxonomy;
					}

					echo '<option value="' . htmlspecialchars( $categ->term_id ) . '"' . ( in_array( (string) $categ->term_id, $fsp_params[ 'parameters' ][ 'categories' ] ) ? ' selected' : '' ) . '>' . htmlspecialchars( $categ->name ) . '</option>';
				}
				echo $saveLastTaxonomyName ? '</optgroup>' : '';
				?>
			</select>
		</div>
	</div>
	<div class="fsp-modal-options">
		<div class="fsp-modal-option <?php echo $fsp_params[ 'parameters' ][ 'filter_type' ] === 'in' ? 'fsp-is-selected' : ''; ?>" data-name="in">
			<?php echo esc_html__( 'Share only the posts of the selected categories, tags, etc...', 'fs-poster' ); ?>
		</div>
		<div class="fsp-modal-option <?php echo $fsp_params[ 'parameters' ][ 'filter_type' ] === 'ex' ? 'fsp-is-selected' : ''; ?>" data-name="ex">
			<?php echo esc_html__( 'Do not share the posts of the selected categories, tags, etc...', 'fs-poster' ); ?>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalActivateBtn" class="fsp-button"><?php echo esc_html__( 'ACTIVATE', 'fs-poster' ); ?></button>
</div>
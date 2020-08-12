<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="acf-to-rest-api-settings">
    <code><?php echo esc_url( home_url( 'wp-json/acf/' ) ); ?></code>
    <select name="acf_to_rest_api_settings[request_version]">
        <option value="2" <?php selected( 2, $request_version ); ?>>v2</option>
        <option value="3" <?php selected( 3, $request_version ); ?>>v3</option>
    </select>
</div>
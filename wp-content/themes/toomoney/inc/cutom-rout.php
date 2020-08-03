<?php
//REGISTER CUSTOM FIELD IN WP API
function toomoney_custom_rest() {
register_rest_field('ads', 'index', array(
'get_callback' => function() {return get_field('index');}
));
register_rest_field('ads', 'amount', array(
'get_callback' => function() {return get_field('amount');}
));
register_rest_field('ads', 'buy_sale', array(
'get_callback' => function() {return get_field('buy_sale');}
));
$postypes_to_exclude = ['acf-field-group','acf-field'];
$extra_postypes_to_include = ["ads"];
$post_types = array_diff(get_post_types(["_builtin" => false], 'names'),$postypes_to_exclude);
array_push($post_types, $extra_postypes_to_include);
foreach ($post_types as $post_type) {
register_rest_field( $post_type, 'ACF', [
'get_callback' => 'expose_ACF_fields',
'schema' => null,
]
);
}
}
add_filter( 'acf/rest_api/item_permissions/update', function( $permission, $request, $type ) {
if ( 'user' == $type && method_exists( $request, 'get_param' ) && get_current_user_id() == $request->get_param( 'id' ) )
{
return true;
}
return $permission;
}, 10, 3 );

function expose_ACF_fields( $object ) {
$ID = $object['id'];
return get_fields($ID);
}
add_action('rest_api_init', 'toomoney_custom_rest');
?>
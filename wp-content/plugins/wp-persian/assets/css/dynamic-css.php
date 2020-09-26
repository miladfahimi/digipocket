<?php
/**
 * User: Siavash
 * Date: 22/06/2018
 * Time: 12:20 PM
 */

$font_main = get_option( 'wpp_adminpanel_font_main' );
$font_h = get_option( 'wpp_adminpanel_font_h' );
$font_nav = get_option( 'wpp_adminpanel_font_nav' );

?>
body, input, textarea, select, option{
    font-family:"<?php echo $font_main; ?>",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif !important;
}
h1,h2,h3,h4,h5,h6{
    font-family:"<?php echo $font_h; ?>",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif !important;
}
#adminmenuwrap *, #wpadminbar *{
    font-family:"<?php echo $font_nav; ?>",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif !important;
}
#wpadminbar span.ab-icon{
    font-family:dashicons !important;
}

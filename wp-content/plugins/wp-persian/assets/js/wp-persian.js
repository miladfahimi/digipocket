// JavaScript Document
jQuery(document).ready(function() {
	
    if (jQuery("select[name='wpp_adminpanel_locale']").val()=="fa_IR")
    {
        jQuery("input[name='wpp_adminpanel_context']").closest("tr").hide();
        jQuery("input[name='wpp_adminpanel_context']").attr('checked',false);
    }
    //jQuery("select[name='WPLANG']").val('');
    jQuery("select[name='WPLANG']").closest("tr").hide();

    jQuery("select[name='wpp_adminpanel_locale']").change(function(){
        if (jQuery(this).val()=="fa_IR") {
            jQuery("input[name='wpp_adminpanel_context']").closest("tr").hide();
            jQuery("input[name='wpp_adminpanel_context']").attr('checked',false);

        }else
            jQuery("input[name='wpp_adminpanel_context']").closest("tr").show();

    });
});// ready Document

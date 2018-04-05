jQuery(document).ready(function ($) {
    $('#acme-demo-setup-upload-zip-form').submit(function (e) {
        if( window.FormData === undefined ){
            return true;
        }
        e.preventDefault();
        var fd = new FormData();
        var file = $(this).find('#upload-zip-archive');
        if(!file.val()){
            jQuery('#at-demo-ajax-install-error').show();
            return false;
        }
        else{
            jQuery('#at-demo-ajax-install-error').hide();
        }
        var _wpnonce = $(this).find('input[name=_wpnonce]');
        var _wp_http_referer = $(this).find('input[name=_wp_http_referer]');

        var individual_file = file[0].files[0];

        fd.append("upload-zip-archive", individual_file);
        fd.append('action', 'acme_demo_setup_ajax_setup');
        fd.append('_wpnonce', _wpnonce.val());
        fd.append('_wp_http_referer', _wp_http_referer.val());

        jQuery.ajax({
            type: 'POST',
            url: acme_demo_setup_object.ajaxurl,
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function (data, settings) {
                jQuery('#acme-demo-setup-upload-zip-form').remove();
                jQuery('#at-demo-ajax-install-result-loading').show();
                jQuery('#at-demo-ajax-install').html(acme_demo_setup_object.importing);
            },
            success   : function (data) {
                jQuery('#at-demo-ajax-install-result-loading').hide();
                jQuery('#at-demo-ajax-install-result-title').show();
                jQuery('#at-demo-ajax-install-result').html(data);
            },
            error     : function (jqXHR, textStatus, errorThrown) {
                jQuery('#at-demo-ajax-install-result-loading').hide();
                jQuery('#at-demo-ajax-install-result-error').show();
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
        return false;
    });
});
jQuery(document).on('click', 'button#contact-send-a', function() {
    var formData = new FormData();
    formData.append('fullname', jQuery('form#ajax-contact-a-frm').find('.input-ca-fullname').val());
    formData.append('email', jQuery('form#ajax-contact-a-frm').find('.input-ca-email').val());
    formData.append('comment', jQuery('form#ajax-contact-a-frm').find('.input-ca-comment').val());
    formData.append('uplfile', jQuery('form#ajax-contact-a-frm').find('input.input-ca-uplfile')[0].files[0]);
    jQuery.ajax({
        url:baseurl+'contact/sendWithAttachData',
        type: 'post',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        dataType:'json',    
        beforeSend: function () {
            jQuery('button#contact-send-a').button('loading');
        },
        complete: function () {
            jQuery('button#contact-send-a').button('reset');
            jQuery("form#ajax-contact-a-frm").find('textarea, input, file').each(function () {
                jQuery(this).val('');
            });
            setTimeout(function () {
                jQuery('span#success-msg').html('');
            }, 4000);
        },                
        success: function (json) {           
           $('.text-danger').remove();
            if (json['error']) {
             
                for (i in json['error']) {

	                var element = $('.input-ca-' + i.replace('_', '-'));
	                if ($(element).parent().hasClass('input-group')) {
                       
		                $(element).parent().after('<small class="text-danger">' + json['error'][i] + '</small>');
	                } else {
		                $(element).after('<small class="text-danger">' + json['error'][i] + '</small>');
	                }
                }
            } else {
                jQuery('span#success-msg').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Your query has been successfully submitted.</div>');
            }                       
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }        
    });
});

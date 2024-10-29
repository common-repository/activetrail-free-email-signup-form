if (typeof (jQuery) == 'function') {
	
	 
(function ($) {
	"use strict";
	
	$.active_trail_validator = function (form)
	{
		var elems = $(form).find('.required');
		var success = true;
		$(elems).each(function () {
			 var controlgroup = $(this).parents('.control-group');
			 var helpblock = $(this).closest('.help-block');
			 if (  ! $(this).val())
			 {
			 	controlgroup.addClass('error');
			 	alert(controlgroup.find('.control-label').text() + ' is required');
			    $(this).focus();
			 	success = false;
			 }
			 else { controlgroup.removeClass('error'); }
		});
		
		return success;
	};
	
	$.active_trail_bind_ajax = function ()
	{
		var pname = _activetrail_config['PLUGIN_NAME'];
		 var ajax_submit = _activetrail_config['FRONTEND_AJAX'];
		 
		 $('.'+ pname +'-signup-form-ajax-active').each(function () {
		 	
		 	 var formx = $(this).find('.'+pname+'-main-form');
		 	 
		 	 if (formx.hasClass('processed-ajax-activated')) return false;
		 	 
		 	 $(formx).addClass('processed-ajax-activated').submit(function () {
		 	 	
		 	 	 if ( ! $.active_trail_validator(this))
		 	 	 {
		 	 		return false;
		 	 	 }
		 	 	
		 	 	 var self = $(this);
		 	 	 var buttons = $(this).find('[type="submit"]').attr('disabled', 'disabled');
		  
		 	 	 
		 	 	 $.ajax({
			 	 	 	dataType : 'json',
			 	 	 	type     : $(this).attr('method'),
			 	 	 	url      : ajax_submit,
			 	 	 	data     : $(this).serialize(),
			 	 	 	complete : function () {
			 	 	 		 buttons.removeAttr('disabled');
			 	 	    },
			 	 	 	success  : function (data)
			 	 	 	{
			 	 	 		 if ( ! data['html'])
			 	 	 		 {
			 	 	 		 	alert('An error occurred, please refresh the page.');
			 	 	 		 	return;
			 	 	 		 }
			 	 	 		 $(self).parents('.activetrail-signup-form:first').html($('<div>'+data['html']+'</div>').find('.activetrail-signup-form').html());
			 	 	 		 $.active_trail_bind_ajax();
			 	 	 	}
		 	 	 });
		 	 	 
		 	 	 return false;
		 	 	 
		 	 });
		 	
		 });
	};
	$(function () {
		 
		 $.active_trail_bind_ajax();
		 
	});
	
}(jQuery));

}
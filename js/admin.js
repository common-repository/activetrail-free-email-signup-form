(function ($) {
	var log = function (l) { if ('console' in window) console.log(l); };
	$(function () {
		 
		 $(document).ajaxStart(function () {
		 	  $('.btn:not(.btn-disabled)').each(function () {
		 	  	    $(this).attr('disabled', 'disabled');
		 	  }); 
		 });
		 
		 $(document).ajaxComplete(function () {
		 	
		 	 $('.btn:not(.btn-disabled)').each(function () {
		 	  	    $(this).removeAttr('disabled');
		 	  }); 
		 	  
		 });
		  
		 var container = $('.atrail');
		 
 		 container.find('#layoutChoices input[type="radio"]').click(function ()  {
 		 	
 		 	   container.find('#layoutChoices .layout_preview').hide();
 		 	   container.find('#layoutChoices #layoutPreview_' + $(this).val()).show();
 	 
 		 	   
 		 });
 		 
 		 container.find('.layout_preview2').click(function () {
 		 	 container.find('.layout_preview2').removeClass('layout_preview2active');
 		 	 var input = $(this).find('input').attr("checked", "checked");
 		 	 $(this).addClass('layout_preview2active');
 		 	 return false;
 		 });
		 
		 
		 
		 
	});
}(jQuery));
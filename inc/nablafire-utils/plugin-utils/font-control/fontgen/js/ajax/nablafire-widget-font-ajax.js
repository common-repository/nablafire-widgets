jQuery(document).ready(function( $ ){

	var ajax_request = function( formData, action ){
			
		$.ajax({
			type: 'POST', 
			dataType: 'json', 
			url: NABLAFIRE_WIDGET_FONT_AJAX.ajaxurl,
			data: {
				action: action,
				data: formData,
			},
			success: function(response){
				if ( response.success === true ){
					//alert( response.data );
					var variants = $.parseJSON(response.data);
					var select   = document.getElementById(formData.variant_id);
					select.innerHTML = "";
    				for (i = 0; i < variants.length; i++){
						var option = document.createElement("option");
						option.text = variants[i];
						if (variants[i] === "regular"){
							option.selected = true;
						}
						select.add(option); 
    				}
				}
				else { 
			        alert( response.data );
				}	
			}  // Close success
		}); // Close ajax request
	} // Close function

	$(document).on('change','.widget-font-family-control',function(e){
       
        e.preventDefault();
       	var _next = $(this).closest('div').find(".widget-font-variant-control");
        var formData = {
			'font_id'    : this.id, 
			'font_val'   : this.value,
			'variant_id' : _next.attr('id')
		}
		// We only do the ajax request if variant-control has been rendered. This allows 
		// us to create a functional font control with no variant field (e.g. if we want
		// to enqueue ALL variants for a given font for example).  
		if (typeof _next != 'undefined'){
			ajax_request( formData, 'return_font_variants');
		}
    });

}); // Close Script 
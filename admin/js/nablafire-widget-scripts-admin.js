
jQuery(function($) {

	'use strict';

	var file_frame;
	var clicked;
	var attachment;

	// Toggle widget subsections
    jQuery(document).on('click', '.nabla-widget-admin-toggle-1', function(e){
    	$(this).toggleClass('open');
    	$('.nabla-widget-admin-field-1').toggle();
    });

    jQuery(document).on('click', '.nabla-widget-admin-toggle-2', function(e){
    	$(this).toggleClass('open');
    	$('.nabla-widget-admin-field-2').toggle();
  	});

    jQuery(document).on('click', '.nabla-widget-admin-toggle-3', function(e){
	    $(this).toggleClass('open');
    	$('.nabla-widget-admin-field-3').toggle();
    });

	// Upload button functionality
	jQuery(document).on("click", ".upload-button", function( event ){
    	clicked = jQuery(this);
    	event.preventDefault();

		if ( file_frame ) {
        	file_frame.open();
        	return;
     	}
   
     	file_frame = wp.media.frames.file_frame = wp.media({
        	title: jQuery( this ).data( "uploader_title" ),
			button: {
				text: jQuery( this ).data( "uploader_button_text" ),
         	},
			multiple: false  
		});
   
		file_frame.on( "select", function() {

			attachment = file_frame.state().get("selection").first().toJSON();

			clicked.parent().find(".upload-field").val(attachment.url);
			clicked.parent().parent().find(".uploader-photo").addClass("active");
			clicked.parent().parent().find(".uploader-photo img.preview").attr("src",attachment.url);
		});
	
		file_frame.open();
	});

	// Color pickers
    $( document ).ready( function() {
        $( '#widgets-right .widget:has(.color-picker)' ).each( function () {            	
        	$(this).find( '.color-picker' ).wpColorPicker()
        });
    });

    // Update color pickers
   	$( document ).on( 'widget-added widget-updated', function() {
		$(this).find( '.color-picker' ).wpColorPicker()
	});
          
});

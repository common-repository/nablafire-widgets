<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//
// This is a container class for the font control AJAX behaviour. Note that wp_enqueue_scripts 
// and wp_localize_script both appear in the WP_Customize_Font_Control classfile. This class 
// contains one method
//
// update_font_variants()  : The AJAX callback for WP_Customize_Font_Control.
//
class Nablafire_Widget_Font_AJAX {

	public function __construct($font_gen) {
		$this->font_gen = $font_gen;
	}

	public function return_font_variants(){

		// Data that has been AJAXed back 
    	$font_id  	= (string)$_POST['data']['font_id'];
		$font_val   = (string)$_POST['data']['font_val'];
		$variant_id = (string)$_POST['data']['variant_id'];

		$_json = json_encode( $this->font_gen->get_variants($font_val) );
	    if ( $_json != false ) {
		  	wp_send_json_success($_json);
		}
		else {
			wp_send_json_error('AJAX return error');
		}
		wp_die();

	}
}
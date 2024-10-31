<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nablafire_Widget_Data_Sanitize {

	function __construct($defaults){
		$this->defaults = $defaults;
	}

	// Pass	(in this widget case this is used for sanitize checkbox)
	function sanitize_pass( $instance, $key ){
		return $instance[$key];
	}

	// Text field
	function sanitize_text_field($instance, $key){
		return sanitize_text_field( $instance[$key] );	
	}
	
	// URL
	function sanitize_url( $instance, $key ) {
		return esc_url_raw( $instance[$key] );
	}

	// Allow for word characters and -. Also optionally incude the # token. If the
	// user does not add it, we will add it for them here. 
	function sanitize_html_id($instance, $key){
		if(preg_match("/#?(\w|-)*/", $instance[$key] , $_input)){
			return ($str[0] != '#') ? '#'. $instance[$key]  : $instance[$key] ;
		}
		else{
			return $this->defaults[$key];
		}
	}

	// kill evil scripts 
	function sanitize_kses($instance, $key){
		$args = array(
  		  	//formatting
    		'strong'=> array(),
    		'span'	=> array(),
    		'em'    => array(),
    		'b'     => array(),
    		'i'     => array(),
    		'br'	=> array(),
		    'ul'	=> array(),
		    'ol'	=> array(),
		    'li'	=> array(),
		    //links
    		'a'     => array(
        		'href' => array()
    		)
		);
		return wp_kses( $instance[$key], $args );
	}

	// Image paths
	function sanitize_image($instance, $key) {
	    $mimes = array(
    	    'jpg|jpeg|jpe' => 'image/jpeg',
    	    'gif'          => 'image/gif',
    	    'png'          => 'image/png',
    	    'bmp'          => 'image/bmp',
       		'tif|tiff'     => 'image/tiff',
        	'ico'          => 'image/x-icon'
    	);
		// Return an array with file extension and mime_type.
    	$file = wp_check_filetype( $instance[$key], $mimes );
		// If $image has a valid mime_type, return it; otherwise, return the default.
    	return ( $file['ext'] ? $instance[$key] : $this->defaults[$key] );
	}

	// Sanitize dropdown 
	function sanitize_dropdown($instance, $key, $atts){
		return ( in_array($instance[$key], $atts) ? $instance[$key] : $this->defaults[$key]);
	}

	// Sanitize number range
	function sanitize_number_range($instance, $key, $atts){
		$number = absint( $instance[$key] );
		$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
		$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
		return (( $min <= $number && $number <= $max ) ? $number : $this->defaults[$key]);
	}

	// regex sanitizers ... 
	function sanitize_alpha_color($instance, $key){
		// Check for rgba, #000, #000000	
		if(preg_match("/^rgba\((([0-9]\s*|[1-9][0-9]\s*|1[0-9][0-9]\s*|2[0-4][0-9]\s*|25[0-5]\s*),){3}0(\.\d{1,2})?\s*\)$|^#[\d|a-f|A-F]{3}$|^#[\d|a-f|A-F]{6}$/", $instance[$key], $_input)){
				return $instance[$key];
		}
		else{
			return $this->defaults[$key];
		}
	}

	function sanitize_hex_color($instance, $key){
		// Check for hex color #000, #000000	
		if(preg_match("/^#[\d|a-f|A-F]{3}$|^#[\d|a-f|A-F]{6}$/", $instance[$key], $_input)) {
			return $instance[$key];
		}
		else {
			return $this->defaults[$key];
		}
	}

	// Matches CSS value with single unit
	function sanitize_css_unit_value($instance, $key){
		if(preg_match("/^\s*(-?\d{1,3}(\.\d{1,3})?(em|vw|vh|cm|mm|in|px|pt))\s*$/", $instance[$key], $_input)) {
			return $instance[$key];
		}
		else {
			return $this->defaults[$key];
		}
	}

	// Matches the above OR zero (for 0=auto settings). If 0 then strip whitespace
	function sanitize_css_unit_value_auto($instance, $key){
		if(preg_match("/^\s*(-?\d{1,3}(\.\d{1,3})?(em|vw|vh|cm|mm|in|px|pt))\s*$|\s*(0)\s*/", $instance[$key], $_input)) {
			// Note that zero is in fourth capture group ...
			return ($_input[4]=='0') ? $_input[4] : $instance[$key];
		}
		else {
			return $instance[$key];
		}

	}

	// Matches margin shorthand for CSS/allows negative values various units and decimals. 
	function sanitize_css_margin_shorthand($instance, $key){
		if(preg_match("/^(\s*-?\d{1,3}(\.\d{1,3})?(em\s*|vw\s*|vh\s*|cm\s*|mm\s*|in\s*|px\s*|pt\s*)){1,4}$/", $instance[$key], $_input)){
			return $instance[$key];	
		}
		else{
			return $this->defaults[$key];
		}
	}

	// This is the same as above, but padding cannot be negative so we omit the minus sign.
	function sanitize_css_padding_shorthand($instance, $key){
		if(preg_match("/^(\s*\d{1,3}(\.\d{1,3})?(em\s*|vw\s*|vh\s*|cm\s*|mm\s*|in\s*|px\s*|pt\s*)){1,4}$/", $instance[$key], $_input)){
			return $instance[$key];	
		}
		else{
			return $this->defaults[$key];
		}
	}
}
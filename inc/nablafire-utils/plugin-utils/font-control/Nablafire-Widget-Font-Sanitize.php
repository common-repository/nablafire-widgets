<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nablafire_Widget_Font_Sanitize {


	function __construct($font_gen, $defaults){
		$this->font_gen = $font_gen;
		$this->defaults = $defaults;
	}

	// Font Family 
	function sanitize_font_fam( $instance, $key ){
		return (
			array_key_exists($instance[$key], $this->font_gen->fonts) ? 
				$instance[$key] : $this->defaults[$key]); 
	}

	// Font Variant 
	function sanitize_font_var( $instance, $key ){
		$_key  = preg_replace("/(\w*)var(\w*)/ ", "$1fam$3", $key);		
		$_variants = $this->font_gen->get_variants( $instance[$_key] );
		return ( in_array($instance[$key], $_variants) ? 
			$instance[$key] : $this->defaults[$key] );
	}

	// Font Size (min=8, max=400)
	function sanitize_font_size( $instance, $key ) {
		$number = absint( $instance[$key] ); $min = 8; $max=400;
		return (( $min <= $number && $number <= $max ) ? $number : $this->defaults[$key]);
	}

	// Font Color. Straightforward 
	function sanitize_font_color($instance, $key){
		// Check for rgba, #000, #000000	
		if(preg_match("/^rgba\((([0-9]\s*|[1-9][0-9]\s*|1[0-9][0-9]\s*|2[0-4][0-9]\s*|25[0-5]\s*),){3}0\.\d{1,2}\s*\)$|^#[\d|a-f|A-F]{3}$|^#[\d|a-f|A-F]{6}$/", $instance[$key], $_input)){
				return $instance[$key];
		}
		else{
			return $this->defaults[$key];
		}
	}

}
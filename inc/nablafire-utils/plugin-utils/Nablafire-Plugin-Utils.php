<?php 

class Nablafire_Plugin_Utils {
	
	function __construct($utils_path, $utils_uri) {

		// For autoloader
		if (!defined('PLUGIN_UTILS_PATH')) {
			define( 'PLUGIN_UTILS_PATH', $utils_path );
		} 

		$this->utils_path = $utils_path;
		$this->utils_uri  = $utils_uri;

		$this->font_gen   = 
			new Nablafire_Widget_Font_Autogen(
				$this->utils_path, 
				$this->utils_uri
			);
		$this->font_ajax  = 
			new Nablafire_Widget_Font_AJAX($this->font_gen);	 
			
		// Enqueue Scripts 
		add_action('admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Register AJAX callback for font control. 		
		add_action('wp_ajax_return_font_variants', array( $this->font_ajax, 'return_font_variants') );
	}

	function enqueue(){
		
		if(version_compare(get_bloginfo('version'),'4.9', '>=') ) {
			$sub_directory = '4.9/';
		}
		else {
			$sub_directory = '4.8/';
		}

		// Enqueue wp-color-picker
		wp_enqueue_style('wp-color-picker');

		// Load CSS if > 4.9
		if ( strcmp($sub_directory, '4.9/') == 0){

			wp_enqueue_style(
				'nablafire-widget-color-control',
				trailingslashit($this->utils_uri) . 
					'color-control/css/' . $sub_directory . 'nablafire-widget-color-control.css',
				array( 'wp-color-picker' ),
				'1.0.0'
			);
		}

		// Alpha color picker JS
		wp_enqueue_script(
			'nablafire-widget-color-control',
			trailingslashit($this->utils_uri) . 
				'color-control/js/' . $sub_directory . 'nablafire-widget-color-control.js',
			array( 'jquery', 'wp-color-picker' ),
			'1.0.0'
		);

		// Font AJAX handler
		wp_enqueue_script(
			'nablafire-widget-font-ajax',
			trailingslashit($this->utils_uri) . 
				'font-control/fontgen/js/ajax/nablafire-widget-font-ajax.js',
			array( 'jquery'),
			'1.0.0',
			true
		);

		// Need to expose ajaxurl for processing AJAX requests. What localize 
		// scripts does is to store the following array in a DOM variable called 
		// CUSTOMIZE_FONT_CONTROL. When this class is called, CUSTOMIZE_FONT_CONTROL
		// is served up along with the script 'customize-font-control-ajax' 
		wp_localize_script('nablafire-widget-font-ajax', 
							'NABLAFIRE_WIDGET_FONT_AJAX', array(
							'ajaxurl'  => admin_url('admin-ajax.php'),
		) );
	}
}

// Plugin Utils Autoloader
spl_autoload_register( function ( $class_name ) {

	// An array of class slugs we will check against 
	$class_slugs = array(
		'Nablafire' => false,
	);

	// Otherwise scan these directories for the class file 			
	$array_paths = array(   
   	  	'data-control/',
		'font-control/',
		'font-control/fontgen/',
 	);

	// If our class_name starts with something in the above array, then set its
	// is_class to true. Note that strpos returns int if substring is found and 
	// bool(false) if substring is not found. 
	foreach($class_slugs as $slug => $is_class){ 

		// If the class name is in the list above, set its value to true
		$class_slugs[$slug] = is_int(strpos($class_name, $slug)) ? true : false;       

		// If the value was just set to true, then do the following
		if ( $class_slugs[$slug] ){
			// If the class exists, then simply return
			if ( class_exists( $class_name ) ) {return;}
			// Otherwise, search array paths for the classfile, include, return
			foreach($array_paths as $path){  
				$class_file = str_replace( '_', '-', $class_name );             
 				$class_path = PLUGIN_UTILS_PATH . $path . $class_file . '.php';
				if ( file_exists( $class_path ) ) {include $class_path; return;}
			} 
		}
	}
} );



<?php
/*
Plugin Name: 	Nablafire Widgets
Description: 	Nablafire Fontpage Widgets Plugin
Version:		1.1.4
Author:			Nablafire
Author URI:		http://www.nablafire.com/
Domain Path:	/languages
Text Domain:	nablafire-widgets
License:		GNU General Public License v3.0
License URI:	http://www.gnu.org/licenses/gpl.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// BEGIN CONTAINER CLASS. This class will contiain the names of our 
// widget, stylesheet URIs, JS URIs. It will also enqueue our styles 
// and scripts for us. Finally it will register and unregister widgets
// which we will develop independently.  
if ( !class_exists('nablafire_widgets') ) {

	class nablafire_widgets {

		public static $instance = null;
		public $plugin_name = 'nablafire_widgets';

		// Init. Calls constructor.
		public static function init() {
		    $class = __CLASS__;
	        new $class;
	    }

		// Constructor 
	    function __construct() {

			// Define path and uri for plugin
			if (!defined('NABLAFIRE_WIDGETS_PATH')) {
				define( 'NABLAFIRE_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
			} 
			if (!defined('NABLAFIRE_WIDGETS_URI')) {
				define( 'NABLAFIRE_WIDGETS_URI', plugin_dir_url( __FILE__ ) );
			} 

			// Widget Defaults
			$defaults_path = NABLAFIRE_WIDGETS_PATH . 'inc/widgets/js/json/plugin-options-table.json';
			$defaults_json = $this->file_local_contents($defaults_path);
			$this->defaults = json_decode($defaults_json, true);
			if($this->defaults === null){ echo "JSON Error. Check Options Table"; die(); }
	
			// Data and Font control  
			$utils_path = NABLAFIRE_WIDGETS_PATH . 'inc/nablafire-utils/plugin-utils/';
			$utils_uri  = NABLAFIRE_WIDGETS_URI  . 'inc/nablafire-utils/plugin-utils/';
			$this->utils = new Nablafire_Plugin_Utils($utils_path, $utils_uri); 

			// Load Scripts
		    add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ));
		}

		// Method to return local file with output buffer (for JSON)
		function file_local_contents($_file){
			ob_start(); 
			include($_file);
			$_data .= ob_get_contents();
			ob_end_clean();
			return $_data;
		}

		// Load all needed css and js for backend
		function load_admin_scripts() { 
      	 	wp_enqueue_style(
      	 		"nablafire-widget-styles-admin", 
      	 		NABLAFIRE_WIDGETS_URI . "admin/css/nablafire-widget-styles-admin.css"
      	 	);
	        wp_enqueue_script(
	        	"nablafire-widget-scripts-admin", 
	        	NABLAFIRE_WIDGETS_URI . "admin/js/nablafire-widget-scripts-admin.js", 
	        	array( 'jquery' ),'1.0.0', 
	        	true 
			);
		}

		// Getters and Setters
		public static function get_instance() {				
			if ( self::$instance==null ) {
				self::$instance = new nablafire_widgets();
			}
			return self::$instance;
		}
			
		public function add($name) {
			return register_widget($name);
		}
			
		public function remove($name) {
			return unregister_widget($name);
		}
			
		public function replace($name){				
			unregister_widget($name);
			return register_widget($name);
		}
	} // END CONTAINER CLASS
} // All remaining code forms a kind of functions.php for the plugin.  


// Initialize instance of the container class if it does not exist. Otherwise 
// simply return the pointer. This is handled by get_instance()
if ( ! function_exists( 'instantiate_plugin' ) ) {		
	function instantiate_plugin() {			
		$plugin = nablafire_widgets::get_instance();
		return $plugin;
	}
}

// Automatically load widget classes when they are required with include
// statements. The files are to be developed in the 'widgets' directory.	
spl_autoload_register( 'nabla_widgets_register_classes' );
function nabla_widgets_register_classes( $class_name ) {
	
	// An array of class slugs we will check against 
	$class_slugs = array(
		'nablafire' => false,
		'Nablafire' => false,
	);
	// Otherwise scan these directories for the class file 			
	$array_paths = array(   
       	'inc/widgets/',
       	'inc/nablafire-utils/data-utils/',
     	'inc/nablafire-utils/plugin-utils/',
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
       			$class_path = NABLAFIRE_WIDGETS_PATH . $path . $class_file . '.php';
				if ( file_exists( $class_path ) ) {include $class_path; return;}
			} 
		}
	}
} // Close autoloader

// run the plugin and register widgets
add_action( 'plugins_loaded', array( 'nablafire_widgets', 'init' ));
	
// Title Widget
add_action( 'widgets_init', function(){
	$plugin = instantiate_plugin();  	
	$widget = new nablafire_fancy_title( 
		$plugin->defaults['fancy_title'], 
		$plugin->utils->font_gen 
	);
    register_widget( $widget );
});

// Text Widget
add_action( 'widgets_init', function(){
	$plugin = instantiate_plugin();  	
	$widget = new nablafire_fancy_text( 
		$plugin->defaults['fancy_text'], 
		$plugin->utils->font_gen 
	);
    register_widget( $widget );
});

// Image Widget
add_action( 'widgets_init', function(){
	$plugin = instantiate_plugin();  	
	$widget = new nablafire_fancy_image( 
		$plugin->defaults['fancy_image'], 
		$plugin->utils->font_gen 
	);
    register_widget( $widget );
});

// Caption Widget
add_action( 'widgets_init', function(){
	$plugin = instantiate_plugin();  	
	$widget = new nablafire_fancy_caption( 
		$plugin->defaults['fancy_caption'], 
		$plugin->utils->font_gen 
	);
    register_widget( $widget );
});

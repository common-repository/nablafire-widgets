<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nablafire_Widget_Font_Setting {
	
	function __construct( $data_group, $data_keys ){
		$this->label 		= $data_group['label']; 
		$this->description  = $data_group['desc'];

		// For form method
		$this->keys = array();
		foreach ($data_keys as $key => $sanitize) {
			$this->keys[$key] = $data_group['group'] . $key;
		}

		// For update method
		$this->callback_keys = array();
		foreach ($data_keys as $key => $sanitize) {
			$this->callback_keys[ $data_group['group'] . $key ] = $sanitize;
		}

	}

	public function get_keys(){
		return $this->keys;
	}
	
	public function get_callback_keys(){
		return $this->callback_keys;
	}
}
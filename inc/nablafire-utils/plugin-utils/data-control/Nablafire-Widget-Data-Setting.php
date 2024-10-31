<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nablafire_Widget_Data_Setting {
	
	function __construct( $data_group, $data_keys ){
		$this->label 		= $data_group['label']; 
		$this->color 		= $data_group['color'];
		$this->description  = $data_group['desc'];

		// For form methods
		$this->data_keys = array();
		foreach ($data_keys as $key => $data) {
			$this->data_keys[ $data_group['group'] . $key ] = $data;
		}

		// For update method
		$this->callback_keys = array();
		$this->callback_atts = array();
		foreach ($data_keys as $key => $array) {
			$this->callback_keys[ $data_group['group'] . $key ] = $array['sanitize'];
			$this->callback_atts[ $data_group['group'] . $key ] = 
				array_key_exists('atts', $array) ? $array['atts'] : false;
		}

	}

	public function get_keys(){
		return $this->keys;
	}
	
	public function get_callback_keys(){
		return $this->callback_keys;
	}

	public function get_callback_atts(){
		return $this->callback_atts;
	}

}
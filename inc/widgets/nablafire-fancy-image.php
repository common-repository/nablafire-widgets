<?php

class nablafire_fancy_image extends WP_Widget{

	function __construct($defaults, $font_gen){

		$widget_name 	= esc_html__("Fancy Image",'nablafire-widgets');
		$widget_options = array( 
			'description' => esc_html__("Fancy Image", 'nablafire-widgets')
		);
		$control_options = array();

		// Call WP_Widget::__construct ...
		parent:: __construct(false, $widget_name, $widget_options, $control_options);

		// Widget Defaults

		$this->settings = array();
		$this->defaults = $defaults;
		$this->font_gen = $font_gen;

		// Font Control
		$this->register_font = new Nablafire_Widget_Font_Control($this->font_gen);			
		$this->sanitize_font = new Nablafire_Widget_Font_Sanitize($this->font_gen, $this->defaults);

		// Data Control 
		$this->register_data = new Nablafire_Widget_Data_Control();
		$this->sanitize_data = new Nablafire_Widget_Data_Sanitize($this->defaults);

		// Build widget settings
		$this->widget_settings();

	} // Close constructor method

	function widget_settings(){


		///////////////////////////////////////////////////
		//	
		// "div_im_image"		: "",
		// "div_im_parallax"	: "",
		//
		$this->settings['div_image']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'div_im_',
				'label'		=> esc_html__('image Properties', 'nablafire-widgets'),
				'desc'		=> false,
				'color'		=> 'blue'	
			),
			array(
				'image'		=> array(
					'type'		=> 'image',
					'label'		=> esc_html__('Select Image', 'nablafire-widgets'),
					'sanitize'	=> array('sanitize_data', 'sanitize_image'),
				),
				'shift'	=> array(
					'type'	=> 'number',
					'label'	=> esc_html__('Image Displacement (%)', 'nablafire-widgets'),
					'desc'	=> __('Shift the image horizontally <em>(0-100%)</em> within the widget container? <strong>(static image)</strong>', 'nablafire-widgets'),
					'sanitize'	=> array('sanitize_data', 'sanitize_number_range'),
					'atts'		=> array('min'=>0, 'max'=>100),	
				),
				'contain'	=> array(
					'type'	=> 'checkbox',
					'label'	=> esc_html__('Contain Image?', 'nablafire-widgets'), 
					'desc'	=> __('Contain the image to its div by applying the needed horizontal and vertical scaling? <strong>(static image)</strong>.', 'nablafire-widgets'),

					'sanitize' => array('sanitize_data', 'sanitize_pass'),
				),
				'parallax'	=> array(
					'type'	=> 'checkbox',
					'label'	=> esc_html__('Parallax Effect?', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_pass'),
				)

		) );

		///////////////////////////////////////////////////
		//	
		// "div_mn_padding"		: "10px",
		// "div_mn_height"		: "0",
		// "div_mn_color"		: "#fff",
		//
		$this->settings['div_styles']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'div_mn_',
				'label'		=> esc_html__('Layout Properties', 'nablafire-widgets'),
				'desc'		=> false,
				'color'		=> 'blue'
			),	
			array(
				'color'	=> array(
					'type'	=> 'color',
					'label'	=> esc_html__('Background Color (rgba)', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_alpha_color'),
					'atts'	=> $this->defaults['div_mn_color']
				), 
				'padding' 	=> array(
					'type'	=> 'text',
					'label' => esc_html__('Padding (CSS Shorthand)', 'nablafire-widgets'), 
					'sanitize' 	=> array('sanitize_data', 'sanitize_css_padding_shorthand'),
				),
				'height'	=> array(
					'type'	=> 'text',
					'label'	=> esc_html__('Fixed Height? (CSS Units)', 'nablafire-widgets'),
					'desc'	=> esc_html__('This setting allows you to set a fixed height for your image. If this value is set to zero(0), then your image will expand to match the height of its container.'),
					'sanitize' 	=> array('sanitize_data', 'sanitize_css_unit_value_auto'),	
				)
		) );

		///////////////////////////////////////////////////
		//
		// "div_bd_radius"		: "2px", 
		// "div_bd_size"		: "2px", 
		// "div_bd_color"		: "#aaa",
		// "div_bd_show"		: "1"
		//
		$this->settings['div_border']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'div_bd_',
				'label'		=> false,
				'desc'		=> false,
				'color'		=> 'yellow'
			),	
			array(
				'show'		=> array(
					'type'	=> 'checkbox',
					'label'	=> esc_html__('Show Border Elements?', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_pass'),
				),
				'inset'		=> array(
					'type'	=> 'checkbox',
					'label'	=> esc_html__('Inset Border?', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_pass'),
				),
				'radius'	=> array(
					'type'	=> 'text',
					'label' => esc_html__('Border Radius (CSS Units)', 'nablafire-widgets'), 
					'desc'	=> esc_html__('Border radius will always be applied. Set border radius to 0px for square corners' ,'nablafire-widgets'),
					'sanitize' 	=> array('sanitize_data', 'sanitize_css_unit_value'),
				),
				'size'		=> array(
					'type'	=> 'text',
					'label' => esc_html__('Border Size (CSS Units)', 'nablafire-widgets'), 
					'sanitize' 	=> array('sanitize_data', 'sanitize_css_unit_value'),				
				),
				'fade'		=> array(
					'type'	=> 'text',
					'label' => esc_html__('Border Fade (CSS Units)', 'nablafire-widgets'), 
					'sanitize' 	=> array('sanitize_data', 'sanitize_css_unit_value'),				
				),
				'color'		=> array(
					'type'	=> 'color',
					'label'	=> esc_html__('Border Color (rgba)', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_alpha_color'),
					'atts'	=> $this->defaults['div_bd_color']
				)
		) );	

	}

	function form($instance){

		// Parse defaults into instance
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Create a local copy of the instance variables
		$options  = array();
		foreach ($this->defaults as $key => $default) { 
			$options[$key] = $instance[$key]; 
		} // Close PHP ---> Begin HTML backend output ?>


		<?php // IMAGE ?>
	   
	    <div class="nabla-widget-admin-fields">
        <h3 class="nabla-widget-admin-toggle nabla-widget-admin-toggle-1">
	  		<?php _e('Image Settings', 'nabla-widgets' ); ?></h3>
    	<div class="nabla-widget-admin-field nabla-widget-admin-field-1">

    	<?php 
    	$this->register_data->control($this, $this->settings['div_image'], $options, $this->defaults); 
    	?>

		</div>
	    </div>	
		
			
	    <?php // DIV ?>

	    <div class="nabla-widget-admin-fields">
        <h3 class="nabla-widget-admin-toggle nabla-widget-admin-toggle-2">
	  		<?php _e('Div Settings', 'nabla-widgets' ); ?></h3>
    	<div class="nabla-widget-admin-field nabla-widget-admin-field-2">

    	<?php 	
		$this->register_data->control($this, $this->settings['div_styles'], $options, $this->defaults); 
		$this->register_data->control($this, $this->settings['div_border'], $options, $this->defaults); 
		?>

		</div>
	    </div>	
		
    <?php } // Close Backend HTML generation (form function)

	// Update Method
	function update($new_instance, $old_instance) {
	
		$instance = $old_instance;
	
		$_data  = array(
			'div_image',
			'div_styles',
			'div_border'
		);
		///////////////////////////////////////////////////////////
		// Update Data Fields
		foreach ( $_data as $_ => $_key ) {
			$keys = $this->settings[ $_key ]->get_callback_keys();
			$atts = $this->settings[ $_key ]->get_callback_atts();  
			foreach($keys as $key => $sanitize){					
			$instance[$key] = ( $atts[ $key ] !== false) ? 
				$this->$sanitize[0]->$sanitize[1]( $new_instance, $key, $atts[$key] ) :
				$this->$sanitize[0]->$sanitize[1]( $new_instance, $key );
			}
		}

		return $instance;
	}


	// This method controls what we see on the frontend ... 
	// This method overloads WP_widget::widget();	
	function widget($args, $instance) {

		extract( $args ); $options = array();
		foreach ($this->defaults as $key => $default) {
			$options[$key] = ( isset($instance[$key] ) ? $instance[$key] : $default );
		} 

		// Process Checkboxes
		$checkboxes = array(
			'div_im_parallax',
			'div_bd_show',
			'div_bd_inset',
		);
		foreach ($checkboxes as $_ => $key) {
			$options[ $key ] = $instance[ $key ]  ? true : false;
		}
		// Close PHP ---> Begin HTML backend output	?>		


		<?php echo $this->write_stylesheet($options); ?>
		<?php echo $before_widget; ?>

		<div class="<?php echo esc_html(str_replace('_', '-', $this->id));?>">
			<div class="bg-<?php echo esc_html(str_replace('_', '-', $this->id));?>">
			</div>
		</div>

		<?php echo $after_widget; ?>

	<?php } //Open PHP - Close WP_Widget::widget() function

	

	function write_stylesheet($instance){

		$_css = new Nablafire_CSS_Autogen(); $css = '';

		$css .= $_css->begin_rule( '.' . str_replace('_', '-', $this->id) );
			$css .= $_css->add_rule('background-color',  $instance['div_mn_color']);
			$css .= $_css->add_rule('padding'		, $instance['div_mn_padding']);
			$css .= $_css->add_rule('border-radius'	, $instance['div_bd_radius']);
			$css .= $_css->add_rule('box-sizing'	, 'border-box');

			if ( strcmp( $instance['div_mn_height'] , "0") == 0 ){
				$css .= $_css->add_rule('height'			, '100%');			
			}
			else {
				$css .= $_css->add_rule('height'			, $instance['div_mn_height']);	
			}

		$css .=	$_css->end_rule();	

		if ( $instance['div_im_parallax'] === true ){

			$css .= $_css->begin_rule( '.' . str_replace('_', '-', $this->id) . ' ' .
									   '.bg-' . str_replace('_', '-', $this->id) );	
				$css .= $_css->add_rule('position'				, 'relative');
				$css .= $_css->add_rule('background-attachment'	, 'fixed');
				$css .= $_css->add_rule('background-position'	, '100%');
				$css .= $_css->add_rule('background-size'		, 'cover');
			$css .=	$_css->end_rule();	

			$css .= $_css->begin_rule( '.bg-' . str_replace('_', '-', $this->id) );
				$css .= $_css->add_rule('border-radius'	, $instance['div_bd_radius']);
				$css .= $_css->add_background_image($instance['div_im_image']);


				if ( strcmp( $instance['div_mn_height'] , "0") == 0 ){
					$css .= $_css->add_rule('height'			, 'inherit');
				}
				else {
					$css .= $_css->add_rule('height'			, '100%');	
				}

				if ( $instance['div_bd_show'] === true ){
				
					$inset = ($instance['div_bd_inset']) ? ' inset' : '';
					$css .= $_css->add_rule(
						'box-shadow', '0 0 ' . 
						$instance['div_bd_fade'] . ' ' . 
						$instance['div_bd_size'] . ' ' . 
						$instance['div_bd_color']. $inset);

				}
			$css .=	$_css->end_rule();			
		}		
		else {
			$css .= $_css->begin_rule( '.bg-' . str_replace('_', '-', $this->id) );	
				$css .= $_css->add_background_image($instance['div_im_image']);
				if ( $instance['div_im_contain'] ){
					$css .= $_css->add_rule('background-size' 	, '100% 100%');					
				}
				else{
					$css .= $_css->add_rule('background-size' 	, 'auto 100%');					
				}
				$css .= $_css->add_rule('background-position', $instance['div_im_shift'].'%');
				$css .= $_css->add_rule('border-radius'		, $instance['div_bd_radius']);

				if ( strcmp( $instance['div_mn_height'] , "0") == 0 ){
					$css .= $_css->add_rule('height'			, 'inherit');
				}
				else {
					$css .= $_css->add_rule('height'			, '100%');	
				}

				if ( $instance['div_bd_show'] === true ){
					$inset = ($instance['div_bd_inset']) ? ' inset' : '';
					$css .= $_css->add_rule(
						'box-shadow', '0 0 ' . 
						$instance['div_bd_fade'] . ' ' . 
						$instance['div_bd_size'] . ' ' . 
						$instance['div_bd_color']. $inset);
				}

			$css .=	$_css->end_rule();			
		}
		
		$css  = "<style>" . $_css->minify($css) . "</style>\n";
		return $css;

	}

}// Close Widget Class

<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class nablafire_fancy_title extends WP_Widget {

	// Our widget constructor will call the WP_Widget parent class 
	// constructor (i.e. inheritance). For Reference 
	// -----------------------------------------------------------
	// WP_Widget::__construct( string $id_base, 
	//  					   string $widget_name, 
	//                         array  $widget_options  = array(), 
	//                         array  $control_options = array());
	// -----------------------------------------------------------
	function __construct($defaults, $font_gen) {

		$widget_name 	= esc_html__("Fancy Title",'nablafire-widgets');
		$widget_options = array( 
			'description' => esc_html__("Fancy Title Area", 'nablafire-widgets')
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
	}

	function widget_settings(){


		///////////////////////////////////////////////////
		//
		// "title_mn_text"		: "",
		// "title_mn_align"		: "",
		//
		$this->settings['title_data']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'title_mn_',
				'label'		=> false,
				'desc'		=> false,
				'color'		=> 'green'
			),	
			array(
				'text'	=> array(
					'type'	=> 'text',
					'label'	=> esc_html__('Text', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_kses'),	
				), 
				'align' 	=> array(
					'type'	=> 'select',
					'label' => esc_html__('Alignment', 'nablafire-widgets'), 
					'sanitize' 	=> array('sanitize_data', 'sanitize_dropdown'),
					'atts'	=> array('left', 'center', 'right', 'justify')
				) 
		) );

		///////////////////////////////////////////////////
		//
		// "title_font_fam"		: "Montserrat",	
		// "title_font_var"		: "regular",
		// "title_font_size"	: "24",
		// "title_font_color"	: "#000",	
		//
		$this->settings['title_font']  = new Nablafire_Widget_Font_Setting(
			array(
				'group'		=> 'title_',
				'label'		=> false,
				'desc'		=> false,
			),	
			array(
				'font_fam' 	=> array('sanitize_font' , 'sanitize_font_fam'),
				'font_var' 	=> array('sanitize_font' , 'sanitize_font_var'),
				'font_size' => array('sanitize_font' , 'sanitize_font_size'),
				'font_color'=> array('sanitize_font' , 'sanitize_font_color')
		) );
		
		///////////////////////////////////////////////////
		//
		// "div_mn_color"	   	: "#fff",
		// "div_mn_padding"  	: "10px",
		// "div_mn_margin"   	: "10px",
		//
		$this->settings['div_styles']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'div_mn_',
				'label'		=> esc_html__('Layout Properties', 'nablafire-widgets'),
				'desc'		=> esc_html__('These values apply to the fancy title container. Note that fancy titles will expand in width to fill their container', 'nablafire-widgets'),
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
		) );

		///////////////////////////////////////////////////
		//
		// "div_bd_radius"  	: "2px", 
		// "div_bd_size"  		: "2px", 
		// "div_bd_color"		: "#fff"
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

	} // Close Settings

	// Backend: Overload WP_widget::form();
	function form($instance) {

		// Parse defaults into instance
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Create a local copy of the instance variables
		$options  = array();
		foreach ($this->defaults as $key => $default) { 
			$options[$key] = $instance[$key]; 
		} // Close PHP ---> Begin HTML backend output ?>

		<?php // TITLE ?>

	    <div class="nabla-widget-admin-fields">
        <h3 class="nabla-widget-admin-toggle nabla-widget-admin-toggle-1">
	  		<?php _e('Title Settings', 'nabla-widgets' ); ?></h3>
    	<div class="nabla-widget-admin-field nabla-widget-admin-field-1">

        <?php 
       	$this->register_data->control($this, $this->settings['title_data'], $options, $this->defaults); 
        $this->register_font->control($this, $this->settings['title_font'], $options, $this->defaults); 
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

        <?php //Open PHP
	}// Close WP_Widget::form function

	// Update Method (Calls Sanitize Callbacks)
	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
	
		$_data  = array(
			'title_data',
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


		$_font = array(
			'title_font',
		);
		///////////////////////////////////////////////////////////
		// Update Font Fields
		foreach ( $_font as $_ => $_key ) {
			$keys = $this->settings[ $_key ]->get_callback_keys();
			foreach($keys as $key => $sanitize){
				$instance[$key] = $this->$sanitize[0]->$sanitize[1]( $new_instance, $key );
			}		
		}
		return $instance;	
	}

	// Frontend: Overload WP_widget::widget();
	function widget($args, $instance) {
		
		extract( $args ); $options = array();
		foreach ($this->defaults as $key => $default) {
			$options[$key] = ( isset($instance[$key] ) ? $instance[$key] : $default );
		} 

		// Process Checkboxes
		$checkboxes = array(
			'div_bd_show',
			'div_bd_inset',
		);
		foreach ($checkboxes as $_ => $key) {
			$options[ $key ] = $instance[ $key ]  ? true : false;
		} // Close PHP ---> Begin HTML fontend output ?>	

		<?php echo $this->write_stylesheet($options); ?>
		<?php echo $before_widget; ?>

		<div class="wrap-<?php echo esc_html(str_replace('_', '-', $this->id));?>">
			<div class="div-<?php echo esc_html(str_replace('_', '-', $this->id));?>">
				<?php $this->widget_title_template($options); ?>
			</div>
		</div>


		<?php echo $after_widget; ?>
    	<?php //Open PHP
	} // Close WP_Widget::widget() function


	function widget_title_template($options){ ?>

		<?php if( $options['title_mn_text'] ): ?>
			<div class="p-<?php echo esc_html(str_replace('_', '-', $this->id));?>">
			<?php echo $this->sanitize_data->sanitize_kses( $options, 'title_mn_text' );?></div>
		<?php endif; ?>	
	
	<?php }

	function write_stylesheet($instance){

		$_css = new Nablafire_CSS_Autogen(); $css = '';
		/////////////////////////////////////////////////////////////
		$css .= $_css->begin_rule('.wrap-' . str_replace('_', '-', $this->id) );
			$css .= $_css->add_rule('padding'			, $instance['div_mn_padding']);
			$css .= $_css->add_rule('box-sizing'		, 'border-box');
		$css .= $_css->end_rule();

		$css .= $_css->begin_rule('.div-' . str_replace('_', '-', $this->id) );

			$css .= $_css->add_rule('background-color'	, $instance['div_mn_color']);
			$css .= $_css->add_rule('border-radius'	, $instance['div_bd_radius']);
			if ( $instance['div_bd_show'] === true ){
				$inset = ($instance['div_bd_inset']) ? ' inset' : '';
				$css .= $_css->add_rule(
					'box-shadow', '0 0 ' . 
					$instance['div_bd_fade'] . ' ' . 
					$instance['div_bd_size'] . ' ' . 
					$instance['div_bd_color']. $inset);
			}
		$css .= $_css->end_rule();
	
		$css .= $_css->begin_rule('.p-' . str_replace('_', '-', $this->id) );
			$css .= $_css->add_rule('text-align'	, $instance['title_mn_align']);
			$css .= $_css->_literal($this->font_gen->css_fontfamily($instance['title_font_fam']));
			$css .= $_css->_literal($this->font_gen->css_fontstyle($instance['title_font_var']));
			$css .= $_css->add_rule('font-size'		, $instance['title_font_size'] . 'px');
			$css .= $_css->add_rule('color'			, $instance['title_font_color']);
			$css .= $_css->add_rule('line-height'	, '100%');
			$css .= $_css->add_rule('margin'		, '0');
		$css .= $_css->end_rule();
		/////////////////////////////////////////////////////////////
		$css  = "<style>" . $_css->minify($css) . "</style>\n";
		$css .= $this->font_gen->link_single(
			$instance['title_font_fam'],$instance['title_font_var']);
	
		return $css;
	}


}// Close Widget Class
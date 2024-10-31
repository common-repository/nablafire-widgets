<?php

class nablafire_fancy_caption extends WP_Widget {

	function __construct($defaults, $font_gen){

		$widget_name 	= esc_html__("Fancy Caption",'nablafire-widgets');
		$widget_options = array( 
			'description' => esc_html__("Fancy caption with image and text", 'nablafire-widgets')
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
		// "accent_ic_image"	: "",
		// "accent_ic_size"		: "30",
		// "accent_ac_size"		: "120",
		// "accent_ac_weight"	: "12",
		// "accent_ac_fg_color"	: "#000",
		// "accent_ac_bg_color"	: "#fff",		
		//
		$this->settings['accent_data']  = new Nablafire_Widget_Data_Setting(

			array(
				'group'		=> 'accent_',
				'label'		=> esc_html__('Accent Settings', 'nablafire-widgets'),
				'desc'		=> false,
				'color'		=> 'red'
			),
			array(
				'ic_image'	=> array(
					'type'		=> 'image',
					'label'		=> esc_html__('Select Icon', 'nablafire-widgets'),
					'sanitize'	=> array('sanitize_data', 'sanitize_image'),
				),
				'ic_size'		=> array(
					'type'		=> 'number',
					'label'		=> esc_html__('Icon Size (px)', 'nablafire-widgets'),
					'sanitize'	=> array('sanitize_data', 'sanitize_number_range'),
					'atts'		=> array('min'=>10, 'max'=>60),
				),
				'ac_size'		=> array(
					'type'		=> 'number',
					'label'		=> esc_html__('Accent Size (px)', 'nablafire-widgets'),
					'sanitize'	=> array('sanitize_data', 'sanitize_number_range'),
					'atts'		=> array('min'=>60, 'max'=>150),
				),	
				'ac_weight'		=> array(
					'type'		=> 'number',
					'label'		=> esc_html__('Accent Size (px)', 'nablafire-widgets'),
					'sanitize'	=> array('sanitize_data', 'sanitize_number_range'),
					'atts'		=> array('min'=>5, 'max'=>25),
				),
				'ac_fg_color' 	=> array(
					'type'	=> 'color',
					'label'	=> esc_html__('Accent Color (rgba)', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_alpha_color'),
					'atts'	=> $this->defaults['accent_ac_fg_color']
				),
				'ac_hv_color' 	=> array(
					'type'	=> 'color',
					'label'	=> esc_html__('Hover Color (rgba)', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_alpha_color'),
					'atts'	=> $this->defaults['accent_ac_hv_color']
				),
				'ac_bg_color' 	=> array(
					'type'	=> 'color',
					'label'	=> esc_html__('Background Color (rgba)', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_alpha_color'),
					'atts'	=> $this->defaults['accent_ac_bg_color']
				), 
		) );

		///////////////////////////////////////////////////
		//
		// "title_mn_text"		: "Fancy Caption",
		//
		$this->settings['title_data']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'title_mn_',
				'label'		=> false,
				'desc'		=> false,
				'color'		=> 'green'
				),	
			array(
				'text'		=> array(
					'type'	=> 'text',
					'label'	=> esc_html__('Caption Text', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_text_field'),		
				),
		) ); 	

		///////////////////////////////////////////////////
		//
		// "title_font_fam"		: "Montserrat",	
		// "title_font_var"		: "regular",
		// "title_font_size"	: "16",
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
		// "text_mn_text"		: "",
		// "text_mn_align"		: "justify",
		//
		$this->settings['text_data']  = new Nablafire_Widget_Data_Setting(
			array(
				'group'		=> 'text_mn_',
				'label'		=> false,
				'desc'		=> false,
				'color'		=> 'orange'
			),	
			array(
				'text'		=> array(
					'type'	=> 'textarea',
					'label'	=> esc_html__('Text', 'nablafire-widgets'), 
					'sanitize' => array('sanitize_data', 'sanitize_kses'),		
				), 
				'align' 	=> array(
					'type'	=> 'select',
					'label' => esc_html__('Alignment', 'nablafire-widgets'), 
					'sanitize' 	=> array('sanitize_data', 'sanitize_dropdown'),
					'atts'	=> array('left', 'center', 'right', 'justify')
				),
				'padding' 	=> array(
					'type'	=> 'text',
					'label' => esc_html__('Padding (CSS Shorthand)', 'nablafire-widgets'), 
					'sanitize' 	=> array('sanitize_data', 'sanitize_css_padding_shorthand'),
				),
		) );

		///////////////////////////////////////////////////
		//
		// "text_font_fam"		: "Montserrat",	
		// "text_font_var"		: "regular",
		// "text_font_size"		: "16",
		// "text_font_color"	: "#000",
		//
		$this->settings['text_font']  = new Nablafire_Widget_Font_Setting(
			array(
				'group'		=> 'text_',
				'label'		=> false,
				'desc'		=> false,
			),	
			array(
				'font_fam' 	=> array('sanitize_font' , 'sanitize_font_fam'),
				'font_var' 	=> array('sanitize_font' , 'sanitize_font_var'),
				'font_size' => array('sanitize_font' , 'sanitize_font_size'),
				'font_color'=> array('sanitize_font' , 'sanitize_font_color')
		) );

	}

	// This method controls what we see on the backend ... 
	// This method overloads WP_widget::form();
	function form($instance) {

		// Parse defaults into instance
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Create a local copy of the instance variables
		$options  = array();
		foreach ($this->defaults as $key => $default) { 
			$options[$key] = $instance[$key]; 
		} // Close PHP ---> Begin HTML backend output ?>


		<?php // ACCENT ?>
		
		<div class="nabla-widget-admin-fields">
        <h3 class="nabla-widget-admin-toggle nabla-widget-admin-toggle-1">
	  		<?php _e('Accent Settings', 'nabla-widgets' ); ?></h3>
    	<div class="nabla-widget-admin-field nabla-widget-admin-field-1">

    	<?php	
       	$this->register_data->control($this, $this->settings['accent_data'], $options, $this->defaults); 
       	?>

    	</div>
	    </div>

		<?php // TITLE ?>
		
		<div class="nabla-widget-admin-fields">
        <h3 class="nabla-widget-admin-toggle nabla-widget-admin-toggle-2">
	  		<?php _e('Caption Settings', 'nabla-widgets' ); ?></h3>
    	<div class="nabla-widget-admin-field nabla-widget-admin-field-2">

    	<?php 
       	$this->register_data->control($this, $this->settings['title_data'], $options, $this->defaults); 
        $this->register_font->control($this, $this->settings['title_font'], $options, $this->defaults); 
      	?>

    	</div>
	    </div>

		<?php // TEXT ?>
		
		<div class="nabla-widget-admin-fields">
        <h3 class="nabla-widget-admin-toggle nabla-widget-admin-toggle-3">
	  		<?php _e('Text Settings', 'nabla-widgets' ); ?></h3>
    	<div class="nabla-widget-admin-field nabla-widget-admin-field-3">

    	<?php 	
    	$this->register_data->control($this, $this->settings['text_data'], $options, $this->defaults); 
		$this->register_font->control($this, $this->settings['text_font'], $options, $this->defaults); 
		?>

    	</div>
	    </div>

	<?php } // Close WP_Widget::form function

	// Update Method
	function update($new_instance, $old_instance) {
	
		$instance = $old_instance;
	
		$_data  = array(
			'accent_data',
			'title_data',
			'text_data',
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
			'text_font'
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

	// This method controls what we see on the frontend ... 
	// This method overloads WP_widget::widget();
	function widget($args, $instance) {

		extract( $args ); $options = array();
		foreach ($this->defaults as $key => $default) {
			$options[$key] = ( isset($instance[$key] ) ? $instance[$key] : $default );
		} 

	

		// Close PHP ---> Begin HTML backend output	?>		
		<?php $class = str_replace('_', '-', $this->id); ?>
		<?php echo $this->write_stylesheet($options, $class); ?>
		
		<?php echo $before_widget; ?>
		<div class="nablafire-fancy-caption">
		
			<!-- Image Field -->
			<?php if( $options['accent_ic_image'] ) : ?>
			<div class="nablafire-caption-wrap">

				<div class="image-<?php echo $class; ?>">
					<div class="circle-outer"></div>
	    			<div class="circle-inner"></div>
	    			<div class="icon-inner">
	    				<img class="icon-<?php echo $class; ?>" 
	    					 src="<?php echo esc_url( $options['accent_ic_image'] );?>">
	    			</div>
				</div>		
			</div>		
			<?php endif;?>

			<!-- Title Field -->
			<?php if( $options['title_mn_text'] ):?>
			<p class="title-<?php echo $class; ?>">
				<?php echo esc_html( $options['title_mn_text'] );?>
			</p>
			<?php endif; ?>

			<!-- Underline -->		
			<div class="ulw-<?php echo $class; ?>">
				<div class="ul-<?php echo $class; ?>"></div>
			</div>

			<!-- Text Field -->
			<?php if( $options['text_mn_text'] ):?>
			<p class="text-<?php echo $class; ?>" >
				<?php echo $options['text_mn_text']; ?>
			</p>
			<?php endif; ?>

		</div>
		<?php echo $after_widget; ?>
		
    <?php //Open PHP
	} // Close WP_Widget::widget() function

	function write_stylesheet($instance, $class){

		$_css = new Nablafire_CSS_Autogen(); $css = '';
		/////////////////////////////////////////////////////////////
		$css .= $_css->begin_rule('.nablafire-fancy-caption');
			$css .= $_css->add_rule('padding'	, '0px 20px');
			$css .= $_css->add_rule('box-sizing', 'border-box');
		$css .= $_css->end_rule();
		
		$css .= $_css->begin_rule('.nablafire-caption-wrap');
			$css .= $_css->add_rule('width'		, '100%');
		$css .= $_css->end_rule();

		$css .= $_css->begin_rule('.image-' . $class);
			$css .= $_css->add_rule('width'		, $instance['accent_ac_size'] . 'px');
			$css .= $_css->add_rule('height'	, $instance['accent_ac_size'] . 'px');
			$css .= $_css->add_rule('position'	, 'relative');
			$css .= $_css->add_rule('margin'	, '0 auto'); // Centering
		$css .= $_css->end_rule();	    

		$css .= $_css->begin_rule('.image-' . $class . ' > .circle-outer');
			$css .=	$_css->add_rule('background-color' 	, $instance['accent_ac_fg_color']);
			$css .= $_css->add_rule('border-radius'		, '50%');
			$css .= $_css->add_rule('position'	, 'absolute');
			$css .= $_css->add_rule('width'		, (string)( $instance['accent_ac_size'] - 10 ) . 'px');
			$css .= $_css->add_rule('height'	, (string)( $instance['accent_ac_size'] - 10 ) . 'px');
			$css .= $_css->add_rule('top'		, '5px');
			$css .= $_css->add_rule('left'		, '5px');
		$css .= $_css->end_rule();	    

		$css .= $_css->begin_rule('.image-' . $class . ' > .circle-inner');
			$css .=	$_css->add_rule('background-color' 	, $instance['accent_ac_bg_color']);
			$css .= $_css->add_rule('border-radius'		, '50%');
			$css .= $_css->add_rule('position'	, 'absolute');
			$css .= $_css->add_rule('width'		, 
				 (string)( $instance['accent_ac_size'] - 2*$instance['accent_ac_weight'] ) . 'px' );
			$css .= $_css->add_rule('height'	, 
				 (string)( $instance['accent_ac_size'] - 2*$instance['accent_ac_weight'] ) . 'px' );
			$css .= $_css->add_rule('top'		, (string)($instance['accent_ac_weight'] ) . 'px' );
			$css .= $_css->add_rule('left'		, (string)($instance['accent_ac_weight'] ) . 'px' );
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.image-' . $class . '> .icon-inner');
			$css .= $_css->add_rule('position'	, 'absolute');
			$css .= $_css->add_rule('text-align', 'center');
    		$css .= $_css->add_rule('width'		, $instance['accent_ac_size'] . 'px');
			$css .= $_css->add_rule('height'	, $instance['accent_ac_size'] . 'px');	
			$css .= $_css->add_rule('padding-top', 	
				  (string)( $instance['accent_ac_size']/2 - $instance['accent_ic_size']/2 ) . 'px' );
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.icon-' . $class);
			$css .= $_css->add_rule('max-height', $instance['accent_ic_size'] . 'px');
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.ulw-' . $class);
			$css .= $_css->add_rule('width'			, '100%');
			$css .= $_css->add_rule('padding-top'	, '10px');
			$css .= $_css->add_rule('padding-bottom', '10px');

		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.ul-' . $class);
			$css .=	$_css->add_rule('background-color' 	, $instance['accent_ac_fg_color']);
			$css .= $_css->add_rule('width'		, '50%');
			$css .= $_css->add_rule('height'	, '3px');
			$css .= $_css->add_rule('margin'	, '0 auto');
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.title-' . $class);
			$css .= $_css->add_rule('text-align'	, 'center');
			$css .= $_css->add_rule('padding'		, '10px 0px');
			$css .= $_css->add_rule('margin'		, '0');
			$css .= $_css->_literal($this->font_gen->css_fontfamily($instance['title_font_fam']));
			$css .= $_css->_literal($this->font_gen->css_fontstyle($instance['title_font_var']));
			$css .= $_css->add_rule('font-size'		, $instance['title_font_size'] . 'px');
			$css .= $_css->add_rule('color'			, $instance['title_font_color']);
			$css .= $_css->add_rule('line-height'	, '100%');		
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.text-' . $class);
			$css .= $_css->add_rule('text-align'	, $instance['text_mn_align']);
			$css .= $_css->add_rule('padding'		, $instance['text_mn_padding']);
			$css .= $_css->add_rule('margin'		, '0');
			$css .= $_css->_literal($this->font_gen->css_fontfamily($instance['text_font_fam']));
			$css .= $_css->_literal($this->font_gen->css_fontstyle($instance['text_font_var']));
			$css .= $_css->add_rule('font-size'		, $instance['text_font_size'] . 'px');
			$css .= $_css->add_rule('color'			, $instance['text_font_color']);
			$css .= $_css->add_rule('line-height'	, '150%');
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.text-' . $class . ' a');
			$css .= $_css->add_rule('color'			, $instance['accent_ac_fg_color']);
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.text-' . $class . ' a:hover');
			$css .= $_css->add_rule('color'			, $instance['accent_ac_hv_color']);
			$css .= $_css->browser_rules('transition', 'color 250ms ease-out');
		$css .= $_css->end_rule();	

		$css .= $_css->begin_rule('.text-' . $class . ' span');
			$css .= $_css->add_rule('color'			, $instance['accent_ac_fg_color']);
			$css .= $_css->add_rule('display'		, 'inline');
			$css .= $_css->add_rule('line-height'	, 'inherit');			
		$css .= $_css->end_rule();	


		/////////////////////////////////////////////////////////////
		$css  = "<style>" . $_css->minify($css) . "</style>\n";
		$css .= $this->font_gen->link_single(
			$instance['title_font_fam'],$instance['title_font_var']);
		$css .= $this->font_gen->link_single(
			$instance['text_font_fam'],$instance['text_font_var']);
	
		return $css;
	}
}// Close Widget Class
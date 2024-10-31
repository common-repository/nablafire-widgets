<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nablafire_Widget_Font_Control {

	function __construct($font_gen){		
		$this->font_gen = $font_gen;
	}

	public function control($widget, $setting, $options, $defaults) { ?>

		  <?php $keys = $setting->get_keys(); ?>

        <div class="widget-font-control"
        	   style="padding:10px; 
                    margin: 5px 0px; 
                    border: 2px solid #BBBBBB; 
                    background-color:#efd3ff">
    
        <?php if ($setting->label): ?> 
	       	<span><p><strong><?php echo $setting->label; ?></strong></p></span>
       	<?php endif; ?>
        
        <?php if ($setting->description): ?> 
       		<span><p><?php echo $setting->description; ?></p></span>
       	<?php endif; ?>
       	

	    <?php if ( array_key_exists('font_fam', $keys) ):?>
			<span class="widefat">
           		<strong><?php echo __('Font Family', 'nablafire-font-control') ?></strong>
    	   	</span>
    	 	
    	 	<p>
    	 	<select class="widefat widget-font-family-control"
			   		  id="<?php echo esc_attr($widget->get_field_id($keys['font_fam'])); ?>"
        			name="<?php echo esc_attr($widget->get_field_name($keys['font_fam'])); ?>">       			
        		<?php foreach($this->font_gen->get_fontlist() as $font_fam): ?>       		
        			<option value="<?php echo esc_attr($font_fam); ?>"  
        			<?php if($options[$keys['font_fam']] == $font_fam) { echo 'selected="selected"'; } ?> >
        			<?php echo esc_attr($font_fam) ; ?>                    
            		</option>
				<?php endforeach; ?>		          	
	        </select>		
    		</p>
    	<?php endif; ?>
	
	    <?php if ( array_key_exists('font_var', $keys) ):?>
          <span class="widefat">
           		<strong><?php echo __('Font Variant', 'nablafire-font-control') ?></strong>
    	   	</span>

         	<p>  
            <select class="widefat widget-font-variant-control"
            		id="<?php echo esc_attr($widget->get_field_id($keys['font_var'])); ?>"
					name="<?php echo esc_attr($widget->get_field_name($keys['font_var'])); ?>">            
            	<?php foreach($this->font_gen->get_variants($options[$keys['font_fam']]) as $font_var): ?>
                    <option value="<?php echo esc_attr($font_var); ?>" 
					<?php if($options[$keys['font_var']] == $font_var) { echo 'selected="selected"'; } ?> >
                    <?php echo esc_attr($font_var) ; ?>                                   
                    </option>
                <?php endforeach; ?>
            </select>
            </p>
        <?php endif; ?>

		<?php if (array_key_exists('font_size', $keys) ):?>
			<span class="widefat">
				<strong><?php echo __('Font Size (px)', 'nablafire-font-control') ?></strong>
    	   	</span>

    	   	<p>
    	   	<input class="widefat widget-font-size-control" 
					name="<?php echo esc_attr( $widget->get_field_name($keys['font_size'] )); ?>"
					type="number" min="8" max="400" step="1"   
					value="<?php echo esc_attr($options[ $keys['font_size']] ); ?>" />
            </p>
		<?php endif; ?>	

		<?php if ( array_key_exists('font_color', $keys) ):?>
			    <span class="widefat">
				  <strong><?php echo __('Font Color (rgba)', 'nablafire-font-control') ?></strong>
    	   	</span>

    	   	<p>
    	   	<input class="color-picker widget-color-picker widget-font-color-control"
					       name="<?php echo esc_attr( $widget->get_field_name($keys['font_color'])); ?>"
	               type="text" 
					       value="<?php echo esc_attr($options[ $keys['font_color']] ); ?>"  
                 data-alpha="true"
               	 data-default-color="<?php echo esc_attr($defaults[ $keys['font_color']]);?>" />
    	   	</p>
		<?php endif; ?>	
	  
    </div>
		
	<?php }

}
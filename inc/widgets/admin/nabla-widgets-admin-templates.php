<?php 

class nabla_widgets_admin_templates
{
	function __construct($domain='default')
	{
		$this->domain = $domain;
	}

	function admin_input_field($widget, $key, $value, $label, $before='', $after=''){ ?>
		<p>
        	<label for="<?php echo esc_attr($widget->get_field_id($key)); ?>">
        		<?php esc_html_e($label,$this->domain); ?></label>
            <?php if($before != ''):?>
                </br><span><em><?php _e( "$before", 'nabla-front-page' ); ?></em></span> 
            <?php endif; ?>    
    		<input class="widefat" 
    				id="<?php echo esc_attr($widget->get_field_id($key)); ?>" 
    				type="text"
    				name="<?php echo esc_attr($widget->get_field_name($key)); ?>"  
    				value="<?php echo esc_attr($value); ?>" />
            <?php if($after != ''):?>
                <span><em><?php _e( "$after", 'nabla-front-page' ); ?></em></span> 
            <?php endif; ?>            
        </p>
    <?php }

    function admin_text_field($widget, $key, $value, $label){ ?>
        
        <p>
            <label for="<?php echo esc_attr($widget->get_field_id($key)); ?>">
                <?php  esc_html_e($label,$this->domain); ?></label> 
            <textarea class="widefat" 
                       id="<?php echo esc_attr($widget->get_field_id($key)); ?>" 
                       name="<?php echo esc_attr($widget->get_field_name($key)); ?>"
                       rows="10" cols="15"><?php echo esc_html($value); ?></textarea>
        </p>

    <?php }

    function admin_drop_down($widget, $key, $value, $label, $options){ ?>
        <p>
            <label for="<?php echo esc_attr($widget->get_field_id($key)); ?>">
                <?php esc_html_e($label,$this->domain); ?></label>
                
                <select class="widefat" 
                        name="<?php echo esc_attr($widget->get_field_name($key)); ?>">
                        <?php foreach($options as $_option) { ?>
                        <option value="<?php echo esc_attr($_option); ?>" 
                            <?php if($value == $_option) { echo 'selected="selected"'; } ?>>
                            <?php echo esc_html($_option); ?>
                        </option>
                        <?php } ?>
                </select>        
        </p>
    <?php }

    function admin_number_field($widget, $key, $value, $label, $min=0, $max=0, $step=0){?>
    
        <p>
            <label for="<?php echo esc_attr($widget->get_field_id($key)); ?>">
                <?php esc_html_e($label,$this->domain); ?></label>
            <input class="widefat" 
                    id="<?php echo esc_attr($widget->get_field_id($key)); ?>" 
                    type="number" 
                    <?php if($step != 0):?>
                        min="<?php echo esc_attr($min)?>" 
                        max="<?php echo esc_attr($max)?>"
                        step="<?php echo esc_attr($step)?>" 
                    <?php endif; ?>
                    name="<?php echo esc_attr($widget->get_field_name($key)); ?>" 
                    value="<?php echo esc_attr($value); ?>" />
        </p>
    <?php }


    function admin_color_field($widget, $key, $value, $label){?>

        <p>
            <label for="<?php echo esc_attr($widget->get_field_id( $key )); ?>">
                <?php esc_html_e( $label, $this->domain ); ?></label><br>
            <input class="color-picker" 
                    id="<?php echo esc_attr($widget->get_field_id( $key )); ?>" 
                    type="text" 
                    name="<?php echo esc_attr($widget->get_field_name( $key )); ?>"
                    value="<?php echo esc_attr($value); ?>" 
                    data-default-color="#000" />
        </p>
       
    <?php }

    function admin_image_field($widget, $key, $value, $label){ ?>

        <p>
            <label for="<?php echo esc_attr($widget->get_field_id( $key )); ?>">
                <?php esc_html_e( $label, $this->domain ); ?></label>          
            <input class="widefat upload-field" 
                    id="<?php echo esc_attr($widget->get_field_id($key)); ?>"                   
                    type="text" 
                    name="<?php echo esc_attr($widget->get_field_name($key)); ?>"
                    value="<?php echo esc_attr($value); ?>" />           
            <span class="action upload upload-button button button-primary"
                    id="<?php echo esc_attr($widget->get_field_id($key)); ?>_button"                    
                    style="margin-top:10px;">
                    <?php esc_html_e("Choose File",$this->domain);?>
            </span>
        </p>

    <?php }

    function admin_checkbox($widget, $key, $value, $label, $t){ ?>

        <p>
            <label for="<?php echo esc_attr($widget->get_field_id( $key )); ?>">
                <?php esc_html_e( $label, $this->domain ); ?></label>          
            <input class="checkbox" 
                    id="<?php echo $widget->get_field_id( $key ); ?>"
                    name="<?php echo $widget->get_field_name( $key ); ?>" 
                    type="checkbox" <?php checked( $t[ $key ], 'on' ); ?> ?>  
        </p>

    <?php }

}


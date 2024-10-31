<?php


  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  class Nablafire_Widget_Data_Control
  {

      public function __construct()
      {
          $this->color = array(
            'red'   => '#ffebe0',
            'orange'=> '#ffeabf',
            'yellow'=> '#fcf5cc',
            'green' => '#d8ffda',
            'blue'  => '#dbddff',
            'purple'=> '#efd3ff',
            'grey'  => '#dddddd',
            'none'  => '#eeeeee',
          );
      }

      // Text Control Template
      private function text_template($widget, $key, $data, $option){ ?>

          <span><strong><?php echo $data['label'] ?></strong></span>
          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?>

          <p>
          <input class="widefat widget-data-control"
                 name="<?php echo esc_attr( $widget->get_field_name( $key )); ?>"
                 type="text" 
                 value="<?php echo esc_attr($option); ?>" />
          </p>
      
      <?php }

       private function textarea_template($widget, $key, $data, $option){ ?>

          <span><strong><?php echo $data['label'] ?></strong></span>
          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?>

          <p>
          <textarea class="widefat widget-data-control"
                    name="<?php echo esc_attr( $widget->get_field_name( $key )); ?>"
                    type="text" 
                    rows="8" ><?php echo esc_html($option); ?></textarea>
          </p>

      <?php }

      // Number range template
      private function number_template($widget, $key, $data, $option){ ?>

          <span><strong><?php echo $data['label'] ?></strong></span>
          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?>

          <p>
          <input class="widefat widget-data-control"
                 name="<?php echo esc_attr( $widget->get_field_name( $key )); ?>"
                 type="number" 
                 <?php // Add min/max if defined
                 if (array_key_exists('atts', $data)):
                    echo 'min="' . absint($data['atts']['min']) . '"'; 
                    echo 'max="' . absint($data['atts']['max']) . '"';                 
                 endif; 
                 ?>
                 value="<?php echo esc_attr($option); ?>" />
          </p>
      
      <?php }


      // Select Control Template
      private function select_template($widget, $key, $data, $option){ ?>
      
          <span><strong><?php echo $data['label'] ?></strong></span>
          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?>

          <p>
      
          <select class="widefat widget-data-control"
                  name="<?php echo esc_attr( $widget->get_field_name( $key )); ?>" >
                <?php foreach($data['atts'] as $_ => $value): ?>
                  <option value="<?php echo esc_attr($value); ?>"   
                    <?php if($option == $value) { echo 'selected="selected"'; } ?> >
                    <?php echo $value ?> 
                  </option>
              <?php endforeach; ?>
          </select>
          </p>   
      
      <?php }

      // Color Control Template
      private function color_template($widget, $key, $data, $option){ ?>
          
          <span><strong><?php echo $data['label'] ?></strong></span>
          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?>

          <p>
          <input class="color-picker widget-color-picker widget-data-control"
                 name="<?php echo esc_attr( $widget->get_field_name($key)); ?>"
                 type="text" 
                 value="<?php echo esc_attr($option); ?>"  
                 data-alpha="true"
                 data-default-color="<?php echo esc_attr($data['atts']);?>" />
          </p>

      <?php }

      // Image Control Template
      private function image_template($widget, $key, $data, $option){ ?>

        <p>      
            <input class="widefat upload-field"  
                    type="text" 
                    name="<?php echo esc_attr($widget->get_field_name($key)); ?>"
                    value="<?php echo esc_attr($option); ?>" />           
            <span class="action upload upload-button button button-primary"                    
                    style="margin-top:10px;">
            <?php esc_html_e("Choose File", 'nablafire-widgets');?>
            </span>
        </p>

      <?php }


      // Textbox Control Template
      private function checkbox_template($widget, $key, $data, $option){ ?>
         
          <p>
          <input class="widget-data-control" 
                 name="<?php echo esc_attr( $widget->get_field_name( $key )); ?>"
                 type="checkbox" <?php checked( $option, 'on' ); ?> /> 
                 <strong><?php echo $data['label'] ?></strong>
          </p>

          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?>         

      <?php }

      // Label Template. 
      private function label_template($widget, $key, $data, $option){ ?>       
          <p>
          <?php if (array_key_exists('label', $data)): ?>
            <span><strong><?php echo $data['label'] ?></strong></span>
          <?php endif; ?> 
          </p>
          <p>
          <?php if (array_key_exists('desc', $data)): ?>            
            <span><?php echo $data['desc']; ?></span>
          <?php endif; ?> 
          </p>
      <?php }

      // Render the content on the theme customizer page
      public function control( $widget, $setting, $options, $defaults )
      { ?>
         
        <?php // Div Properties ?>
        <div style="padding:10px;
                    margin: 5px 0px; 
                    border: 2px solid #BBBBBB; 
                    background-color:<?php echo $this->color[$setting->color] ?>">
       
        <?php if ($setting->label): // echo label if it exists ?>      
          <p><span><strong><?php echo $setting->label; ?></strong></span></p>
        <?php endif; ?>

        <?php if ($setting->description): // echo description if it exists ?> 
        	<p><span><?php echo $setting->description; ?></span></p>
        <?php endif; ?>

        <?php foreach ($setting->data_keys as $key => $data) { 
            
            if ($data['type'] == 'text') { 
              $this->text_template($widget, $key, $data, $options[$key] );}
            
            if ($data['type'] == 'color') {
              $this->color_template($widget, $key, $data, $options[$key] );}
            
            if ($data['type'] == 'image') {
              $this->image_template($widget, $key, $data, $options[$key] );}  

            if ($data['type'] == 'label') {
              $this->label_template($widget, $key, $data, $options[$key] );}

            if ($data['type'] == 'select') {
              $this->select_template($widget, $key, $data, $options[$key]);}

            if ($data['type'] == 'number') {
              $this->number_template($widget, $key, $data, $options[$key]);}
            
            if ($data['type'] == 'checkbox'){
              $this->checkbox_template($widget, $key, $data, $options[$key] );}
            
            if ($data['type'] == 'textarea') { 
              $this->textarea_template($widget, $key, $data, $options[$key] );}  

        } ?>
        </div>
      <?php }  
    } // END Class
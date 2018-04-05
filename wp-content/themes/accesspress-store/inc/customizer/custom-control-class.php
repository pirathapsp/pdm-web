<?php
/**
 * Custom Control Class
*/

if( class_exists( 'WP_Customize_control') && class_exists( 'WP_Customize_Section' ) ){
    
    class AccessPress_Store_WP_Customize_Switch_Control extends WP_Customize_Control {
		public $type = 'switch';
        
		public function render_content() {
		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		        <div class="switch_options">
                  <span class="switch_enable"> <?php _e('Enable', 'accesspress-store'); ?> </span>
                  <span class="switch_disable"> <?php _e('Disable', 'accesspress-store'); ?> </span>  
                  <input type="hidden" id="enable_prev_next" <?php $this->link(); ?> value="<?php echo $this->value(); ?>" />							
                </div>
            </label>
		<?php
       
		}
	}
    
    class AccessPress_Store_WP_Customize_Switch_Control_YesNo extends WP_Customize_Control {
		public $type = 'switch_yesno';        
		public function render_content() {
		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		        <div class="switch_options">
                  <span class="switch_enable"> <?php _e('Yes', 'accesspress-store'); ?> </span>
                  <span class="switch_disable"> <?php _e('No', 'accesspress-store'); ?> </span>  
                  <input type="hidden" id="enable_prev_next_yn" <?php $this->link(); ?> value="<?php echo $this->value(); ?>" />
                </div>
            </label>
		<?php
       
		}
	}

	class AccessPress_Store_WP_Customize_Radioimage_Control extends WP_Customize_Control {
		public $type = 'radioimage';
		public function enqueue() {
			wp_enqueue_script( 'jquery-ui-button' );
		}
	    public function render_content() {
			$name = '_customize-radio-' . $this->id;
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div id="input_<?php echo $this->id; ?>" class="image">
				<?php foreach ( $this->choices as $value => $label ) : ?>
					<input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo $this->id . $value; ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
						<label for="<?php echo $this->id . $value; ?>">
							<img src="<?php echo esc_html( $label ); ?>"/>
						</label>
					</input>
				<?php endforeach; ?>
			</div>
			<script>jQuery(document).ready(function($) { $( '[id="input_<?php echo $this->id; ?>"]' ).buttonset(); });</script>
			<?php 
		}
	}

	class AccessPress_Store_WP_Category_Checkboxes_Control extends WP_Customize_Control {

	    public $type = 'category-checkboxes';

	    public function render_content() {
	        echo '<script src="' . get_template_directory_uri() . '/js/theme-customizer.js"></script>';
	        echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
	        foreach (get_categories() as $category) {
	            echo '<label><input type="checkbox" name="category-' . $category->term_id . '" id="category-' . $category->term_id . '" class="cstmzr-category-checkbox"> ' . $category->cat_name . '</label><br>';
	        }
	        ?>
	        <input type="hidden" id="<?php echo $this->id; ?>" class="cstmzr-hidden-categories" <?php $this->link(); ?> value="<?php echo sanitize_text_field($this->value()); ?>">
	        <?php
	    }
	}

	/**
	 * AccessPress Store Pro Features
	*/
	class AccessPress_Store_Theme_Info_Product_Custom_Control extends WP_Customize_Control{
	    public function render_content(){ ?>
	        <label>
	            <span class="customize-text_editor_desc button">
	                <?php echo wp_kses_post( $this->description ); ?>
	            </span>
	        </label>
	        <?php
	    }
	}

	if(!class_exists( 'AccessPress_Store_Customize_Section_Pro' )){
		/**
	     * Pro customizer section.
	     *
	     * @since  1.0.0
	     * @access public
	     */
	    class AccessPress_Store_Customize_Section_Pro extends WP_Customize_Section {

	        /**
	         * The type of customize section being rendered.
	         *
	         * @since  1.0.0
	         * @access public
	         * @var    string
	         */
	        public $type = 'accesspress-store-pro';

	        /**
	         * Custom button text to output.
	         *
	         * @since  1.0.0
	         * @access public
	         * @var    string
	         */
	        public $pro_text1 = '';
	        public $title1 = '';

	        /**
	         * Custom pro button URL.
	         *
	         * @since  1.0.0
	         * @access public
	         * @var    string
	         */
	        public $pro_url1 = '';

	        /**
	         * Add custom parameters to pass to the JS via JSON.
	         *
	         * @since  1.0.0
	         * @access public
	         * @return void
	         */
	        public function json() {
	            $json = parent::json();
	            $json['pro_text'] = $this->pro_text;
	            $json['title1'] = $this->title1;
	            $json['pro_text1'] = $this->pro_text1;
	            $json['pro_url']  = esc_url( $this->pro_url );
	            $json['pro_url1']  = $this->pro_url1;
	            return $json;
	        }

	        /**
	         * Outputs the Underscore.js template.
	         *
	         * @since  1.0.0
	         * @access public
	         * @return void
	         */
	        protected function render_template() { ?>

	            <li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
	                <h3 class="accordion-section-title">
	                    {{ data.title1 }}
	                    <# if ( data.pro_text1 && data.pro_url1 ) { #>
	                        <a href="{{ data.pro_url1 }}" class="button button-secondary alignright" target="_blank">{{ data.pro_text1 }}</a>
	                    <# } #>
	                </h3>
	            </li>
	        <?php }
	    }
	}
}
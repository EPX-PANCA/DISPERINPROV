<?php

/**
 * Base Widget Class
 */
class AWPA_Widget_Base extends WP_Widget {
    /**
     * @var Array of string
     */
    public $text_fields = array();
   
    /**
     * @var Array of string
     */
    public $select_fields = array();

    /**
     * @var form instance object
     */
    public $form_instance = '';
    /**
     * Register widget with WordPress.
     */
    function __construct($id, $name, $args = array(), $controls = array() ) {
        parent::__construct(
            $id, // Base ID
            $name, // Name
            $args, // Args
            $controls
        );
    }
    /**
     * Function to quick create form input field
     *
     * @param string $field widget field name
     * @param string $label
     * @param string $note field note to appear below
     */
    public function awpa_generate_text_input($field, $label, $value, $type='text', $note = '', $class = '', $active_callback='') {
        $instance = isset($this->form_instance[$field]) ? $this->form_instance[$field] : $value;
        ?>
        <p class="<?php echo $active_callback; ?>">
            <label for="<?php echo $this->get_field_id( $field ); ?>">
                <?php _e( $label, 'rpwe' ); ?>
            </label>
            <input class="widefat <?php echo $class; ?>"
                   id="<?php echo $this->get_field_id( $field ); ?>"
                   name="<?php echo $this->get_field_name( $field ); ?>"
                   type="<?php echo $type ?>"
                   value="<?php echo $instance; ?>" />
            <?php if ( !empty( $note ) ): ?>
                <small><?php echo $note; ?></small>
            <?php endif; ?>
        </p>
        <?php
    }
    
    /**
     * Generate select input
     *
     * @param string $field widget field name
     * @param string $label
     * @param string $note field note to appear below
     * @param Array_A $elements
     */
    public function awpa_generate_select_options($field, $label, $elements, $note = '', $class='', $active_callback='') {
        $instance = isset($this->form_instance[$field]) ? $this->form_instance[$field] : $label;
        ?>
        <p class="<?php echo $active_callback; ?>">
            <label for="<?php echo $this->get_field_id( $field ); ?>">
                <?php _e( $label, 'wp-post-author' ); ?>
            </label>
            <select class="widefat <?php echo $class; ?>" id="<?php echo $this->get_field_id( $field ); ?>" name="<?php echo $this->get_field_name( $field ); ?>" style="width:100%;">
                <?php foreach ( $elements as $key => $elem ) : ?>
                    <option value="<?php echo $key; ?>" <?php selected( $instance, $key ); ?>><?php echo ucfirst( $elem ); ?></option>
                    </li>
                <?php endforeach; ?>
            </select>
            <?php if ( !empty( $note ) ): ?>
                <small><?php echo $note; ?></small>
            <?php endif; ?>
        </p>
        <?php
    }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance = $this->awpa_sanitize_data( $instance, $new_instance );
        return $instance;
    }
    public function awpa_sanitize_data($instance, $new_instance) {
        if ( is_array( $this->text_fields ) ) {
            // update the text fields values
            foreach ( $this->text_fields as $field ) {
                $instance = array_merge( $instance, $this->awpa_update_text( $field, $new_instance ) );
            }
        }

        
        if ( is_array( $this->select_fields ) ) {
            // update the select fields values
            foreach ( $this->select_fields as $field ) {
                $instance = array_merge( $instance, $this->awpa_update_select( $field, $new_instance ) );
            }
        }
        return $instance;
    }
    /**
     * Update and sanitize backend value of the text field
     *
     * @param string $name
     * @param object $new_instance
     * @return object validate new instance
     */
    public function awpa_update_text($name, $new_instance) {
        $instance = array();
        $instance[$name] = (!empty( $new_instance[$name] )) ? sanitize_text_field( $new_instance[$name] ) : '';
        return $instance;
    }
    
    /**
     * Update and sanitize backend value of the select field
     *
     * @param string $name
     * @param object $new_instance
     * @return object validate new instance
     */
    public function awpa_update_select($name, $new_instance) {
        $instance = array();
        $instance[$name] = (!empty( $new_instance[$name] )) ? esc_attr( $new_instance[$name] ) : '';
        return $instance;
    }


}
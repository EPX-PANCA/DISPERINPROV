<?php
if (!class_exists('AWPA_Widget')) :
    /**
     * Adds AWPA_Widget widget.
     */
    class AWPA_Widget extends AWPA_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('awpa-post-author-title', 'awpa-post-author-id');
            $this->select_fields = array('awpa-post-author-type','awpa-post-author-align', 'awpa-post-author-image-layout', 'awpa-post-author-show-role', 'awpa-post-author-show-email' );
           
            $widget_ops = array(
                'classname' => 'wp_post_author_widget',
                'description' => __('Displays posts author descriptions.', 'elegant-magazine'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('wp__post_author', __('WP Post Author', 'elegant-magazine'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */

        public function widget($args, $instance)
        {

            $author_type = isset($instance['awpa-post-author-type']) ? $instance['awpa-post-author-type'] : 'post-author';
            if($author_type == 'specific-author'){
                $author_id = isset($instance['awpa-post-author-id']) ? $instance['awpa-post-author-id'] : '';
            }else{
                $author_id = '';
            }

            $title = isset($instance['awpa-post-author-title']) ? $instance['awpa-post-author-title'] : __('About Post Author', 'wp-post-author');
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);

            $alignment = isset($instance['awpa-post-author-align']) ? $instance['awpa-post-author-align'] : 'left';
            $image_layout = isset($instance['awpa-post-author-image-layout']) ? $instance['awpa-post-author-image-layout'] : 'square';

            $show_role = isset($instance['awpa-post-author-show-role']) ? $instance['awpa-post-author-show-role'] : 'false';
            $show_role = ($show_role == 'true') ? true : false;

            $show_email = isset($instance['awpa-post-author-show-email']) ? $instance['awpa-post-author-show-email'] : 'false';
            $show_email = ($show_email == 'true') ? true : false;

            /** This filter is documented in wp-includes/default-widgets.php */
                // open the widget container
            echo $args['before_widget'];

            $show = true;

            if(is_single()){
                $post_type = get_post_type();            
                // Set class property
                $options = get_option('awpa_setting_options');

                if( $post_type == 'post' ){                    
                    $show = true;
                }else{

                    if( isset($options['awpa_also_visibile_in_'.$post_type]) ){
                        $visibile = $options['awpa_also_visibile_in_'.$post_type];
                        if( $visibile == $post_type){
                            $show = true;
                        }
                    }else{
                            $show = false;
                    }
                    
                }

            }elseif(is_front_page()){
                
                    $show = true;
               
            }      

            if(( $show == true) || is_author()){   
                   ?>
                   <div class="wp-post-author-wrap <?php echo $alignment; ?>">
                    <h2 class="widget-title"><?php echo $title; ?></h2>
                    <?php do_action('before_wp_post_author'); ?>
                    <?php awpa_get_author_block( $author_id, $image_layout, $show_role, $show_email ); ?>
                    <?php do_action('after_wp_post_author'); ?>
                </div>

                <?php

                // close the widget container
                echo $args['after_widget'];
            }
        
        $instance = parent::awpa_sanitize_data($instance, $instance);


    }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
            $this->form_instance = $instance;

            $author_type = array(
                'post-author' => __('Post Author', 'wp-post-author'),
                'specific-author' => __('Specific Author', 'wp-post-author')               
            );


            $alignments = array(
                'left' => __('Left', 'wp-post-author'),
                'right' => __('Right', 'wp-post-author'),
                'center' => __('Center', 'wp-post-author')
            );

            $layout = array(
                'square' => __('Square', 'wp-post-author'),
                'round' => __('Round', 'wp-post-author')

            );

            $options = array(
                'false' => __('No', 'wp-post-author'),
                'true' => __('Yes', 'wp-post-author')

            );

// generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::awpa_generate_text_input('awpa-post-author-title', 'Title', 'WP Post Author');
            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            echo parent::awpa_generate_select_options('awpa-post-author-type', 'Author type', $author_type, 'Select author type to display.', 'awpa-post-author-type');
            echo parent::awpa_generate_text_input('awpa-post-author-id', 'Author id', 1, 'number', '', '', 'ac-awpa-post-author-type');
            echo parent::awpa_generate_select_options('awpa-post-author-align', 'Alignment', $alignments, 'Select element alignment.');
            echo parent::awpa_generate_select_options('awpa-post-author-image-layout', 'Profile image layout', $layout, 'Select author image layout.');
            echo parent::awpa_generate_select_options('awpa-post-author-show-role', 'Show author role', $options, 'Show/hide author role.');
            echo parent::awpa_generate_select_options('awpa-post-author-show-email', 'Show author email', $options, 'Show/hode author email.');


        }


    }
endif;
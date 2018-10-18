<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!function_exists('awpa_get_author_shortcode')){
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_shortcode( $atts ){

     
        $awpa = shortcode_atts(array(
            'title' => __('WP Post Author', 'wp-post-author'),
            'author-id' => '',
            'align' => 'left',
            'image-layout' => 'square',
            'show-role' => 'false',
            'show-email' => 'false'
        ), $atts);
        
        $author_id = !empty($awpa['author-id']) ? absint($awpa['author-id']) : '';
        $title = isset($awpa['title']) ? esc_attr($awpa['title']) : '';
        $align = !empty($awpa['align']) ? esc_attr($awpa['align']) : 'left';
        $image_layout = !empty($awpa['image-layout']) ?esc_attr($awpa['image-layout']) : 'square';
        $show_role = !empty($awpa['show-role']) ? esc_attr($awpa['show-role']) : 'false';
        $show_email = !empty($awpa['show-email']) ? esc_attr($awpa['show-email']) : 'false';
       
        $show_role = ($show_role == 'true') ? true : false;   
        $show_email = ($show_email == 'true') ? true : false;
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
                ob_start();
                ?>
                <div class="wp-post-author-wrap wp-post-author-shortcode <?php echo $align; ?>">
                    <h3 class="awpa-title"><?php echo $title; ?></h3>
                    <?php do_action('before_wp_post_author'); ?>
                    <?php awpa_get_author_block( $author_id, $image_layout, $show_role, $show_email ); ?>
                    <?php do_action('after_wp_post_author'); ?>
                </div>
                <?php
                      
                return ob_get_clean();
                }
        


        
    }
}
add_shortcode( 'wp-post-author', 'awpa_get_author_shortcode' );
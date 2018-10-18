<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * WP Post Author
 *
 * Allows user to get WP Post Author.
 *
 * @class   WP_Post_Author_Frontend
 */


class WP_Post_Author_Frontend {



	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->id                 = 'WP_Post_Author_Frontend';
		$this->method_title       = __( 'WP Post Author Frontend', 'wp-post-author' );
		$this->method_description = __( 'WP Post Author Frontend', 'wp-post-author' );
		
		// Actions
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_post_author_enqueue_style') );
        

	}


	/**
	 * Loading  frontend styles.
	 */

	public function wp_post_author_enqueue_style(){
	    wp_register_style('awpa-wp-post-author-style', AWPA_PLUGIN_URL .'/assets/css/awpa-frontend-style.css', array(), '', 'all');
	    wp_enqueue_style('awpa-wp-post-author-style');
        wp_add_inline_style( 'aem-custom-style', $this->wp_post_author_add_custom_style() );

    }

    public function wp_post_author_add_custom_style(){
        $options = get_option('awpa_setting_options');
        $custom_css = isset($options['awpa_custom_css']) ? ($options['awpa_custom_css']) : '';
        if(!empty($custom_css)):
	        ?>

	        <style type="text/css">
	            <?php echo wp_strip_all_tags($custom_css); ?>
	        </style>

	        <?php
	    endif;
    }



}

$awpa_frontend = new WP_Post_Author_Frontend();
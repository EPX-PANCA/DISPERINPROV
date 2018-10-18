<?php
/*
 * Plugin Name:       WP Post Author
 * Plugin URI:        https://wordpress.org/plugins/wp-post-author/
 * Description:       Post author box | Author social icons | Author bio - Widgets and Shortcodes
 * Version:           1.0.6
 * Author:            AF themes
 * Author URI:        https://profiles.wordpress.org/afthemes
 * Text Domain:       wp-post-author
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );  // prevent direct access

if ( ! class_exists( 'WP_Post_Author' ) ) :
	
	class WP_Post_Author {


		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.0.6';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;


		/**
		 * Initialize the plugin.
		 */
		public function __construct(){

            /**
             * Define global constants
             **/
            defined('AWPA_BASE_FILE') or define('AWPA_BASE_FILE', __FILE__);
            defined('AWPA_BASE_DIR') or define('AWPA_BASE_DIR', dirname(AWPA_BASE_FILE));
            defined('AWPA_PLUGIN_URL') or define('AWPA_PLUGIN_URL', plugin_dir_url(__FILE__));
            defined('AWPA_PLUGIN_DIR') or define('AWPA_PLUGIN_DIR', plugin_dir_path(__FILE__));

            include_once 'includes/core.php';

			} // end of contructor

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

			

	}// end of the class

add_action( 'plugins_loaded', array( 'WP_Post_Author', 'get_instance' ), 0 );

endif;
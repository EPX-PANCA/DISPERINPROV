<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * WP Post Author
 *
 * Allows user to get WP Post Author.
 *
 * @class   WP_Post_Author_Backend
 */
class WP_Post_Author_Backend
{

    /**
     * Init and hook in the integration.
     *
     * @return void
     */


    public function __construct()
    {
        $this->id = 'WP_Post_Author_Backend';
        $this->method_title = __('WP Post Author Backend', 'wp-post-author');
        $this->method_description = __('WP Post Author Backend', 'wp-post-author');

        include_once 'awpa-user-fields.php';

        include_once 'awpa-widget-base.php';

        include_once 'awpa-widget.php';

        add_action('widgets_init', array($this, 'awpa_widgets_init'));

        add_action('admin_menu', array($this, 'awpa_register_settings_menu_page'));
        add_action('admin_init', array($this, 'awpa_display_options'));

        // Actions
        add_action( 'admin_enqueue_scripts', array( $this, 'wp_post_author_enqueue_admin_style') );


    }

    public function wp_post_author_enqueue_admin_style(){
        wp_register_style('awpa-admin-style', AWPA_PLUGIN_URL .'/assets/css/awpa-backend-style.css', array(), '', 'all');
        wp_enqueue_style('awpa-admin-style');

        $screen = get_current_screen();
        if(isset($screen)){
            if($screen->base == 'widgets'){

                wp_register_script('awpa-admin-scripts', AWPA_PLUGIN_URL .'/assets/js/awpa-backend-scripts.js', array('jquery'));
                wp_enqueue_script('awpa-admin-scripts');
            }
        }
    }

    public function awpa_widgets_init()
    {
        register_widget('AWPA_Widget');
    }

    /**
     * Register a awpa settings page
     */
    public function awpa_register_settings_menu_page()
    {
        add_menu_page(
            __('WP Post Author', 'wp-post-author'),
            'WP Post Author',
            'manage_options',
            'wp-post-author',
            array($this, 'awpa_settings_menu_page_callback'),
            'dashicons-id-alt',
            70

        );
    }

    /**
     * Display a awpa settings page
     */
    public function awpa_settings_menu_page_callback()
    {

        // Set class property
        $options = get_option('awpa_setting_options');
        if (!empty($options)) {
            $this->options = $options;
        }

        ?>
        <div class="wrap">

            <h1><?php _e("WP Post Author", 'wp-post-author'); ?></h1>

            <?php
            //we check if the page is visited by click on the tabs or on the menu button.
            //then we get the active tab.
            $active_tab = "awpa-global-settings";
            if (isset($_GET["tab"])) {
                if ($_GET["tab"] == "awpa-widget") {
                    $active_tab = "awpa-widget";
                } elseif ($_GET["tab"] == "awpa-shortcode") {
                    $active_tab = "awpa-shortcode";
                } elseif ($_GET["tab"] == "awpa-custom-css") {
                    $active_tab = "awpa-custom-css";                    
                }else {
                    $active_tab = "awpa-global-settings";
                }
            }
            ?>

            <!-- wordpress provides the styling for tabs. -->
            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                
                <a href="?page=wp-post-author&tab=awpa-global-settings"
                   class="nav-tab <?php if ($active_tab == 'awpa-global-settings') {
                       echo 'nav-tab-active';
                   } ?> ">
                    <?php _e('Global Settings', 'wp-post-author'); ?>
                </a>
                <a href="?page=wp-post-author&tab=awpa-widget"
                   class="nav-tab <?php if ($active_tab == 'awpa-widget') {
                       echo 'nav-tab-active';
                   } ?> ">
                    <?php _e('Widget', 'wp-post-author'); ?>
                </a>
                <a href="?page=wp-post-author&tab=awpa-shortcode"
                   class="nav-tab <?php if ($active_tab == 'awpa-shortcode') {
                       echo 'nav-tab-active';
                   } ?>">
                    <?php _e('Shortcode', 'wp-post-author'); ?>
                </a>
                <a href="?page=wp-post-author&tab=awpa-custom-css"
                   class="nav-tab <?php if ($active_tab == 'awpa-custom-css') {
                       echo 'nav-tab-active';
                   } ?>">
                    <?php _e('Custom Styling', 'wp-post-author'); ?>
                </a>
            </h2>

            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'awpa-global-settings') {
                    settings_fields("awpa_setting_group");
                    do_settings_sections("awpa-global-settings-section");
                    submit_button();
                } elseif ($active_tab == 'awpa-widget') {
                    settings_fields("awpa_setting_group");
                    do_settings_sections("awpa-widget-section");
                } elseif ($active_tab == 'awpa-shortcode') {
                    settings_fields("awpa_setting_group");
                    do_settings_sections("awpa-shortcode-section");
                }elseif ($active_tab == 'awpa-custom-css') {
                    settings_fields("awpa_setting_group");
                    do_settings_sections("awpa-custom-css-section");
                    submit_button();
                } else {
                    settings_fields("awpa_setting_group");
                    do_settings_sections("awpa-global-settings-section");
                }

                ?>
            </form>
        </div>
        <?php
    }

    public function awpa_display_options()
    {


        //here we display the sections and options in the settings page based on the active tab
        register_setting(
            "awpa_setting_group",
            "awpa_setting_options",
            array($this, 'awpa_sanitize')
        );
        if (isset($_GET["tab"])) {

            if ($_GET["tab"] == "awpa-global-settings") {
                add_settings_section(
                    "awpa_global_settings_section_id",
                    __("WP Post Author global settings", 'wp-post-author'),
                    array(),
                    "awpa-global-settings-section"
                );


                add_settings_field(
                    "awpa_appearance_id",
                    __("Appearance", 'wp-post-author'),
                    array($this,
                        "awpa_appearance_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

                add_settings_field(
                    "awpa_visibility_id",
                    __("Visibility", 'wp-post-author'),
                    array($this,
                        "awpa_visibility_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

                add_settings_field(
                    "awpa_hide_from_post_content_id",
                    __("Remove from post content", 'wp-post-author'),
                    array($this,
                        "awpa_hide_from_post_content_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );
                

            } elseif ($_GET["tab"] == "awpa-widget") {

                add_settings_section(
                    "awpa_widget_section_id",
                    __("WP Post Author widget", 'wp-post-author'),
                    array($this, "awpa_widget_section_info"),
                    "awpa-widget-section"
                );

            }  elseif ($_GET["tab"] == "awpa-shortcode") {

                add_settings_section(
                    "awpa_shortcode_section_id",
                    __("WP Post Author shortcode", 'wp-post-author'),
                    array($this, "awpa_shortcode_section_info"),
                    "awpa-shortcode-section"
                );

            } elseif ($_GET["tab"] == "awpa-custom-css") {

                add_settings_section(
                    "awpa_custom_css_section_id",
                    __("Custom styling", 'wp-post-author'),
                    array($this, "awpa_custom_css_section_info"),
                    "awpa-custom-css-section"
                );
                add_settings_field(
                    "awpa_custom_css_id",
                    __("Custom css box", 'wp-post-author'), 
                    array($this,
                    "awpa_display_custom_css_options"),
                    "awpa-custom-css-section",
                    "awpa_custom_css_section_id"
                );
            } else {
                add_settings_section(
                    "awpa_global_settings_section_id",
                    __("WP Post Author global settings", 'wp-post-author'),
                    array(),
                    "awpa-global-settings-section"
                );

                add_settings_field(
                    "awpa_appearance_id",
                    __("Appearance", 'wp-post-author'),
                    array($this,
                        "awpa_appearance_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

                add_settings_field(
                    "awpa_visibility_id",
                    __("Visibility", 'wp-post-author'),
                    array($this,
                        "awpa_visibility_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

                add_settings_field(
                    "awpa_hide_from_post_content_id",
                    __("Remove from post content", 'wp-post-author'),
                    array($this,
                        "awpa_hide_from_post_content_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

            }
        } else {

              add_settings_section(
                    "awpa_global_settings_section_id",
                    __("WP Post Author global settings", 'wp-post-author'),
                    array(),
                    "awpa-global-settings-section"
                );

              add_settings_field(
                    "awpa_appearance_id",
                    __("Appearance", 'wp-post-author'), 
                    array($this,
                    "awpa_appearance_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

                add_settings_field(
                    "awpa_visibility_id",
                    __("Visibility", 'wp-post-author'),
                    array($this,
                    "awpa_visibility_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );

                add_settings_field(
                    "awpa_hide_from_post_content_id",
                    __("Remove from post content", 'wp-post-author'), 
                    array($this,
                    "awpa_hide_from_post_content_options"),
                    "awpa-global-settings-section",
                    "awpa_global_settings_section_id"
                );
                 

        }

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function awpa_sanitize($input)
    {
        $new_input = array();

        //global settings
        if (isset($input['awpa_global_title'])) {
            $new_input['awpa_global_title'] = sanitize_text_field($input['awpa_global_title']);
        }

        if (isset($input['awpa_global_align'])) {
            $new_input['awpa_global_align'] = sanitize_text_field($input['awpa_global_align']);
        }

        if (isset($input['awpa_global_image_layout'])) {
            $new_input['awpa_global_image_layout'] = sanitize_text_field($input['awpa_global_image_layout']);
        }

        if (isset($input['awpa_global_show_role'])) {
            $new_input['awpa_global_show_role'] = sanitize_text_field($input['awpa_global_show_role']);
        }

        if (isset($input['awpa_global_show_email'])) {
            $new_input['awpa_global_show_email'] = sanitize_text_field($input['awpa_global_show_email']);
        }


        //post type
        $args = array(
             'public'   => true,
             '_builtin' => false,
         );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $post_types = get_post_types( $args, $output, $operator );
       

        
        if(isset($post_types)){
            foreach ( $post_types  as $post_type ){                
               if (isset($input['awpa_also_visibile_in_'.$post_type])) {
                 
                    $new_input['awpa_also_visibile_in_'.$post_type] = $input['awpa_also_visibile_in_'.$post_type];
                }
            }

        } 

        if (isset($input['hide_from_post_content'])) {
            $new_input['hide_from_post_content'] = wp_strip_all_tags($input['hide_from_post_content']);
        }

        //custom css

        if (isset($input['awpa_custom_css'])) {
            $new_input['awpa_custom_css'] = wp_strip_all_tags($input['awpa_custom_css']);
        }


        return $new_input;
    }

    public function awpa_global_settings_section_info()
    {
        ?>
        <p class="awpa-global-settings-desc">
            <?php _e('Please set global settings for WP Post Author widget and', 'wp-post-author'); ?>
            <?php
            $output = "";
            $output .= htmlspecialchars("<?php echo do_shortcodes('");
            $output .= "<span class='awpa-shortcodes-only' style='color: #0073aa;' >";
            $output .= '[wp-post-author]';
            $output .= "</span>";
            $output .= htmlspecialchars("'); ?>");
            echo $output;
            ?>
            <?php _e('shortcode.', 'wp-post-author'); ?>
        </p>
        <?php
    }

    public function awpa_appearance_options(){

        $title = isset($this->options['awpa_global_title']) ? $this->options['awpa_global_title'] : __('About Post Author','wp-post-author');
        $align = isset($this->options['awpa_global_align']) ? $this->options['awpa_global_align'] : 'left';
        $image_layout = isset($this->options['awpa_global_image_layout']) ? $this->options['awpa_global_image_layout'] : 'square';
        $show_role = isset($this->options['awpa_global_show_role']) ? $this->options['awpa_global_show_role'] : 'false';
        $show_email = isset($this->options['awpa_global_show_email']) ? $this->options['awpa_global_show_email'] : 'false';


        ?>
        <p class="awpa-section-desc">
            <?php _e('Please set appearance for the WP Post Author.', 'wp-post-author'); ?>
        </p>
        <table class="awpa-table awpa-appearance-table">
            <tr>
                <td><label for ="awpa_global_title" ><?php _e('Title', 'wp-post-author'); ?></label></td>
                <td><input type="text" name="awpa_setting_options[awpa_global_title]" value="<?php echo $title;  ?>"></td>
            </tr>
            <tr>
                <td><label for ="awpa_setting_options[awpa_global_align]" ><?php _e('Alignment', 'wp-post-author'); ?></label></td>
                <td>
                    <select name="awpa_setting_options[awpa_global_align]">
                        <option value="left" <?php selected( $align, 'left'); ?>><?php _e('Left', 'wp-post-author'); ?></option>
                        <option value="right" <?php selected( $align, 'right'); ?>><?php _e('Right', 'wp-post-author'); ?></option>
                        <option value="center" <?php selected( $align, 'center'); ?>><?php _e('Center', 'wp-post-author'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for ="awpa_global_image_layout" ><?php _e('Profile image layout', 'wp-post-author'); ?></label></td>
                <td>
                    <select name="awpa_setting_options[awpa_global_image_layout]">
                        <option value="square" <?php selected( $image_layout, 'square'); ?>><?php _e('Square', 'wp-post-author'); ?></option>
                        <option value="round" <?php selected( $image_layout, 'round'); ?>><?php _e('Round', 'wp-post-author'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for ="awpa_global_show_role" ><?php _e('Show author role', 'wp-post-author'); ?></label></td>
                <td>
                    <select name="awpa_setting_options[awpa_global_show_role]">
                        <option value="false" <?php selected( $show_role, 'false'); ?>><?php _e('No', 'wp-post-author'); ?></option>
                        <option value="true" <?php selected( $show_role, 'true'); ?>><?php _e('Yes', 'wp-post-author'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for ="awpa_global_show_email" ><?php _e('Show author email', 'wp-post-author'); ?></label></td>
                <td>
                    <select name="awpa_setting_options[awpa_global_show_email]">
                        <option value="false" <?php selected( $show_email, 'false'); ?>><?php _e('No', 'wp-post-author'); ?></option>
                        <option value="true" <?php selected( $show_email, 'true'); ?>><?php _e('Yes', 'wp-post-author'); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <?php
    }

    public function awpa_visibility_options()
    {  ?>
                
        <p class="awpa-section-desc">
            <?php _e('By default, output from the WP Post Author will be visible for your front page, posts and single author page.', 'wp-post-author'); ?>
        </p>


        <?php

            $args = array(
                'public'   => true,
                '_builtin' => false,
            );
            $output = 'objects'; // names or objects, note names is the default
            $operator = 'and'; // 'and' or 'or'
            $post_types = get_post_types( $args, $output, $operator );

            if(isset($post_types) && !empty($post_types)):

        ?>

        <strong class="awpa-section-desc">
            <?php _e('If you need to show it from any other available post type, please check.', 'wp-post-author'); ?>
        </strong>
        <ul class="awpa-list">

            <?php
                foreach ( $post_types  as $post_type ):

                    $checked = '';
                    if ( isset($this->options['awpa_also_visibile_in_'.$post_type->name]) ) {
                        $checked = 'checked';
                    }
                    ?>
                    <li>
                        <input type="checkbox" name="awpa_setting_options[awpa_also_visibile_in_<?php echo $post_type->name; ?>]" value="<?php echo $post_type->name; ?>" <?php echo $checked; ?>><?php echo 'Also show on '. $post_type->labels->menu_name; ?>
                    </li>
                <?php endforeach; ?>
        </ul>
            <?php endif; ?>

      
    <?php }


    public function awpa_hide_from_post_content_options()
    {
        $checked = '';
        if (isset($this->options['hide_from_post_content'])) {
            $checked = 'checked';
        }

        ?>
        <input type="checkbox" name="awpa_setting_options[hide_from_post_content]"
               value="<?php echo 'hide'; ?>" <?php echo $checked; ?>><?php _e('Remove', 'wp-post-author'); ?>
        <p class="awpa-section-desc">
            <?php _e('When turned ON, the output from the WP Post Author will no longer be automatically added to your post content. You\'ll need to manually add it using widgets, shortcodes or a PHP function.', 'wp-post-author'); ?>
        </p>
    <?php }


    public function awpa_widget_section_info()
    {
        ?>
        <p class="awpa-section-desc">
            <?php _e('The widget "WP Post Author" is mainly designed for WordPress single post author and author page.', 'wp-post-author'); ?>
        </p>
        <?php
        $options = array(
            array(
                'title' => 'Title',
                'available_value' => 'Any texts string',
                'default' => 'WP Post Author'
            ),
            array(
                'title' => 'Alignment',
                'available_value' => 'Left, Right or Center',
                'default' => 'Left'
            ),
            array(
                'title' => 'Profile image layout',
                'available_value' => 'Square or Round',
                'default' => 'Square'
            ),
            array(
                'title' => 'Show author role',
                'available_value' => 'Yes or No',
                'default' => 'Yes'
            ),
            array(
                'title' => 'Show author email',
                'available_value' => 'Yes or No',
                'default' => 'Yes'
            )
        );
        ?>

        <table class="awpa-table wp-list-table widefat fixed striped posts">
            <thead>

            <tr>
                <th><strong><?php _e('Options', 'wp-post-author'); ?></strong></th>
                <th><strong><?php _e('Available values', 'wp-post-author'); ?></strong></th>
                <th><strong><?php _e('Default value', 'wp-post-author'); ?></strong></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($options as $option): ?>
                <tr>
                    <td><strong><?php _e($option['title'], 'wp-post-author'); ?></strong></td>
                    <td><?php _e($option['available_value'], 'wp-post-author'); ?></td>
                    <td><?php _e($option['default'], 'wp-post-author'); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p class="awpa-section-desc">
            <?php _e('You can add and configure the widget from the following admin page:', 'wp-post-author'); ?>
            <a href="<?php echo admin_url('widgets.php'); ?>"
               target="_blank"><?php _e('Widgets.', 'wp-post-author'); ?></a>
            <?php _e('The widget will only visible on single post. ', 'wp-post-author'); ?>
        </p>
        <?php $this->awpa_display_other_options(); ?>

        <?php

    }

    public function awpa_shortcode_section_info()
    {
        ?>
        <p class="awpa-section-desc">
            <?php _e('The shortcode ', 'wp-post-author'); ?>
            <?php
            $output = "";
            $output .= htmlspecialchars("<?php echo do_shortcodes('");
            $output .= "<span class='awpa-shortcodes-only' style='color: #0073aa;' >";
            $output .= '[wp-post-author]';
            $output .= "</span>";
            $output .= htmlspecialchars("'); ?>");
            echo $output;
            ?>
            <?php _e(' is mainly designed for WordPress single post author and  author page.', 'wp-post-author'); ?>
        </p>
        <?php
        $options = array(
            array(
                'title' => 'align',
                'available_value' => 'left, right or center',
                'default' => 'left'
            ),
            array(
                'title' => 'image-layout',
                'available_value' => 'square or round',
                'default' => 'square'
            ),
            array(
                'title' => 'show-role',
                'available_value' => 'true or false',
                'default' => 'true'
            ),
            array(
                'title' => 'show-email',
                'available_value' => 'true or false',
                'default' => 'true'
            )
        );
        ?>
        <table class="awpa-table wp-list-table widefat fixed striped posts">
            <thead>

            <tr>
                <th><strong><?php _e('Options'); ?></strong></th>
                <th><strong><?php _e('Available values'); ?></strong></th>
                <th><strong><?php _e('Default value'); ?></strong></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($options as $option): ?>
                <tr>
                    <td><strong><?php echo $option['title']; ?></strong></td>
                    <td><?php  echo $option['available_value']; ?></td>
                    <td><?php echo $option['default']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="awpa-section-desc">
            <?php _e('You can configure and use the shortcode for .php templates and functions with appropriate attribute values. The output will only visible on single post and single author page.', 'wp-post-author'); ?>
        </p>
        <?php $this->awpa_display_other_options(); ?>

        <?php

    }

    public function awpa_custom_css_section_info()
    {
        ?>
        <p class="awpa-section-desc">
            <?php _e('Please paste appropriate css code snippets to the given area.', 'wp-post-author'); ?>
        </p>
        <?php
    }

    public function awpa_display_custom_css_options()
    {

        $custom_css = '';
        if (isset($this->options['awpa_custom_css'])) {
            $custom_css = ($this->options['awpa_custom_css']);
        }

        ?>
        <textarea id="awpa_custom_css" name="awpa_setting_options[awpa_custom_css]" rows="20"
                  cols="60"><?php echo $custom_css; ?></textarea>
        <?php
    }

    public function awpa_display_other_options()
    {

        $options = array(
            array(
                'title' => 'Facebook',
                'available_value' => 'Visible if available'
            ),
            array(
                'title' => 'Twitter',
                'available_value' => 'Visible if available'
            ),

            array(
                'title' => 'Linkedin',
                'available_value' => 'Visible if available'
            ),

            array(
                'title' => 'Website',
                'available_value' => 'Visible if available'
            ),
            array(
                'title' => 'Author bio',
                'available_value' => 'Visible if available'
            ),

        );
        ?>
        <h2><?php _e('Other options'); ?></h2>


        <table class="awpa-table wp-list-table widefat fixed striped posts">
            <thead>

            <tr>
                <th><strong><?php _e('Options', 'wp-post-author'); ?><strong></th>
                <th><strong><?php _e('Availability', 'wp-post-author'); ?><strong></th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($options as $option): ?>
                <tr>
                    <td><strong><?php _e($option['title'], 'wp-post-author'); ?></strong></td>
                    <td><?php _e($option['available_value'], 'wp-post-author'); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="awpa-section-desc">
            <?php _e('Add/remove available contact info by selecting specific user from the following admin page:', 'wp-post-author'); ?>
            <a href="<?php echo admin_url('users.php'); ?>" target="_blank"><?php _e('Users.', 'wp-post-author'); ?></a>
        </p>

    <?php }


}

$awpa_backend = new WP_Post_Author_Backend();
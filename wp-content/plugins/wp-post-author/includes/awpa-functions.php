<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!function_exists('awpa_get_author_role')) {

    /**
     * @param $author_id
     * @return mixed
     */
    function awpa_get_author_role($author_id )
    {
        $user = new WP_User($author_id);
        return array_shift($user->roles);

    }
}


if(!function_exists('awpa_get_author_contact_info')){
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_contact_info( $author_id ){
        $author_facebook = get_the_author_meta( 'awpa_contact_facebook', $author_id);

        $author_twitter = get_the_author_meta( 'awpa_contact_twitter', $author_id);

        $author_linkedin = get_the_author_meta( 'awpa_contact_linkedin', $author_id);




        $contact_info = array();

        if(!empty($author_facebook)){
            $contact_info['facebook'] = esc_url($author_facebook);
        }

        if(!empty($author_twitter)){
            $contact_info['twitter'] = esc_url($author_twitter);
        }

        if(!empty($author_linkedin)){
            $contact_info['linkedin'] = esc_url($author_linkedin);
        }

        if(!empty($author_website)){
            $contact_info['website'] = esc_url($author_website);
        }

        return $contact_info;
    }
}



if (!function_exists('awpa_get_author_block')) {
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_block( $author_id, $image_layout='square', $show_role = false, $show_email = false )
    {
        
        if(empty($author_id)){           
            global $post;
            $author_id = get_post_field('post_author', $post->ID);
        }
        
        $author_name = get_the_author_meta('display_name', $author_id);
        $author_website = get_the_author_meta( 'user_url', $author_id);

        $author_role = '';
        if (isset($show_role) && $show_role == true ) {
            $author_role = awpa_get_author_role($author_id);
            $author_role = esc_attr($author_role);
        }

        $author_email = '';
        if (isset($show_email) && $show_email == true) {
            $author_email = get_the_author_meta('user_email', $author_id);
            $author_email = sanitize_email($author_email);
        }



        $contact_info = awpa_get_author_contact_info($author_id);
        $author_posts_url = get_author_posts_url($author_id);
        $author_avatar = get_avatar($author_id, 150);
        $author_desc = get_the_author_meta('description', $author_id);
        ?>

        <div class="wp-post-author">

                <div class="awpa-img awpa-author-block <?php echo $image_layout; ?>">
                    <a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_avatar; ?></a>
                </div>
                <div class="wp-post-author-meta awpa-author-block">
                    <h4 class="awpa-display-name">
                        <a href="<?php echo $author_posts_url; ?>"><?php echo esc_attr($author_name); ?></a>
                    </h4>

                    <?php if (!empty($author_role)): ?>
                        <p class="awpa-role"><?php echo $author_role; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($author_email)): ?>
                        <p class="awpa-mail">
                        <a href="mailto:<?php echo $author_email; ?>"
                           class="awpa-email"><?php echo $author_email; ?></a>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($author_website)): ?>
                        <p class="awpa-website">
                        <a href="<?php echo esc_url($author_website); ?>"
                           class="awpa-email" target="_blank"><?php echo esc_url($author_website); ?></a>
                        </p>
                    <?php endif; ?>
                    <div class="wp-post-author-meta-bio">
                        <?php
                        $author_desc = wptexturize($author_desc);
                        $author_desc = wpautop($author_desc);
                        echo wp_kses_post($author_desc);
                        ?>
                    </div>
                    <div class="wp-post-author-meta-more-posts">
                        <p class="awpa-more-posts">                        
                           <a href="<?php  echo esc_url(get_author_posts_url( $author_id )); ?>" class="awpa-more-posts"><?php esc_html_e("See author's posts", 'wp-post-author'); ?></a>
                        </p>
                    </div>
                    <?php if (!empty($contact_info)): ?>
                        <ul class="awpa-contact-info">
                            <?php foreach ($contact_info as $key => $value): ?>
                                <li class="awpa-<?php echo $key; ?>-li">
                                    <a href="<?php echo $value; ?>"
                                       class="awpa-<?php echo $key; ?> awpa-icon-<?php echo $key; ?>"></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
        </div>

        <?php


    }
}
add_filter('the_content', 'awpa_add_author');
if (!function_exists('awpa_add_author')) {
 function awpa_add_author($content){
     if(is_single()){
         $options = get_option('awpa_setting_options');
         if(!isset($options['hide_from_post_content'])){
             

                 $title = $options['awpa_global_title'];
                 $align = $options['awpa_global_align'];
                 $image_layout = $options['awpa_global_image_layout'];
                 $show_role = $options['awpa_global_show_role'];
                 $show_email = $options['awpa_global_show_email'];

                 $post_author = do_shortcode('[wp-post-author title="'.$title.'" align="'.$align.'" image-layout="'.$image_layout.'" show-role="'.$show_role.'" show-email="'.$show_email.'"]');                 
                 $content .= $post_author;
            
         }
     }


     return $content;
 }
}


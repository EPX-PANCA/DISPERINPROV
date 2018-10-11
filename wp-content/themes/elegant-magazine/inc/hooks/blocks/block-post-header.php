<?php
/**
 * List block part for displaying page content in page.php
 *
 * @package Elegant_Magazine
 */

?>
<header class="entry-header">
    <?php elegant_magazine_post_thumbnail(); ?>
    <div class="header-details-wrapper">
        <div class="entry-header-details">
            <?php if ('post' === get_post_type()) : ?>
                <div class="figure-categories figure-categories-bg">
                    <?php echo elegant_magazine_post_format($post->ID); ?>
                    <?php elegant_magazine_post_categories(); ?>
                </div>
            <?php endif; ?>
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            <?php if ('post' === get_post_type()) : ?>
            <?php if(has_excerpt()): ?>
                <div class="post-excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
                <div class="post-item-metadata entry-meta">
                    <?php elegant_magazine_post_item_meta(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header><!-- .entry-header -->






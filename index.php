<?php get_header(); ?>
	<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>    
                <?php get_template_part('article', get_post_type() , array( 'post' => $post ) ) ?> 
            <?php endwhile; ?>
    <?php endif; ?>
<?php get_footer(); ?>
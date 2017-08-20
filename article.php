<?php do_action('before_article' , $post); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
    <div class="article-header">
        <h3 class="article-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
        <?php do_action('article_header', $post); ?>
    </div>
    <div class="article-content">
        <?php the_content(); ?>
    </div>
    <div class="article-footer">
        <?php do_action('article_footer', $post); ?>
    </div>
</article>
<?php do_action('after_article', $post); ?>
<?php do_action('before_article' , $post); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
    <div class="article-header"><?php do_action('article_header', $post); ?></div>
    <div class="article-content"> <?php do_action('article_content', $post); ?></div>
    <div class="article-footer"><?php do_action('article_footer', $post); ?></div>
</article>
<?php do_action('after_article', $post); ?>
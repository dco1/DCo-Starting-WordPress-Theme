<div class="page-navigation">
    <div class="alignleft">
        <?php if ( is_single() )  previous_post_link( '%link','<i class="fa fa-arrow-circle-o-left"></i> Previous Post' , true);
        echo get_previous_posts_link( '<i class="fa fa-arrow-circle-o-left"></i> Previous Page' ); ?>    
    </div>
    <div class="alignright">
    <?php if ( is_single() )  next_post_link( '%link','Next Post<i class="fa fa-arrow-circle-o-right"></i>' , true); echo get_next_posts_link('Next Page<i class="fa fa-arrow-circle-o-right"></i>'); ?>
    </div>	                   
</div><!-- .page-navigation -->
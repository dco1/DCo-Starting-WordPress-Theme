        </div><!-- #main -->
        <?php do_action('before_footer'); ?>
        <footer><div class="wrapper">
	        <h5 class="footer-site-name"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h5>
	        <ul id="footer-widgets">
				<?php dynamic_sidebar( 'footer-sidebar' ); ?>
	        </ul>        
        </div></footer>
    </div> <!-- #container --> 
<?php wp_footer(); ?>
</body>
</html>
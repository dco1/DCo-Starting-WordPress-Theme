<?php
get_header();

	// If there is a page named 404...
	if ( get_page_by_title('404') ){
		
		global $post;
		$post = get_page_by_title('404');
		
		get_template_part('article', get_post_type() , array( 'post' => get_page_by_title('404') ) );

		//echo do_blocks( get_page_by_title('404')->post_content );
	} else {
	
		?>
		<h2 class="uhoh error error404 404">404</h2>
		<?php
	
	}

get_footer();
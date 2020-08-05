<?php

	add_action('wp_head','dco_add_credits', 99);
	function dco_add_credits(){ echo "<!-- This site was designed and coded by Danny Cohen - dannycohen.design -->";}

////////////////////
/* BASE FUNCTIONS */
///////////////////


    add_action( 'after_setup_theme', 'dco_theme_setup' );
    if ( ! function_exists( 'dco_theme_setup' ) ) :
        function dco_theme_setup(){
            // Enable thumbnails
            add_theme_support( 'post-thumbnails' );
            
            // Register a sidebar
            register_sidebars();
            
            // HTML5 Search Form
            add_theme_support( 'html5', array( 'search-form' ) );
            
            // Let's register some navigation menus
            if ( function_exists( 'register_nav_menus' ) ) {
            	register_nav_menus(
            		array(
                        'main_menu' => 'Main Menu',
                        'social_menu' => 'Social Menu',
                        'footer_menu' => 'Footer Menu'
            		)
            	);
            }
            
            //Maybe we want our default oembed size something different from the default. This is where we can set it.
            //add_filter('embed_defaults','dco_themename_embed_defaults');
            function dco_themename_embed_defaults($defaults) {
               $defaults['width'] = 640;
               return $defaults;
            }
            
           
            
            // And any custom admin css
            add_action( 'admin_enqueue_scripts', 'dco_admin_styles' );
            function dco_admin_styles( ) {
                    wp_register_style( 'custom_wp_admin_css', get_bloginfo( 'stylesheet_directory' ) . '/admin_style.css', false, '1.0.0' );
                    wp_enqueue_style( 'custom_wp_admin_css' );
            }
            
        
        }
    endif; // dco_theme_setup()

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
	function _s_wp_title( $title, $sep ) {
	    if ( is_feed() ) {
	    	return $title;
	    }
	    global $page, $paged;
	    // Add the blog name.
	    $title .= get_bloginfo( 'name', 'display' );
	    // Add the blog description for the home/front page.
	    $site_description = get_bloginfo( 'description', 'display' );
	    if ( $site_description && ( is_home() || is_front_page() ) ) {
	    	$title .= " $sep $site_description";
	    }
	    // Add a page number if necessary.
	    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
	    	$title .= " $sep " . sprintf( esc_html__( 'Page %s', '_s' ), max( $paged, $page ) );
	    }
	    return $title;
	}
	add_filter( 'wp_title', '_s_wp_title', 10, 2 );

// The header

	add_action('wp_head', 'dco_header_tags');
	function dco_header_tags(){ ?>
	    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	    <meta http-equiv="x-ua-compatible" content="ie=edge">
	    <meta name="description" content="<?php bloginfo('description'); ?>" />
	    <meta name="viewport" content="width=device-width" />
	<?php
	}

// Call Googles HTML5 Shim, but only for users on old versions of IE
	add_action('wp_head', 'dco_wpfme_IEhtml5_shim');
	function dco_wpfme_IEhtml5_shim() {
		global $is_IE;
		if ( $is_IE ) echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
	}


// Add a post thumbnail column, please
	add_filter('manage_posts_columns', 'dco_add_post_thumb_posts_columns', 5);
	add_action('manage_posts_custom_column', 'dco_add_post_thumb_posts_custom_columns', 5, 2);
	function dco_add_post_thumb_posts_columns($defaults){
	   unset( $defaults['cb'] );
	   return  array( 'cb' => '',  'post_thumbs' => __('') ) + $defaults;
	}
	function dco_add_post_thumb_posts_custom_columns($column_name, $id){
	        if ( $column_name === 'post_thumbs' ) {
	        echo '<a href="'. get_edit_post_link( $id, '&') .'">' .  get_the_post_thumbnail( $id, array(96,96) ) . '</a>';
	    }
	}




// If the Featured Image is empty, let's grab the first image from the post.
// add_filter('get_post_metadata', 'dco_substitute_for_empty_post_thumbnail_id', 99, 4);
	function dco_substitute_for_empty_post_thumbnail_id( $metadata, $object_id, $meta_key, $single ){
		if ( $meta_key == '_thumbnail_id' && empty( $metadata ) ){
			$images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $object_id );
			if ( !empty( $images ) ) {
				reset( $images );
				$metadata = key( $images );
				if ( is_int( $metadata ) && $metadata > 0 ) add_post_meta( $object_id, '_thumbnail_id', $metadata); //And now update the post with the correct thumbnail id.
			}
		}
		return $metadata;
	}



/* Remove all those extra links in the header */
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'adjacent_posts_rel_link');


// KILL RSS!!!!
//function fb_disable_feed() {
//    wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
//}
//add_action('do_feed', 'fb_disable_feed', 1);
//add_action('do_feed_rdf', 'fb_disable_feed', 1);
//add_action('do_feed_rss', 'fb_disable_feed', 1);
//add_action('do_feed_rss2', 'fb_disable_feed', 1);
//add_action('do_feed_atom', 'fb_disable_feed', 1);


// I want an /edit end point added on to make my life easier
	add_action( 'init', 'dco_add_on_edit_permalink_point' );
	function dco_add_on_edit_permalink_point() {
		add_rewrite_endpoint( 'edit', EP_PERMALINK | EP_PAGES );
	}
	
	add_action( 'template_redirect', 'dco_add_on_edit_permalink_point_template_redirect' );
	function dco_add_on_edit_permalink_point_template_redirect() {
	    global $wp_query;
	    global $post;
	    if ( isset( $wp_query->query_vars['edit'] )  &&  is_user_logged_in()  && is_singular() ) {
	        if ( have_posts() ) : while ( have_posts() ) : the_post(); 
	  	   	 wp_redirect( get_edit_post_link( $post->ID, '&') );
		   	 endwhile; endif;
	    	exit;
	    } else {
	    	return;
	    }
	}
	

/* Helper Functions */
	if ( ! function_exists( 'pre_print' ) ) :
	    function pre_print( $content ){
	        echo '<pre style="">'; print_r($content); echo '</pre>';
	    }
	endif;
	
	if ( ! function_exists( 'admin_print' ) && function_exists( 'pre_print' ) ) :
	    function admin_print( $content ){
	        if ( current_user_can( 'manage_options' ) ) pre_print( $content );
	    }
	endif;
?>
<?php

	add_action('wp_head','dco_add_credits', 99);
	function dco_add_credits(){ echo "<!-- This site was designed and coded by Danny Cohen - dannycohen.design -->";}

////////////////////
// BASE FUNCTIONS //
///////////////////


	///////////////////////////////////////     
	// Front end Stylesheets and Scripts //
	///////////////////////////////////////
	
	add_action('wp_enqueue_scripts', 'dco_theme_add_client_script_and_styles'); // Enqueue some scripts we'll be needing
	function dco_theme_add_client_script_and_styles() {
	    if ( is_admin() ) return;
	    
	    wp_enqueue_script( 'page-js', get_theme_file_uri( 'page.js') , array('jquery'), time(), true); // Let's register the page.js
	    wp_enqueue_style( 'main_style', get_bloginfo( 'stylesheet_url' ) , null, time() ); // Let's add our stylesheet from the functions.php
	    
	    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )   wp_enqueue_script( 'comment-reply' );

	}
	
	///////////////
	// ADMIN CSS //
	///////////////

    // And any custom admin css
    add_action( 'admin_enqueue_scripts', 'dco_admin_styles' );
    function dco_admin_styles() {
            wp_register_style( 'custom_wp_admin_css', get_bloginfo( 'stylesheet_directory' ) . '/admin_style.css', false, '1.0.0' );
            wp_enqueue_style( 'custom_wp_admin_css' );
    }

	/////////////////
	// THEME SETUP //
	/////////////////

    add_action( 'after_setup_theme', 'dco_theme_setup' );
    if ( ! function_exists( 'dco_theme_setup' ) ) {
        function dco_theme_setup(){
            // Enable thumbnails
            add_theme_support( 'post-thumbnails' );
            
            // Register a sidebar
            register_sidebars();
            
            // HTML5 Search Form
			add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
            
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
            
            //Title Tag
            add_theme_support( 'title-tag' );
            
			// Block Editor Support
           
          	add_theme_support( 'align-wide' );
          	add_theme_support( 'editor-styles' );
          	add_editor_style( 'editor-style.css' );
           
			add_theme_support( 'editor-color-palette', dco_wp_block_editor_custom_colors_setup() );
                
            
        
        }
    } // dco_theme_setup()
    

    
    function dco_wp_block_editor_custom_colors_setup(){
	    
	    $colors = apply_filters( 'dco_wp_block_editor_custom_colors', array() );
	    
	    $return_array = array();
	    
	    foreach( $colors as $name => $hexcode){
		    $return_array[] = array(
			    'name' => __( $name, 'dco_theme' ),
			    'slug' => sanitize_title($name),
			    'color' => $hexcode,
		    );
	    }
	    
	    return $return_array;
    }
   
	// Some default custom colors for the block editor. 
	add_filter( 'dco_wp_block_editor_custom_colors', 'dco_wp_block_editor_custom_colors_defaults', 1, 1 );
    function dco_wp_block_editor_custom_colors_defaults($colors){
	    return array(	 
	    	'Strong Red' => '#D80D04',
	    	'Orange'     => '#FB7D03',
	    	'Light Gray' => '#B2B8B2',
	    	'Dark Gray'  => "#444444"
	    );
    }
     
    
    // Customizer! //
    
    add_filter( 'dco_wp_block_editor_custom_colors', 'dco_wp_block_editor_custom_colors_for_customizer' , 10, 1);
    function dco_wp_block_editor_custom_colors_for_customizer($colors){
	    foreach( $colors as $name => $hexcode ){
	    	$colors[$name] = get_theme_mod( sanitize_title( $name ), $hexcode );
	    }
	    return $colors;
    }
    
    // Uncomment for the color customizer
    //add_action('customize_register','dco_theme_customize_register');
	function dco_theme_customize_register( $wp_customize ){
		
		$colors = apply_filters( 'dco_wp_block_editor_custom_colors', array() );
		
		foreach($colors as $name => $hexcode ){
			$wp_customize->add_setting(  sanitize_title( $name ) , array(
				'default' => $hexcode,
				'sanitize_callback' => 'sanitize_hex_color',
			));
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,  sanitize_title( $name ) , array(
				'label' =>   "Editor - " .  $name ,
				'section' => 'colors',
				'settings' =>   sanitize_title( $name ) ,
				'capability' => 'edit_theme_options',
			) ) );
		}
		

	}
	

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
 	add_filter( 'wp_title', 'dco_s_wp_title', 10, 2 );
	function dco_s_wp_title( $title, $sep ) {
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


// The header

	add_action('wp_head', 'dco_theme_header_tags');
	function dco_theme_header_tags(){ ?>
	    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	    <meta http-equiv="x-ua-compatible" content="ie=edge">
	    <meta name="description" content="<?php bloginfo('description'); ?>" />
	    <meta name="viewport" content="width=device-width" />
	<?php
	}


// Add a post thumbnail column, please
	add_filter('manage_posts_columns', 'dco_add_post_thumb_posts_columns', 5);
	add_action('manage_posts_custom_column', 'dco_add_post_thumb_posts_custom_columns', 5, 2);
	function dco_add_post_thumb_posts_columns($defaults){
	   unset( $defaults['cb'] );
	   return array( 'cb' => '',  'post_thumbs' => __('') ) + $defaults;
	}
	function dco_add_post_thumb_posts_custom_columns($column_name, $id){
	    if ( $column_name === 'post_thumbs' ) {
		   // add_filter('post_thumbnail_html', 'dco_post_thumbnail_html_filter_for_empty_thumbnail', $html);
	        echo '<a href="'. get_edit_post_link( $id, '&') .'">' .  get_the_post_thumbnail( $id, array(96,96) ) . '</a>';
	    }
	}
	
	function dco_post_thumbnail_html_filter_for_empty_thumbnail($html){
		if (!empty($html)) {
			return $html;
		} else {
			return '<span class="empty_thumbnail"></span>';
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


// DISABLE RSS!!!!
//function fb_disable_feed() {
//    wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
//}
//add_action('do_feed', 'fb_disable_feed', 1);
//add_action('do_feed_rdf', 'fb_disable_feed', 1);
//add_action('do_feed_rss', 'fb_disable_feed', 1);
//add_action('do_feed_rss2', 'fb_disable_feed', 1);
//add_action('do_feed_atom', 'fb_disable_feed', 1);


// I want an /edit end point added on to make my life easier. So, just add /edit to the end of any url to get the post edit page in the backend.
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
	    function admin_print( $content = ''){
		    
		    if ( empty( $content ) ) {
			    $backtrace = debug_backtrace();
			    $content = "<strong>Debug</strong> " . $backtrace[1]['function'] . " on Line " . $backtrace[0]['line'];
			}
		    
	        if ( current_user_can( 'manage_options' ) ) pre_print( $content );
	    }
	endif;
	
	
	//add_action( 'after_setup_theme', 'dco_theme_setup_starter_content' );
	function dco_theme_setup_starter_content(){
		add_theme_support( 'starter-content', array(
		    	// Place widgets in the desired locations (such as sidebar or footer).
		    	// Example widgets: archives, calendar, categories, meta, recent-comments, recent-posts, 
		    	//                  search, text_business_info, text_about
		    	'widgets'     => array( 'sidebar-1' => array( 'search', 'categories', 'meta'), ),
		    	// Specify pages to create, and optionally add custom thumbnails to them.
		    	// Note: For thumbnails, use attachment symbolic references in {{double-curly-braces}}.
		    	// Post options: post_type, post_title, post_excerpt, post_name (slug), post_content, 
		    	//               menu_order, comment_status, thumbnail (featured image ID), and template
		    	'posts'       => array( 'home' => array('post_content' => 'This is the homepage'), 'about', 'blog', ),
		    	// Create custom image attachments used as post thumbnails for pages.
		    	// Note: You can reference these attachment IDs in the posts section above. Example: {{image-cafe}}
		    	//'attachments' => array( 'image-cafe' => array( 'post_title' => 'Cafe', 'file' => 'assets/images/cafe.jpg' ), ),
		    	// Assign options defaults, such as front page settings.
		    	// The 'show_on_front' value can be 'page' to show a specified page, or 'posts' to show your latest posts.
		    	// Note: Use page ID symbolic references from the posts section above wrapped in {{double-curly-braces}}.
		    	'options'     => array( 'show_on_front'  => 'page', 'page_on_front' => '{{home}}', 'page_for_posts' => '{{blog}}', ),
		    	// Set up nav menus.
		    	'nav_menus'   => array(
		    		'main_menu' => array( 'name' => 'Main Menu', 'items' => array( 'link_home', 'page_about', 'page_blog' )),
		    		'social_menu' => array( 'name'  => 'Social Links Menu', 'items' => array( 'link_facebook', 'link_twitter', 'link_instagram')),
		    		 )
		    	
		    ));

	}	



	
	
?>
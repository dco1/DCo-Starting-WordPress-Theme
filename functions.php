<?php
include('functions_base.php');



        
//Stylesheets and Scripts
	
	add_action('wp_enqueue_scripts', 'dco_theme_add_client_script_and_styles'); // Enqueue some scripts we'll be needing
	function dco_theme_add_client_script_and_styles() {
	    if ( is_admin() ) return;
	    
	    wp_enqueue_script( 'page-js', get_theme_file_uri( 'page.js') , array('jquery'), time(), true); // Let's register the page.js
	    wp_enqueue_style( 'main_style', get_bloginfo( 'stylesheet_url' ) , null, time() ); // Let's add our stylesheet from the functions.php
	    
	    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )   wp_enqueue_script( 'comment-reply' );

	}
	


/* Adding a hamburger mobile nav menu item and cooresponding SVG reading code */

	add_filter('wp_nav_menu', 'dco_wp_nav_menu_for_adding_hamburger_menu_item', 10, 2);
	function dco_wp_nav_menu_for_adding_hamburger_menu_item(  $nav_menu, $args ){
		
		if ($args->container_id == 'main-header-menu' ){
			return '<a href="#" id="toggle-mobile-nav">' . ss_svg_code('hamburger.svg') . '</a>' . $nav_menu;

		}
		return $nav_menu;
	}

	function ss_svg_code($svg_filename){
		return file_get_contents( get_template_directory() .  "/images/" . $svg_filename);
	}

?>
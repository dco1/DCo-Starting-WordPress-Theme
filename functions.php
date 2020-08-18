<?php
include('functions_base.php');
	
////////////////
// Mobile Nav //
////////////////

	// Adding a hamburger mobile nav menu item and cooresponding SVG reading code
	add_filter('wp_nav_menu', 'dco_wp_nav_menu_for_adding_hamburger_menu_item', 10, 2);
	function dco_wp_nav_menu_for_adding_hamburger_menu_item(  $nav_menu, $args ){
		
		if ($args->container_id == 'main-header-menu' ){
			return '<a href="#" id="toggle-mobile-nav">' . dco_theme_svg_code('hamburger.svg') . '</a>' . $nav_menu;

		}
		return $nav_menu;
	}

	// Inline SVG
	function dco_theme_svg_code($svg_filename){
		return file_get_contents( get_template_directory() .  "/images/" . $svg_filename);
	}

/////////////////////////////////////////
// Article Header, Content, and Footer //
/////////////////////////////////////////

	//// How to control what appears for a post or page. First, we go to the index.php, which calls an article template. The default article is article.php, but there is the option to also provide article-page.php or article-custom-post-type.php
	//// Then, there are three parts of an article: Header, Content, and Footer. These three parts are called as actions (article_header, article_content, and article_footer).

	add_action('article_header', 'dco_theme_article_header', 10, 1);
	function dco_theme_article_header($post){
		?>
		<h3 class="article-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<?php
	}
	
	add_action('article_content', 'dco_theme_article_content', 10, 1);
	function dco_theme_article_content($post){
		the_content(); 
	}
	
	
//////////////////
// Block Editor //
//////////////////

	// Custom Colors	
	add_filter( 'dco_wp_block_editor_custom_colors', 'dco_theme_custom_colors', 1, 1 );
	function dco_theme_custom_colors( $colors ){
		return array("Text Black" => "#333333");
	}

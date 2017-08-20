<?php
include('functions_base.php');


        
//Stylesheets and Scripts

add_action('wp_enqueue_scripts', 'theme_add_script_and_styles'); // Enqueue some scripts we'll be needing
function theme_add_script_and_styles() {
    if ( is_admin() ) return
    wp_enqueue_script( 'page-js', get_bloginfo( 'stylesheet_directory' ) . '/page.js', array('jquery'), time(), true); // Let's register the page.js
    wp_enqueue_style( 'main_style', get_bloginfo( 'stylesheet_url' ) ); // Let's add our stylesheet from the functions.php
}


?>
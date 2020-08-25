<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
    <div id="container">
    	<?php do_action('before_header'); ?>
    	<header role="banner"><div class="wrapper">
    	    <h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
	    	<div class="header-menus">
		    <?php wp_nav_menu(array('theme_location' => 'social_menu', 'container' => 'nav' ,  'container_id' => 'social-header-menu')); ?> 
    	    <?php wp_nav_menu(array('theme_location' => 'main_menu', 'container' => 'nav' ,  'container_id' => 'main-header-menu')); ?> 
	    	</div>
    	</div></header>
    	<?php do_action('after_header'); ?>
    	<div id="main">

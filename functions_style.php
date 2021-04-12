<?php

// Functions for theme style customizations that, generally, are also used in the block editor

add_action('after_setup_theme', 'dco_theme_setup_styles');
if (! function_exists('dco_theme_setup_styles')) {
    function dco_theme_setup_styles()
    {
        // Colors
        add_theme_support("disable-custom-colors");
        add_theme_support("editor-color-palette", dco_wp_block_editor_custom_colors_setup());

        // Font Sizes
        add_theme_support("editor-font-sizes", dco_wp_block_editor_custom_font_sizes_setup());
    }
}

add_action("wp_head", "dco_wp_block_editor_custom_head_css");
function dco_wp_block_editor_custom_head_css()
{
    echo '<style type="text/css">';
    foreach (apply_filters('dco_wp_block_editor_custom_css', array()) as $custom_css) {
        echo $custom_css;
    }
    echo '</style>';
}

// Font Sizes //


add_filter('dco_wp_block_editor_custom_font_sizes', 'dco_wp_block_editor_custom_font_sizes_defaults', 1, 1);
function dco_wp_block_editor_custom_font_sizes_defaults($sizes)
{
    return array(
        'Small'     => 12,
        'Regular'   => 18,
        'Large'     => 36,
        'Huge'      => 52
    );
}


add_filter('dco_wp_block_editor_custom_css', 'dco_wp_block_editor_custom_font_sizes_wp_head_css', 1, 1);
function dco_wp_block_editor_custom_font_sizes_wp_head_css($custom_css_rules)
{
    $sizes = dco_wp_block_editor_custom_font_sizes_setup();
    if (empty($sizes)) {
        return;
    }

    foreach ($sizes as $size) {
        $custom_css_rules[] = "--".THEME_LANGDOMAIN."-font-size-" . $size["slug"]  . ":".  $size["size"] . "px;";
        $custom_css_rules[] =  ".has-". $size["slug"] . "-font-size { font-size: ". $size["size"] . "px;}";
    }

    return $custom_css_rules;
}



function dco_wp_block_editor_custom_font_sizes_setup()
{
    $sizes = apply_filters('dco_wp_block_editor_custom_font_sizes', array());
    foreach ($sizes as $name => $size) {
        $return_array[] = array(
        'name' => __($name, THEME_LANGDOMAIN),
        'slug' => sanitize_title($name),
        'size' => $size,
    );
    }
    return $return_array;
}

// Colors //
// Some default custom colors for the block editor.
add_filter('dco_wp_block_editor_custom_colors', 'dco_wp_block_editor_custom_colors_defaults', 1, 1);
function dco_wp_block_editor_custom_colors_defaults($colors)
{
    return array(
        'Strong Red' => '#D80D04',
        'Orange'     => '#FB7D03',
        'Light Gray' => '#B2B8B2',
        'Dark Gray'  => "#444444"
    );
}



function dco_wp_block_editor_custom_colors_setup()
{
    $colors = apply_filters('dco_wp_block_editor_custom_colors', array());

    $return_array = array();

    foreach ($colors as $name => $hexcode) {
        $return_array[] = array(
            'name' => __($name, 'dco_theme'),
            'slug' => sanitize_title($name),
            'color' => $hexcode,
        );
    }

    return $return_array;
}

add_filter('dco_wp_block_editor_custom_css', 'dco_wp_block_editor_custom_colors_wp_head_css', 2, 1);
function dco_wp_block_editor_custom_colors_wp_head_css($custom_css_rules)
{
    $colors = dco_wp_block_editor_custom_colors_setup();
    if (empty($colors)) {
        return;
    }

    foreach ($colors as $color) {
        $custom_css_rules[] = "--".THEME_LANGDOMAIN."-color-" . $color["slug"]  . ":".  $color["color"] . ";";
        $custom_css_rules[] = ".has-". $color["slug"] . "-color { color:". $color["color"] . ";}";
        $custom_css_rules[] = ".has-". $color["slug"] . "-color a { color:". $color["color"] . ";}";
        $custom_css_rules[] = ".has-". $color["slug"] . "-background-color {background-color:". $color["color"] . ";}";
    }

    return $custom_css_rules;
}





// Customizer! //

add_filter('dco_wp_block_editor_custom_colors', 'dco_wp_block_editor_custom_colors_for_customizer', 10, 1);
function dco_wp_block_editor_custom_colors_for_customizer($colors)
{
    foreach ($colors as $name => $hexcode) {
        $colors[$name] = get_theme_mod(sanitize_title($name), $hexcode);
    }
    return $colors;
}

// Uncomment for the color customizer
//add_action('customize_register','dco_theme_customize_register');
function dco_theme_customize_register($wp_customize)
{
    $colors = apply_filters('dco_wp_block_editor_custom_colors', array());

    foreach ($colors as $name => $hexcode) {
        $wp_customize->add_setting(sanitize_title($name), array(
            'default' => $hexcode,
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, sanitize_title($name), array(
            'label' =>   "Editor - " .  $name ,
            'section' => 'colors',
            'settings' =>   sanitize_title($name) ,
            'capability' => 'edit_theme_options',
        )));
    }
}

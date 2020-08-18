<?php

get_header();


// Remove this in production
global $wp_query;

admin_print($wp_query);

get_footer();
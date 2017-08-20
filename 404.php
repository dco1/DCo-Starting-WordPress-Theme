<?php

get_header();

global $wp_query;

admin_print($wp_query);

get_footer();
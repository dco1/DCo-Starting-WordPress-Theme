<?php
get_header();

if ( wp_get_environment_type() == "development"){
	// Remove this in production
	global $wp_query;
	admin_print($wp_query);
}

?>
<h2 class="uhoh error error404 404">404</h2>
<?php

get_footer();
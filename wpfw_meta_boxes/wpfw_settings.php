<?php
// styles and scripts for front-end
if (!is_admin()) {
	// do not remove these lines - these are calling the wp_enqueue_scripts for css and js inclusion
	add_action('wp_enqueue_scripts', 'wpfw_p_enqueue_core_scripts');
	add_action('wp_enqueue_scripts', 'wpfw_p_enqueue_core_styles');
}

// styles and scripts for back-end
if (is_admin()) {
	
	$css_library['wpfw-meta-boxes-style'] = array(plugins_url( 'styles/metaboxes.css' , __FILE__ ), '1.0');
	// do not remove these lines - these are calling the wp_enqueue_scripts for css and js inclusion
	add_action('admin_enqueue_scripts', 'wpfw_p_enqueue_core_scripts');
	add_action('admin_enqueue_scripts', 'wpfw_p_enqueue_core_styles');	
}

?>
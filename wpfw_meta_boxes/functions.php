<?php
if (!function_exists("get_wp_path")) {
	function get_wp_path($filename) {
		$url = explode("wp-content", dirname( __FILE__ ));
		if (count($url) <= 1) {
			$url = explode("wp-admin", dirname( __FILE__ ));
			if (count($url) <= 1) {
				$url[0] = dirname( __FILE__ )."/";
			}
		}	
		return $url[0].$filename;
	}
}

if (!function_exists("wpfw_p_enqueue_core_scripts")) {
	function wpfw_p_enqueue_core_scripts() {
		global $wp_library, $js_library;
		
		if (count($wp_library)) {
			foreach($wp_library as $wp_script_name) {	
				wp_enqueue_script($wp_script_name); 
			}
		}
		
		if (count($js_library)) {
			foreach($js_library as $key => $path) {	
				wp_enqueue_script(
					$key,
					$path[0],
					array('jquery'),
					$path[1],
					true
				);				
			}
		}
	}
}

if (!function_exists("wpfw_p_enqueue_core_styles")) {
	
	function wpfw_p_enqueue_core_styles() {
		global $css_library;
		if (count($css_library)) {
			foreach($css_library as $key => $path) {
				if(!isset($path[2])) $path[2] = 'all';
				wp_register_style($key, $path[0], '', $path[1], $path[2]);
				wp_enqueue_style($key);
			}
		}
	
	}
}

if (!function_exists("wpfwcurPageURL")) {
	function wpfwcurPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}  
}

if (!function_exists("wpfw_get_sidebar_info")) {
	function wpfw_get_sidebar_info($sidebar_id, $out = '') {
	 global $wp_registered_sidebars;
	 
	 if(!$out) {
	 	 return $wp_registered_sidebars[$sidebar_id];
	 }
	 else {
	 	 return $wp_registered_sidebars[$sidebar_id][$out];
	 }
	 
	 
	}  
}

if (!function_exists("wpfw_create_tables")) {
	function wpfw_create_tables($tables=array()) {
		global $wpdb;
		
		if (count($tables) > 0) {
			
			foreach($tables as $key => $table) {
				
				$table_name = $key;
				
				$sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix.$table_name."` (
				  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
				   PRIMARY KEY (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
					$wpdb->query($sql);
				
				if(count($table) > 0) {
					
					foreach($table as $key => $type) {
						wpfw_add_db_columns($table_name, $key, $type);
						
					}
					
				}
			}
			
		}
		
	}
}

if (!function_exists("wpfw_add_db_columns")) {
	function wpfw_add_db_columns($table_name, $column_name, $column_type) {
		global $wpdb;
		$check = $wpdb->get_results("SHOW COLUMNS FROM `".$table_name."` LIKE '".$column_name."'");
		if(count($check) < 1) {
			$wpdb->query("ALTER TABLE `".$wpdb->prefix.$table_name."` ADD COLUMN `".$column_name."` ".$column_type." NOT NULL");
		}
	}  
}

if(isset($external)) {
	require_once(get_wp_path('wp-load.php'));
}
require_once(get_wp_path('wp-includes/pluggable.php'));
require_once(get_wp_path('wp-admin/includes/upgrade.php'));
if(isset($external)) {
	require_once(get_wp_path('wp-admin/includes/image.php'));
}
global $wpdb;


?>
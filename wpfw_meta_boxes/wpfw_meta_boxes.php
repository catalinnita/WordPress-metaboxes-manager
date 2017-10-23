<?php
/*
Plugin Name: WPFW - Meta Boxes
Plugin URI: http://www.WordPressForward.com/wordpress-meta-boxes
Description: Add custom meta boxes on any post type you like
Author: Catalin Nita
Author URI: http://www.WordPressForward.com
*/

include('wpfw_settings.php');
include('functions.php');
include('wpfw_data.php');

add_action('admin_menu', 'wpfw_metabox_settings');  
function wpfw_metabox_settings() {
	add_menu_page('Meta Boxes', __('Meta Boxes', 'wpfw'), 'administrator', 'wpfw-metaboxes', 'wpfw_metaboxes', '');
	//add_submenu_page('Fonts', 'Upload Fonts', __('Upload Fonts', 'wpfw'), 'administrator', 'upload_fonts', 'upload_fonts');	
}

function wpfw_metaboxes() {
	global $wpdb;

	if (!current_user_can('edit_posts'))
		return;

	if ($_POST['mb_title']) 
		wpfw_save_metaboxes();

	?>
	<div class="wrap">
		<h2>Manage custom metaboxes</h2>
		
		<div id="col-container">
		
			<div id="col-right">
				<table class="widefat clearfix">
					<thead>
						<tr>
							<th>Type</th>
							<th>Name</th>
							<th>Desc</th>
							<th>Post types</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$metaboxes = wpfw_get_metaboxes();
						foreach($metaboxes as $mb) {
						?>
						<tr>
							<td><?php echo ($mb->mb_type == 0) ? 'Side' : 'Normal'; ?></td>
							<td><?php echo $mb->mb_title; ?></td>
							<td><?php echo $mb->mb_desc; ?></td>
							<td>
								<?php 
								$post_types = unserialize($mb->mb_cpt);
								foreach($post_types as $post_type) {
									echo '<a href="#" class="cpt">'.$post_type.'</a>';
								}
								?>
							</td>
							<td>
								<a href="<?php echo wpfw_set_url('&edit_id='.$mb->ID, '_wpnonce'.$mb->ID); ?>" class="button-secondary">Edit</a>
								<a href="<?php echo wpfw_set_url('&delete_id='.$mb->ID, '_wpnonce'.$mb->ID); ?>" class="button-secondary">Delete</a>
								<a href="<?php echo wpfw_set_url('&fields_id='.$mb->ID, '_wpnonce'.$mb->ID); ?>" class="button-primary">Add fields</a>
							</td>
						</tr>		
						<?php
						}
						?>
					</tbody>
				</table>
			</div>


			<div id="col-left">
				
				<div class="form-wrap">
					
					<h3>Add New Metabox</h3>
					<form method=POST action="admin.php?page=wpfw-metaboxes">
					
						<?php
						wp_nonce_field( 'create_metabox' );
						?>


						<!-- metabox title -->
						<div class="form-field">
						<label>Metabox title</label>
						<input type="text" name="mb_title">
						<p>This is the title that appears on top of your metabox</p>
						</div>

						<!-- metabox title -->
						<div class="form-field">
						<label>Metabox description</label>
						<textarea name="mb_desc"></textarea>
						<p>This is the description that appears inside your metabox</p>
						</div>

						<!-- metabox position -->
						<div class="form-field">
						<label>Metabox position</label>
						<select name="mb_position">
							<option value="side">Side</option>
							<option value="normal">Normal</option>
						</select>
						<p>Select where on page you want to show the metabox</p>
						</div>

						<!-- metabox priority -->
						<div class="form-field">
						<label>Metabox priority</label>
						<input type="text" name="mb_priority">
						<p>Please select the position of metabox compared with oher metaboxes. Add a number from 0 to 10000. Lower number will place the metabox above.</p>
						</div>

						<!-- custom post types -->
						<div class="form-field">
						<label>Post types</label>
						<?php
						$post_types = wpfw_get_valid_posttypes();
						foreach($post_types as $post_type) {
							?>
							<div>
							<input type="checkbox" name="mb_<?php echo $post_type; ?>" value="on" /> <?php echo $post_type; ?>
							</div>	
							<?php
						}
						?>
						<p>Please select on what post types you want to display the metabox</p>

						<p class="submit"><input type="submit" value="Create metabox" class="button-primary" /></p>
						</div>

					</form>


				</div>

			</div>

		</div>

	</div>
	<?php
	
}


function wpfw_save_metaboxes() {
	global $wpdb;

	if(!$_REQUEST['_wpnonce'] || !wp_verify_nonce( $_REQUEST['_wpnonce'], 'create_metabox' ))
		return;

	$cpt = array();
	$post_types = wpfw_get_valid_posttypes();
	foreach($post_types as $post_type) {
		if($_POST['mb_'.$post_type] == 'on')
			$cpt[] = $post_type;
	}

	$metabox_v = array(
		'mb_title' => $_POST['mb_title'],
		'mb_desc' => $_POST['mb_desc'],
		'mb_position' => $_POST['mb_position'],
		'mb_priority' => $_POST['mb_priority'],
		'mb_cpt' => serialize($cpt)
	);
	$metabox_t = array(
		'%s',
		'%s',
		'%d',
		'%d',
		'%s',
	);

	$wpdb->insert( 
		$wpdb->prefix.'wpfw_metaboxes', 
		$metabox_v,
		$metabox_t
	);

}

function wpfw_get_valid_posttypes() {

	$post_exclude = array('attachment', 'revision', 'nav_menu_item');
	$post_types = array_diff(get_post_types(), $post_exclude);

	return $post_types;
}

function wpfw_get_metaboxes() {
	global $wpdb;

	$metaboxes = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wpfw_metaboxes");
	return $metaboxes;

}

function wpfw_set_url($custom_var, $nonce_name) {
	return wp_nonce_url('admin.php?page='.$_GET['page'].$custom_var, $nonce_name);
}

?>
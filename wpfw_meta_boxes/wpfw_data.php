<?php
$wpfw_metaboxes_tables = array(
	'wpfw_metaboxes' => array(
		'mb_title' => 'varchar (255)',
		'mb_desc' => 'text',
		'mb_position' => 'boolean',
		'mb_priority' => 'bigint (20)',
		'mb_cpt' => 'varchar (255)'
	)
);

wpfw_create_tables($wpfw_metaboxes_tables);
?>
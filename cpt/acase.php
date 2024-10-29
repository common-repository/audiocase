<?php

function audiocase_register_acase() {

	/**
	 * Post Type: audioCases.
	 */

	$labels = [
		"name" => __( "audioCases" ),
		"singular_name" => __( "audioCase" ),
		"menu_name" => __( "audioCase" ),
		"all_items" => __( "All audioCases" ),
		"add_new" => __( "Add New" ),
		"add_new_item" => __( "Add New audioCase" ),
		"edit_item" => __( "Edit audioCase" ),
		"new_item" => __( "New audioCase" ),
		"view_item" => __( "View audioCase" ),
		"view_items" => __( "View audioCases" ),
		"search_items" => __( "Search audioCases" ),
		"not_found" => __( "No audioCases Found" ),
		"not_found_in_trash" => __( "No audioCases in Trash" ),
		"featured_image" => __( "Featured image for this audioCase" ),
		"set_featured_image" => __( "Set Image" ),
		"remove_featured_image" => __( "Remove Image" ),
		"use_featured_image" => __( "Use This Image" ),
		"archives" => __( "audioCase Archives" ),
		"uploaded_to_this_item" => __( "Uploaded to this audioCase" ),
		"filter_items_list" => __( "Filter audioCases List" ),
		"items_list_navigation" => __( "audioCases List Navigation" ),
		"items_list" => __( "audioCases List" ),
		];

	$args = [
		"label" => __( "audioCases" ),
		"labels" => $labels,
		"description" => "Create individual audioCase song displays",
		"public" => false,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "acase", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title" ],
		"menu_icon" => "dashicons-album",

	];

	register_post_type( "acase", $args );
}

add_action( 'init', 'audiocase_register_acase' );



if( function_exists('acf_add_local_field_group') ):

 acf_add_local_field_group(array(
	'key' => 'group_605ceb4957f62',
	'title' => 'audioCase Details',
	'fields' => array(
 		array(
			'key' => 'field_605cebd9acf62',
			'label' => 'Song File',
			'name' => 'audiocase_song',
			'type' => 'file',
			'instructions' => 'Your song file must be < 15MB.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'library' => 'all',
			'min_size' => '',
			'max_size' => 15,
			'mime_types' => 'mp3',
		),
		array(
			'key' => 'field_605cecdcacf63',
			'label' => 'Cover Art',
			'name' => 'audiocase_cover',
			'type' => 'image',
			'instructions' => 'Image must be square ratio (1:1). Minimum dimensions: 720x720px, Maximum: 1500x1500px.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => 720,
			'min_height' => 720,
			'min_size' => '',
			'max_width' => 1500,
			'max_height' => 1500,
			'max_size' => 5,
			'mime_types' => 'jpg,jpeg,png,gif,webp',
		),
		array(
			'key' => 'field_605cf583acf64',
			'label' => 'Viewer Action',
			'name' => 'audiocase_action',
			'type' => 'radio',
			'instructions' => 'Update this if you\'d like the listener to download or purchase your song.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'download' => 'Download',
				'buy' => 'Buy',
				'no' => 'No Action',
			),
			'allow_null' => 0,
			'other_choice' => 0,
			'default_value' => 'no',
			'layout' => 'horizontal',
			'return_format' => 'value',
			'save_other_choice' => 0,
		),
		array(
			'key' => 'field_605cfcdcacf65',
			'label' => 'Download Link',
			'name' => 'audiocase_download',
			'type' => 'url',
			'instructions' => 'This URL can be the same as your song file or this can be used to provide a "high quality" download file (WAV, FLACC, ZIP, etc.) that you\'ve uploaded here or elsewhere.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_605cf583acf64',
						'operator' => '==',
						'value' => 'download',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'https://mysite.com/mydownloadfile.zip',
		),
		array(
			'key' => 'field_605cfe9bacf66',
			'label' => 'Buy Link',
			'name' => 'audiocase_buy',
			'type' => 'url',
			'instructions' => 'This URL can be a link to any site or digital store you\'d like the listener to purchase your track from.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_605cf583acf64',
						'operator' => '==',
						'value' => 'buy',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'https://songstore.com/mysong',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'acase',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array(
		0 => 'permalink',
		1 => 'the_content',
		2 => 'excerpt',
		3 => 'discussion',
		4 => 'comments',
		5 => 'revisions',
		6 => 'slug',
		7 => 'author',
		8 => 'format',
		9 => 'page_attributes',
		10 => 'featured_image',
		11 => 'categories',
		12 => 'tags',
		13 => 'send-trackbacks',
	),
	'active' => true,
	'description' => '',
));

endif;


?>

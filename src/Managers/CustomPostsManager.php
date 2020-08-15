<?php
/**
 * Bootstraps settings and configurations for custom post types.
 *
 * @package Skela
 */

namespace Skela\Managers;

/** Class */
class CustomPostsManager {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'init', array( $this, 'register_post_types' ), 1 );
	}


	/**
	 * Register post types in WoPostsrdPress
	 *
	 * @return void
	 */
	public function register_post_types() {
		/*
		// This is an example of the `register_post_type` call. Note that you should
		// *not* register an "author" post type since that is a reserved type in
		// WordPress. This code is here for referene only.
		// Author post type.
		$author_labels = array(
			'name'          => __( 'Authors' ),
			'singular_name' => __( 'Author' ),
			'add_new_item'  => __( 'Add New Author' ),
		);

		register_post_type(
			'author',
			array(
				'labels'        => $author_labels,
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'rewrite'       => array( 'slug' => 'authors' ),
				'supports'      => array( 'editor', 'excerpt', 'title', 'thumbnail' ),
				'taxonomies'    => array(),
			)
		);
		*/
	}
}

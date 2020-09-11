<?php
/**
 * Custom block for creating image layouts
 *
 * Requires an Image Layout field group with three custom fields:
 * - imageLayoutImages (Gallery)
 * - imageLayoutLayout (Radio Button) with the following choices
 *     - 2-symmetrical : 2 Symmetrical
 *     - 2-asymmetrical : 2 Asymmetrical
 *     - 3-symmetrical : 3 Symmetrical
 *     - 3-asymmetrical : 3 Asymmetrical
 * - imageLayoutCrop (True / False)
 * Set this field group if the block is equal to Image Layout.
 *
 * @package Skela
 */

namespace Skela\Blocks\ImageLayout;

use Timber\Timber;
use Timber\PostQuery;

/** Class */
class ImageLayout {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'acf/init', array( $this, 'register' ) );
	}

	/**
	 * Uses ACF function to register custom blocks.
	 *
	 * @return void
	 */
	public function register() {
		if ( function_exists( 'acf_register_block' ) ) {
			acf_register_block_type(
				array(
					'name'            => 'imageLayout',
					'title'           => __( 'Image Layout' ),
					'description'     => __( 'A custom image block.' ),
					'render_callback' => array( $this, 'render' ),
					'category'        => 'formatting',
					'icon'            => 'images-alt',
					'keywords'        => array( 'image', 'layout' ),
					'mode'            => 'edit',
				)
			);
		}
	}

	/**
	 * Get info from the related ACF fields and then render corrosponding template.
	 *
	 * @param array  $block      The block settings and attributes.
	 * @param string $content    The block content (empty content).
	 * @param bool   $is_preview True during AJAX preview.
	 *
	 * @return void
	 */
	public function render( $block, $content, $is_preview ) {
		$context['layout']          = get_field( 'imageLayoutLayout' );
		$context['cropToSameRatio'] = get_field( 'imageLayoutCrop' );

		$context['images'] = array_map(
			function ( $image ) {
				return new \TimberImage( $image['id'] );
			},
			get_field( 'imageLayoutImages' )
		);

		$templates = array( 'templates/components/image-layout.twig' );

		if ( $is_preview ) {
			echo 'Preview mode is not supported for related articles. Please change to Edit mode by clicking the pencil icon in the toolbar above.';
		} else {
			Timber::render( $templates, $context );
		}
	}
}

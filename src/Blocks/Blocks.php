<?php
/**
 * Blocks Initializer. Enqueue CSS/JS of all the blocks.
 *
 * @package Skela
 */

namespace Skela\Blocks;

/** Class */
class Blocks {

	/**
	 * Enqueue assets needed for block
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );

		new SampleACFBlock\ACFBlock();
		new RelatedArticles\RelatedArticles();
		new ImageLayout\ImageLayout();

	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {
		// Scripts.
		wp_enqueue_script(
			'block-js', // Handle.
			SKELA_THEME_URL . '/dist/blocks.js', // Block.block.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'manifest', 'vendor', 'wp-editor' ), // Dependencies, defined above.
			true // Enqueue the script in the footer.
		);

		// Styles.
		wp_enqueue_style(
			'block-editor-css', // Handle.
			SKELA_THEME_URL . '/dist/blocks.css', // Block editor CSS.
			array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
			SKELA_THEME_VERSION
		);
	}
}

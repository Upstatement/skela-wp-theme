<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 */
namespace Skela\Blocks;

class Blocks
{
    /**
     * Enqueue assets needed for block
     */
    public function __construct()
    {
        add_action('enqueue_block_editor_assets', array($this,'enqueueBlockEditorAssets'));

        new SampleACFBlock\ACFBlock();
        new RelatedArticles\RelatedArticles();
        new ImageLayout\ImageLayout();

    }

    /**
     * Enqueue Gutenberg block assets for backend editor.
     *
     * @return null
     */
    public function enqueueBlockEditorAssets()
    {
        // Scripts.
        wp_enqueue_script(
            'block-js', // Handle.
            SKELA_THEME_URL . '/dist/blocks.build.js', // Block.block.build.js: We register the block here. Built with Webpack.
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'manifest', 'vendor', 'wp-editor'), // Dependencies, defined above.
            true // Enqueue the script in the footer.
        );

        // Styles.
        wp_enqueue_style(
            'block-editor-css', // Handle.
            SKELA_THEME_URL . '/dist/block-editor.build.css', // Block editor CSS.
            array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
        );
    }
}

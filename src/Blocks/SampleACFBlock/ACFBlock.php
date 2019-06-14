<?php
/**
 * Example for creating custom block that uses ACF fields
 *
 * In order to have this example work, you first have to create
 * a new custom field block with two ACF fields named some_headline and some_text,
 * and show the field group if the block is equal to ACF Block
 */
namespace Skela\Blocks\SampleACFBlock;

use Timber\Timber;

class ACFBlock
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('acf/init', array($this,'createACFBlock'));
    }
    /**
     * Uses ACF function to register custom blocks
     *
     * @return void
     */
    public function createACFBlock()
    {
        if (function_exists('acf_register_block_type')) {
            acf_register_block_type(
                array(
                    'name'            => 'acfBlock',
                    'title'           => __('ACF Block'),
                    'description'     => __('A custom block that incorporates ACF fields.'),
                    'render_callback' => array($this, 'renderACFBlock'),
                    'category'        => 'widgets',
                    'icon'            => array('background' => '#ecf6f6', 'src' => 'email'),
                    'keywords'        => array('example', 'acf'),
                    'mode'            => 'edit'
                )
            );
        }
    }
    /**
     * Get info from the related ACF fields
     * and then render corrosponding template
     *
     * @param array  $block      The block settings and attributes.
     * @param string $content    The block content (empty content).
     * @param bool   $is_preview True during AJAX preview.
     *
     * @return void
     */
    public function renderACFBlock($block, $content, $is_preview)
    {
        // If the block renders info from TimberTheme, TimberSite, etc., then uncomment the following:
        // $context = Timber::get_context();

        $context['some_headline'] = get_field('some_headline');
        $context['some_text'] = get_field('some_text');

        Timber::render(['templates/components/acf-block.twig'], $context);
    }
}

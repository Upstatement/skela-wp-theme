<?php
/**
 * Example for creating custom block that uses ACF fields
 */
namespace Skela\Blocks\ACFBlock;

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
        // check function exists
        if (function_exists('acf_register_block')) {
         // register RelatedArticles block
            acf_register_block(
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
        $templates = ['templates/components/acf-block.twig', 'templates/components/acf-block.twig'];

        // If the block renders info from TimberTheme, TimberSite, etc., then uncomment the following:
        // $context = Timber::get_context();

        $context['some_headline'] = get_field('some_headline');
        $context['some_text'] = get_field('some_text');

         // ob_start();
        if ($is_preview) {
            echo "Preview mode is not supported for related articles. Please change to Edit mode by clicking the pencil icon in the toolbar above.";
        } else {
            Timber::render($templates, $context);
        }
    }
}

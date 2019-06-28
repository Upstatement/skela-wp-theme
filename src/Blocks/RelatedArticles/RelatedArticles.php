<?php

/**
 * Custom block for inserting a list of Related articles
 *
 * Requires an Related Articles field group with two custom fields:
 * - header_text (Text)
 * - chosen_articles (Repeater) with the following sub field:
 *     - related_article (Post Object)
 * Set this field group if the block is equal to Related Articles
 */
namespace Skela\Blocks\RelatedArticles;

use Timber\Timber;
use Timber\PostQuery;

class RelatedArticles
{
    /**
     * Constructor
     */
    public function __construct()
    {
                add_action('acf/init', array($this,'createRelatedArticlesBlock'));
    }

    /**
     * Uses ACF function to register custom blocks
     *
     * @return void
     */
    public function createRelatedArticlesBlock()
    {
        // check function exists
        if (function_exists('acf_register_block')) {

        // register RelatedArticles block
            acf_register_block(
                array(
                    'name'            => 'relatedArticles',
                    'title'           => __('Related Articles'),
                    'description'     => __('A custom block for inserting links to other articles.'),
                    'render_callback' => array($this, 'renderRelatedArticlesBlock'),
                    'category'        => 'widgets',
                    'icon'            => array('background' => '#ecf6f6', 'src' => 'list-view'),
                    'keywords'        => array('related', 'articles'),
                    'mode'            => 'edit'
                )
            );
        }
    }

    /**
     * Get the Related Articles text and related articles,
     * and then render corrosponding template
     *
     * @param array  $block      The block settings and attributes.
     * @param string $content    The block content (empty content).
     * @param bool   $is_preview True during AJAX preview.
     *
     * @return void
     */
    public function renderRelatedArticlesBlock($block, $content, $is_preview)
    {
        $context = Timber::get_context();
        $context['relatedArticlesHeader'] = get_field('header_text');
        $relatedArticles = get_field('chosen_articles');
        $relatedArticlesIDs = [];

        if (!empty($relatedArticles)) {
            if (is_array($relatedArticles) && sizeof($relatedArticles) > 0) {
                foreach ($relatedArticles as $article) {
                    if (!empty($article['related_article'])) {

                        $relatedArticlesIDs[] = $article['related_article']->ID;
                    }
                }
                // Set query args
                if (!empty($relatedArticlesIDs)) {
                    $args = array(
                        'post_status' => 'publish',
                        'post__in' => $relatedArticlesIDs,
                        'orderby' => 'post__in'
                    );

                    $context['relatedArticles'] = new PostQuery($args);

                }
            }
        }

        if ($is_preview) {
            echo "Preview mode is not supported for related articles. Please change to Edit mode by clicking the pencil icon in the toolbar above.";
        } else {
            Timber::render('templates/components/related-articles.twig', $context);
        }
    }
}

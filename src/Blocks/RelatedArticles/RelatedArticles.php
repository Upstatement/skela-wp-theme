<?php
/**
 * Custom block for inserting a list of Related articles
 *
 * Requires an Related Articles field group with two custom fields:
 * - header_text (Text)
 * - chosen_articles (Repeater) with the following sub field:
 *     - related_article (Post Object)
 * Set this field group if the block is equal to Related Articles.
 *
 * @package Skela
 */

namespace Skela\Blocks\RelatedArticles;

use Timber\Timber;
use Timber\PostQuery;

/** Class */
class RelatedArticles {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'acf/init', array( $this, 'register' ) );
	}

	/**
	 * Uses ACF function to register custom blocks
	 *
	 * @return void
	 */
	public function register() {
		// Check that ACF exists.
		if ( function_exists( 'acf_register_block' ) ) {

			// Register the block.
			acf_register_block(
				array(
					'name'            => 'relatedArticles',
					'title'           => __( 'Related Articles' ),
					'description'     => __( 'A custom block for inserting links to other articles.' ),
					'render_callback' => array( $this, 'render' ),
					'category'        => 'widgets',
					'icon'            => array(
						'background' => '#ecf6f6',
						'src'        => 'list-view',
					),
					'keywords'        => array( 'related', 'articles' ),
					'mode'            => 'edit',
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
	public function render( $block, $content, $is_preview ) {
		$context = Timber::context();

		$related_articles    = get_field( 'chosen_articles' );
		$related_article_ids = array();

		$context['relatedArticlesHeader'] = get_field( 'header_text' );

		if ( ! empty( $related_articles ) ) {
			if ( is_array( $related_articles ) && count( $related_articles ) > 0 ) {
				foreach ( $related_articles as $article ) {
					if ( ! empty( $article['related_article'] ) ) {

						$related_article_ids[] = $article['related_article']->ID;
					}
				}

				// Set query args.
				if ( ! empty( $related_article_ids ) ) {
					$args = array(
						'post_status' => 'publish',
						'post__in'    => $related_article_ids,
						'orderby'     => 'post__in',
					);

					$context['relatedArticles'] = new PostQuery( $args );
				}
			}
		}

		if ( $is_preview ) {
			echo 'Preview mode is not supported for related articles. Please change to Edit mode by clicking the pencil icon in the toolbar above.';
		} else {
			Timber::render( 'templates/components/related-articles.twig', $context );
		}
	}
}

<?php
/**
 * Sets up Gutenberg and calls constructor for custom blocks.
 *
 * @package Skela
 */

namespace Skela\Managers;

use Skela\Blocks;

/** Class */
class GutenbergManager {

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() {
		// TODO: Uncomment this to only allow certain blocks.
		/* add_filter( 'allowed_block_types', array( $this, 'allow_blocks' ) ); */

		new \Skela\Blocks\Blocks();
	}

	/**
	 * Only allow the Gutenberg blocks we actually needed. As of Gutenberg 5.0 this
	 * was the easiest way to disable blocks. Also note that custom blocks need to
	 * be added to this list in order to appear.
	 *
	 * @param array $allowed_blocks Allowed blocks.
	 *
	 * @return array
	 */
	public function allow_blocks( $allowed_blocks ) {
		return array(
			'core/image',
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/subhead',
			'core/quote',
			'core/audio',
			'core/video',
			'core/table',
			'core/freeform',
			'core/html',
			'core/preformatted',
			'core/pullquote',
			'core/separator',
			'core/embed',
			'core-embed/twitter',
			'core-embed/youtube',
			'core-embed/facebook',
			'core-embed/instagram',
			'core-embed/soundcloud',
			'core-embed/spotify',
			'core-embed/vimeo',
			'core-embed/issuu',
			'core-embed/imgur',
			'core-embed/reddit',
			'core-embed/scribd',
			'core-embed/slideshare',
			'core-embed/tumblr',
		);
	}
}

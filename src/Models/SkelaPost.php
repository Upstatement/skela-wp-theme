<?php
/**
 * Additional functionality for extending the TimberPost object.
 *
 * @package Skela
 */

namespace Skela\Models;

use Timber\Post;
use Timber\Timber;
use Timber\Image;

/** Class */
class SkelaPost extends Post {
	/**
	 * Example Function
	 *
	 * @return string
	 */
	public function get_formatted_authors() {
		return 'Jane Doe, Roger Smith';
	}
}

<?php
/**
 * Parent repository class. Provides a very basic, fluent interface for interacting
 * with PostCollection/PostQuery classes.
 *
 * @package Skela
 */

namespace Skela\Repositories;

use Timber\PostCollection;
use Timber\PostQuery;

/** Class */
class Repository {
	/**
	 * List of posts.
	 *
	 * @var array
	 */
	private $result_set = array();

	/**
	 * Returns a list or collection of posts.
	 *
	 * @return array|PostCollection
	 */
	public function get() {
		return $this->result_set;
	}

	/**
	 * Returns the first item in a collection. Returns null if there are 0 items in
	 * the collection.
	 *
	 * @return mixed
	 */
	public function first() {
		$local_array = $this->get();
		return isset( $local_array[0] ) ? $local_array[0] : null;
	}

	/**
	 * Returns a slice of the collection starting at the given index. Similar to
	 * Laravel's slice().
	 *
	 * @param int $start Start index.
	 *
	 * @return array
	 */
	public function slice( $start ) {
		$local_array = $this->get();

		if ( count( $local_array ) < 1 ) {
			return array();
		}

		if ( is_object( $local_array ) && $local_array instanceof PostCollection ) {
			$local_array = $local_array->getArrayCopy();
		}

		return array_slice( $local_array, $start );
	}

	/**
	 * Shuffles (and slices) the result set.
	 *
	 * @param integer $and_slice Index to slice the array at (optional).
	 *
	 * @return array
	 */
	public function shuffle( $and_slice = 0 ) {
		$local_array = $this->get();

		if ( count( $local_array ) < 1 ) {
			return array();
		}

		if ( is_object( $local_array ) && $local_array instanceof PostCollection ) {
			$local_array = $local_array->getArrayCopy();
		}

		shuffle( $local_array );

		if ( $and_slice < 1 ) {
			return $local_array;
		}

		return array_slice( $local_array, 0, $and_slice );
	}

	/**
	 * Runs a query.
	 *
	 * @param array  $params    WP Query params.
	 * @param string $post_class Post class to return.
	 *
	 * @return Repository
	 */
	protected function query( array $params, $post_class = '\Timber\Post' ) {

		// Clear old result sets.
		$this->reset();

		$cache_key    = __FUNCTION__ . md5( http_build_query( $params ) );
		$cached_posts = wp_cache_get( $cache_key, __CLASS__ );

		if ( false !== $cached_posts && count( $cached_posts ) > 0 ) {
			// Use cached results.
			return $this->result_set( $cached_posts );
		}

		$posts = new PostQuery( $params, $post_class );

		// Cache our results.
		if ( count( $posts ) > 0 ) {
			wp_cache_set( $cache_key, $posts, __CLASS__ );
		}

		return $this->result_set( $posts );
	}

	/**
	 * Clears the current result set.
	 *
	 * @return Repository
	 */
	protected function reset() {
		$this->result_set = array();
		return $this;
	}

	/**
	 * Returns current result set
	 *
	 * @param array|PostCollection $result_set Result set.
	 *
	 * @return Repository
	 */
	protected function result_set( $result_set = array() ) {
		$this->result_set = $result_set;
		return $this;
	}

}

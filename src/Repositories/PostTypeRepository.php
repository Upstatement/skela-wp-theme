<?php
/**
 * Repository entity for retrieving post type objects.
 *
 * @package Skela
 */

namespace Skela\Repositories;

use WP_Query;

use Carbon\Carbon;

use Timber\Post;
use Timber\PostCollection;
use Timber\PostQuery;

/** Class */
class PostTypeRepository extends Repository {
	const POST_TYPES = array( 'post' ); // Main post types.

	/**
	 * Returns a chronological list of latest "Post" (articles) posts for a given
	 * category. Default $limit is 10.
	 *
	 * @param string|array $slug    Slug.
	 * @param integer      $limit   Number to return (optional).
	 * @param array        $exclude Posts to exclude (optional).
	 * @param integer      $paged   Enable pagination (optional).
	 *
	 * @return Repository
	 */
	public function articles_by_category_slug( $slug, $limit = 10, $exclude = array(), $paged = 0 ) {

		// Set sane defaults so we don't do full table scans.
		if ( $limit <= 0 || $limit > 1000 ) {
			$limit = 1000;
		}

		// Note the + symbol. See https://codex.wordpress.org/Class_Reference/WP_Query#Category_Parameters.
		if ( is_array( $slug ) ) {
			$slug = implode( '+', $slug );
		}

		$params = array(
			'posts_per_page' => (int) $limit,
			'category_name'  => $slug,
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( is_array( $exclude ) && count( $exclude ) > 0 ) {
			$params['post__not_in'] = $exclude;
		}

		if ( (int) $paged > 0 ) {
			$params['paged'] = $paged;
		}

		return $this->query( $params );
	}

	/**
	 * Returns list of "Posts" between the specified date ranges. $end_datae defaults
	 * to now if null.
	 *
	 * @param Carbon  $start_date Start date.
	 * @param Carbon  $end_datae  End date.
	 * @param integer $paged      Enable pagination.
	 *
	 * @return Repository
	 */
	public function articles_by_date_range( Carbon $start_date, Carbon $end_datae = null, $paged = 0 ) {
		if ( null === $end_datae ) {
			$end_datae = Carbon::now();
		}

		$date_query = array(
			'after'     => array(
				'year'  => $start_date->year,
				'month' => $start_date->month,
				'day'   => $start_date->day,
			),
			'before'    => array(
				'year'  => $end_datae->year,
				'month' => $end_datae->month,
				'day'   => $end_datae->day,
			),
			'inclusive' => true,
		);

		$params = array(
			'date_query'     => $date_query,
			'posts_per_page' => -1,
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( (int) $paged > 0 ) {
			$params['paged'] = $paged;
		}

		return $this->query( $params );
	}

	/**
	 * Returns a chronological list of latest posts across all *public* post types.
	 * This acts as a "firehose" of new content so to speak.
	 *
	 * @param integer $limit      Number of posts to return.
	 * @param array   $post_types WordPress post types.
	 * @param array   $exclude    IDs of posts to exclude.
	 * @param integer $paged      Enable pagination.
	 *
	 * @return Repository
	 */
	public function latest_posts( $limit = 10, $post_types = self::POST_TYPES, array $exclude = array(), $paged = 0 ) {

		// Set sane defaults so we don't do full table scans.
		if ( $limit <= 0 || $limit > 1000 ) {
			$limit = 1000;
		}

		$params = array(
			'posts_per_page' => (int) $limit,
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( count( $exclude ) > 0 ) {
			$params['post__not_in'] = $exclude;
		}

		if ( (int) $paged > 0 ) {
			$params['paged'] = $paged;
		}

		return $this->query( $params );
	}
}

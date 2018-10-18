<?php
/**
 * Repository entity for retrieving post type objects.
 */
namespace Skela\Repositories;

use WP_Query;

use Carbon\Carbon;

use Timber\Post;
use Timber\PostCollection;
use Timber\PostQuery;

class PostTypeRepository extends Repository
{
    const POST_TYPES = ['post']; // Main post types

    /**
     * Returns a chronological list of latest "Post" (articles) posts for a given category.
     * Default $limit is 10.
     *
     * @param string|array $slug    Slug
     * @param integer      $limit   Number to return (optional)
     * @param array        $exclude Posts to exclude (optional)
     * @param integer      $paged   Enable pagination (optional)
     *
     * @return Repository
     */
    public function articlesByCategorySlug($slug, $limit = 10, $exclude = [], $paged = 0)
    {

        // Set sane defaults so we don't do full table scans.
        if ($limit <= 0 || $limit > 1000) {
            $limit = 1000;
        }

        // Note the + symbol. See https://codex.wordpress.org/Class_Reference/WP_Query#Category_Parameters
        if (is_array($slug)) {
            $slug = implode('+', $slug);
        }

        $params = ['posts_per_page' => (int)$limit,
                   'category_name' => $slug,
                   'post_type' => 'post',
                   'post_status' => 'publish',
                   'orderby' => 'date',
                   'order' => 'DESC'];

        if (is_array($exclude) && count($exclude) > 0) {
            $params['post__not_in'] = $exclude;
        }

        if ((int)$paged > 0) {
            $params['paged'] = $paged;
        }

        return $this->query($params);
    }

    /**
     * Returns list of "Posts" between the specified date ranges. $endDate defaults to now if
     * null.
     *
     * @param Carbon  $startDate Start date
     * @param Carbon  $endDate   End date
     * @param integer $paged     Enable pagination
     *
     * @return Repository
     */
    public function articlesByDateRange(Carbon $startDate, Carbon $endDate = null, $paged = 0)
    {
        if ($endDate == null) {
            $endDate = Carbon::now();
        }

        $dateQuery = [
            'after' => ['year' => $startDate->year, 'month' => $startDate->month, 'day' => $startDate->day],
            'before' => ['year' => $endDate->year, 'month' => $endDate->month, 'day' => $endDate->day],
            'inclusive' => true
        ];

        $params = ['date_query' => $dateQuery,
                   'posts_per_page' => -1,
                   'post_type' => 'post',
                   'post_status' => 'publish',
                   'orderby' => 'date',
                   'order' => 'DESC'];

        if ((int)$paged > 0) {
            $params['paged'] = $paged;
        }

        return $this->query($params);
    }

    /**
     * Returns a chronological list of latest posts across all *public* post types. This
     * acts as a "firehose" of new content so to speak.
     *
     * @param integer $limit    Number of posts to return
     * @param array   $postType WordPress post types
     * @param array   $exclude  IDs of posts to exclude
     * @param integer $paged    Enable pagination
     *
     * @return Repository
     */
    public function latestPosts($limit = 10, $postType = self::POST_TYPES, array $exclude = [], $paged = 0)
    {

        // Set sane defaults so we don't do full table scans.
        if ($limit <= 0 || $limit > 1000) {
            $limit = 1000;
        }

        $params = ['posts_per_page' => (int)$limit,
                   'post_status' => 'publish',
                   'orderby' => 'date',
                   'order' => 'DESC'];

        if (count($exclude) > 0) {
            $params['post__not_in'] = $exclude;
        }

        if ((int)$paged > 0) {
            $params['paged'] = $paged;
        }

        return $this->query($params);
    }
}

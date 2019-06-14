<?php
/**
 * Parent repository class. Provides a very basic, fluent interface for interacting with PostCollection/PostQuery classes.
 */
namespace Skela\Repositories;

use Timber\PostCollection;
use Timber\PostQuery;

class Repository
{
    private $resultSet = [];

    /**
     * Returns a list or collection of posts.
     *
     * @return array|PostCollection
     */
    public function get()
    {
        return $this->resultSet;
    }

    /**
     * Returns the first item in a collection. Returns null if there are 0 items in the collection.
     *
     * @return mixed
     */
    public function first()
    {
        $localArray = $this->get();

        return isset($localArray[0]) ? $localArray[0] : null;
    }

    /**
     * Returns a slice of the collection starting at the given index. Similar to Laravel's slice().
     *
     * @param int $start Start index
     *
     * @return array
     */
    public function slice($start)
    {
        $localArray = $this->get();

        if (count($localArray) < 1) {
            return [];
        }

        if (is_object($localArray) && $localArray instanceof PostCollection) {
            $localArray = $localArray->getArrayCopy();
        }

        return array_slice($localArray, $start);
    }

    /**
     * Shuffles (and slices) the result set.
     *
     * @param integer $andSlice - optional
     *
     * @return array
     */
    public function shuffle($andSlice = 0)
    {
        $localArray = $this->get();

        if (count($localArray) < 1) {
            return [];
        }

        if (is_object($localArray) && $localArray instanceof PostCollection) {
            $localArray = $localArray->getArrayCopy();
        }

        shuffle($localArray);

        if ($andSlice < 1) {
            return $localArray;
        }

        return array_slice($localArray, 0, $andSlice);
    }

    /**
     * Runs a query.
     *
     * @param array  $params    WP Query params
     * @param string $postClass Post class to return
     *
     * @return Repository
     */
    protected function query(array $params, $postClass = '\Timber\Post')
    {

        // Clear old result sets.
        $this->reset();

        $cacheKey = __FUNCTION__ . md5(http_build_query($params));
        $cachedPosts = wp_cache_get($cacheKey, __CLASS__);

        if ($cachedPosts !== false && count($cachedPosts) > 0) {

            // Use cached results.
            return $this->resultSet($cachedPosts);
        }

        $posts = new PostQuery($params, $postClass);

        // Cache our results.
        if (count($posts) > 0) {
            wp_cache_set($cacheKey, $posts, __CLASS__);
        }

        return $this->resultSet($posts);
    }

    /**
     * Clears the current result set.
     *
     * @return Repository
     */
    protected function reset()
    {
        $this->resultSet = [];

        return $this;
    }

    /**
     * Returns current result set
     *
     * @param array|PostCollection $resultSet Result set
     *
     * @return Repository
     */
    protected function resultSet($resultSet = [])
    {
        $this->resultSet = $resultSet;

        return $this;
    }

}

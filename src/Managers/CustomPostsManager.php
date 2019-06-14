<?php
/**
 * Bootstraps settings and configurations for custom post types.
 */
namespace Skela\Managers;

class CustomPostsManager
{

    /**
     * Runs initialization tasks.
     *
     * @return void
     */
    public function run()
    {
        add_action('init', [$this, 'registerPostTypes'], 1);
    }


    /**
     * Register post types in WoPostsrdPress
     *
     * @return void
     */
    public function registerPostTypes()
    {

        // Author post type
        $authorLabels = ['name' => __('Authors'),
                     'singular_name' => __('Author'),
                     'add_new_item' => __('Add New Author')];

        register_post_type(
            'author',
            ['labels' => $authorLabels,
            'public' => true,
            'has_archive' => true,
            'menu_position' => 5,
            'rewrite' => ['slug' => 'authors'],
            'supports' => ['editor', 'excerpt', 'title', 'thumbnail'],
            'taxonomies' => []]
        );
    }
}

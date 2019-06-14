<?php
/**
 * Bootstraps settings and configurations for taxonomies.
 */
namespace Skela\Managers;

class TaxonomiesManager
{

    /**
     * Runs initialization tasks.
     *
     * @return void
     */
    public function run()
    {
        add_action('init', [$this, 'registerTaxonomies'], 1);
    }

    /**
     * Register taxonomies in WordPress
     *
     * @return void
     */
    public function registerTaxonomies()
    {
        // Register Author Taxonomy
        $authors_labels = array(
            'name'                       => _x('Authors', 'Taxonomy General Name', 'text_domain'),
            'singular_name'              => _x('Author', 'Taxonomy Singular Name', 'text_domain'),
            'menu_name'                  => __('Authors', 'text_domain'),
            'all_items'                  => __('All Authors', 'text_domain'),
            'parent_item'                => __('Parent Author', 'text_domain'),
            'parent_item_colon'          => __('Parent Author:', 'text_domain'),
            'new_item_name'              => __('New Author Name', 'text_domain'),
            'add_new_item'               => __('Add New Author', 'text_domain'),
            'edit_item'                  => __('Edit Author', 'text_domain'),
            'update_item'                => __('Update Author', 'text_domain'),
            'view_item'                  => __('View Author', 'text_domain'),
            'separate_items_with_commas' => __('Separate authors with commas', 'text_domain'),
            'add_or_remove_items'        => __('Add or remove authors', 'text_domain'),
            'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
            'popular_items'              => __('Popular Authors', 'text_domain'),
            'search_items'               => __('Search Authors', 'text_domain'),
            'not_found'                  => __('Not Found', 'text_domain'),
            'no_terms'                   => __('No authors', 'text_domain'),
            'items_list'                 => __('Authors list', 'text_domain'),
            'items_list_navigation'      => __('Authors list navigation', 'text_domain'),
        );
        $authors_args = array(
            'labels'                     => $authors_labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_in_quick_edit'         => false,
            'meta_box_cb'                => false,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_in_rest'               => true,
            'show_tagcloud'              => true,
            'query_var'                  => 'authors'
        );
        register_taxonomy('author', array( 'post' ), $authors_args);
    }
}

<?php
/**
 * Bootstraps WordPress theme related functions, most importantly enqueuing javascript and styles.
 */
namespace Skela\Managers;

use Timber\Menu;

class ThemeManager
{
    private $managers = [];

    /**
     * Constructor
     *
     * @param array $managers Array of managers
     */
    public function __construct(array $managers)
    {
        $this->managers = $managers;
        add_filter('timber/context', array($this, 'addIsHomeToContext'));
        add_filter('timber/context', array($this, 'addMenusToContext'));
        add_filter('timber/context', array($this, 'addACFOptionsToContext'));

        add_action('admin_enqueue_scripts', array($this,'enqueueAdminScripts'));
        add_action('wp_dashboard_setup', array($this, 'addDocumentationWidget'));
        add_action('admin_init', array($this,'redirectToDocs'), 1);
        add_action('admin_menu', array($this, 'addDocumentationMenuItem'));
        add_action('admin_init', array($this,'registerMenus'));

        add_action('init', array($this, 'registerOptions'), 1, 3);

        add_filter('acf/fields/relationship/query', array($this,'post_relationship_query'), 10, 3);
    }

    /**
     * Runs initialization tasks.
     *
     * @return void
     */
    public function run()
    {
        if (count($this->managers) > 0) {
            foreach ($this->managers as $manager) {
                $manager->run();
            }
        }


        add_action('wp_enqueue_scripts', [$this, 'enqueue'], 999);
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
    }

    /**
     * Enqueue javascript using WordPress
     *
     * @return void
     */
    public function enqueue()
    {
        // don't use WordPress jquery in production. (admin bar and wordpress debug bar needs it in development)
        if (WP_ENV != 'development') {
            wp_deregister_script('jquery');
        }

        // Remove default Gutenberg CSS
        wp_deregister_style('wp-block-library');

        // enqueue vendor script output from webpack
        wp_enqueue_script('manifest', SKELA_THEME_URL . '/dist/manifest.js', array(), SKELA_THEME_VERSION, true);
        wp_enqueue_script('vendor', SKELA_THEME_URL . '/dist/vendor.js', array(), SKELA_THEME_VERSION, true);

        // enqueue custom js file, with cache busting
        wp_enqueue_script('script.js', SKELA_THEME_URL . '/dist/app.js', array(), SKELA_THEME_VERSION, true);
    }

    /**
     * Adds ability to check if we are on the homepage in a twig file
     *
     * @param array $context Timber context
     *
     * @return array
     */
    public function addIsHomeToContext($context)
    {
        $context['is_home'] = is_home();

        return $context;
    }

    /**
     * Register nav menus
     *
      @return void
     */
    public function registerMenus()
    {
        register_nav_menus(
            array(
                'nav_topics_menu' => 'Navigation Topics Menu',
                'nav_pages_menu' => 'Navigation Pages Menu',
                )
        );
    }


    /**
     * Registers and adds menus to context
     *
     * @param array $context Timber context
     *
     * @return array
     */
    public function addMenusToContext($context)
    {
        $context['nav_topics_menu'] = new Menu('nav_topics_menu');
        $context['nav_pages_menu'] = new Menu('nav_pages_menu');
        return $context;
    }

    /**
     * Adds a widget to the dashboard with a link to editor docs
     *
     * @return void
     */
    public function addDocumentationWidget()
    {
        wp_add_dashboard_widget(
            'custom_dashboard_widget',
            'Editor Documentation',
            function () {
                echo "<p><a href='/wp-content/themes/skela/documentation/index.html' target='_blank' rel='noopener noreferrer'>View</a> the editor documentation</p>";
            }
        );
    }

    /**
     * Adds a menu item to WP admin that links to editor docs
     *
     * @return void
     */
    public function addDocumentationMenuItem()
    {
        add_menu_page('Editor Docs', 'Editor Docs', 'manage_options', 'link-to-docs', array($this,'redirectToDocs'), 'dashicons-admin-links', 100);
    }


    /**
     * To have an external link to the docs we need this weird function
     *
     * @return void
     */
    public function redirectToDocs()
    {
        $menu_redirect = isset($_GET['page']) ? $_GET['page'] : false;
        if ($menu_redirect == 'link-to-docs') {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . '/wp-content/themes/cpi/documentation');
            exit();
        }
    }

    /**
     * Add ACF options page to WordPress
     *
     * @return void
     */
    public function registerOptions()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(
                array(
                'page_title'  => 'Site Settings',
                'menu_title'  => 'Site Settings',
                'menu_slug'   => 'site-settings'
                )
            );
        }
    }

    /**
     * Adds ability to access array of ACF options fields in a twig field
     *
     * @param array $context Timber context
     *
     * @return array
     */
    public function addACFOptionsToContext($context)
    {
        if (class_exists('acf')) {
            $context["options"] = get_fields('option');
        }
        return $context;
    }

    /**
     * Modify ACF relationship field to show most recent posts instead of alpha
     *
     * @param array  $args    Args
     * @param string $field   Field
     * @param int    $post_id Post ID
     *
     * @return void
     */
    public function post_relationship_query($args, $field, $post_id)
    {
        // order returned query collection by date, starting with most recent
        $args['order'] = 'DESC';
        $args['orderby'] = 'post_date';

        return $args;
    }
}

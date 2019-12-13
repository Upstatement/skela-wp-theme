<?php
/**
 * WP Theme constants and setup functions
 *
 * @package ThemeSkela
 */

require_once 'vendor/autoload.php';

use Skela\Managers\ThemeManager;

define('SKELA_THEME_URL', get_stylesheet_directory_uri());
define('SKELA_THEME_PATH', dirname(__FILE__) . '/');
define('SKELA_DOMAIN', get_site_url());
define('SKELA_SITE_NAME', get_bloginfo('name'));
define('SKELA_THEME_VERSION', wp_get_theme()->get('Version'));

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', getenv('WP_ENV') ?: 'production');

// Pretty error reporting
if (WP_ENV !== 'production') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();

    error_reporting(E_ERROR);
};

/**
 * Use Dotenv to set required environment variables and load .env file when present.
 */
Dotenv\Dotenv::create(__DIR__)->safeLoad();

$timber = new Timber\Timber();
Timber::$dirname = array('templates');

// Cache twig in staging and production.
if (WP_ENV !== 'development') {
    Timber::$cache = true;
}

/**
 * Customize Twig cache location to a more web-server friendly location.
 */
add_filter('timber/cache/location', function() {
    return WP_CONTENT_DIR . '/uploads/cache/twig';
});

add_action(
    'after_setup_theme',
    function () {
        $managers = [
            // new \Skela\Managers\TaxonomiesManager(),
            new \Skela\Managers\WordPressManager(),
            new \Skela\Managers\GutenbergManager(),
            new \Skela\Managers\CustomPostsManager(),
        ];

        if (function_exists('acf_add_local_field_group')) {
            $managers[] = new \Skela\Managers\ACFManager();
        }

        $themeManager = new ThemeManager($managers);
        $themeManager->run();
    }
);

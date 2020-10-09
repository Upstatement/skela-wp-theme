<?php
/**
 * WP Theme constants and setup functions
 *
 * @package Skela
 */

/**
 * Require the autoloader.
 */
require_once 'vendor/autoload.php';

use Skela\Managers\ThemeManager;

define( 'SKELA_THEME_URL', get_stylesheet_directory_uri() );
define( 'SKELA_THEME_PATH', dirname( __FILE__ ) . '/' );
define( 'SKELA_DOMAIN', get_site_url() );
define( 'SKELA_SITE_NAME', get_bloginfo( 'name' ) );
define( 'SKELA_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Use Dotenv to set required environment variables and load .env file when present.
 */
Dotenv\Dotenv::create( __DIR__ )->safeLoad();

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define( 'WP_ENV', getenv( 'WP_ENV' ) ? getenv( 'WP_ENV' ) : 'production' );

$timber = new Timber\Timber();
Timber::$dirname = array( 'templates' );

add_action(
	'after_setup_theme',
	function () {
		$managers = array(
			/* new \Skela\Managers\TaxonomiesManager(), */
			new \Skela\Managers\WordPressManager(),
			new \Skela\Managers\GutenbergManager(),
			new \Skela\Managers\CustomPostsManager(),
		);

		if ( function_exists( 'acf_add_local_field_group' ) ) {
			$managers[] = new \Skela\Managers\ACFManager();
		}

		$theme_manager = new ThemeManager( $managers );
		$theme_manager->run();
	}
);

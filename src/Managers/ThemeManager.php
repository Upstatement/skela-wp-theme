<?php
/**
 * Bootstraps WordPress theme related functions, most importantly enqueuing
 * javascript and styles.
 *
 * @package Skela
 */

namespace Skela\Managers;

use Timber\Menu;

/** Class */
class ThemeManager {
	/**
	 * List of theme managers.
	 *
	 * @var array
	 */
	private $managers = array();

	/**
	 * Constructor
	 *
	 * @param array $managers Array of managers.
	 */
	public function __construct( array $managers ) {
		$this->managers = $managers;

		add_filter( 'timber/context', array( $this, 'add_wp_env_to_context' ) );
		add_filter( 'timber/context', array( $this, 'add_theme_version_to_context' ) );
		add_filter( 'timber/context', array( $this, 'add_is_home_to_context' ) );
		add_filter( 'timber/context', array( $this, 'add_menus_to_context' ) );
		add_filter( 'timber/context', array( $this, 'add_acf_options_to_context' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'add_documentation_widget' ) );
		add_action( 'admin_init', array( $this, 'redirect_to_docs' ), 1 );
		add_action( 'admin_menu', array( $this, 'add_documentation_menu_item' ) );
		add_action( 'admin_init', array( $this, 'register_menus' ) );

		add_action( 'init', array( $this, 'register_options' ), 1, 3 );

		add_filter( 'acf/fields/relationship/query', array( $this, 'post_relationship_query' ), 10, 3 );
	}

	/**
	 * Runs initialization tasks.
	 *
	 * @return void
	 */
	public function run() {
		if ( count( $this->managers ) > 0 ) {
			foreach ( $this->managers as $manager ) {
				$manager->run();
			}
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 999 );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
	}

	/**
	 * Enqueue javascript using WordPress
	 *
	 * @return void
	 */
	public function enqueue() {
		// Remove default Gutenberg CSS.
		wp_deregister_style( 'wp-block-library' );

		wp_enqueue_script( 'vendor', SKELA_THEME_URL . '/dist/vendor.js', array(), SKELA_THEME_VERSION, true );

		// Enqueue custom js file, with cache busting.
		wp_enqueue_script( 'script.js', SKELA_THEME_URL . '/dist/app.js', array(), SKELA_THEME_VERSION, true );
	}

	/**
	 * Enqueue JS and CSS for WP admin panel
	 *
	 * @return void
	 */
	public function enqueue_admin() {
		wp_enqueue_style( 'admin-styles', SKELA_THEME_URL . '/dist/admin.css', array(), SKELA_THEME_VERSION );

		wp_enqueue_script( 'vendor', SKELA_THEME_URL . '/dist/vendor.js', array(), SKELA_THEME_VERSION, false );
		wp_enqueue_script( 'admin.js', SKELA_THEME_URL . '/dist/admin.js', array(), SKELA_THEME_VERSION, false );
	}

	/**
	 * Adds ability to check the environment in a twig file
	 *
	 * @param array $context Timber context.
	 *
	 * @return array
	 */
	public function add_wp_env_to_context( $context ) {
		$context['wp_env'] = WP_ENV;
		return $context;
	}

	/**
	 * Expose current theme version to Timber context
	 *
	 * @param array $context Timber context.
	 *
	 * @return array
	 */
	public function add_theme_version_to_context( $context ) {
		$context['theme_version'] = SKELA_THEME_VERSION;

		return $context;
	}

	/**
	 * Adds ability to check if we are on the homepage in a twig file
	 *
	 * @param array $context Timber context.
	 *
	 * @return array
	 */
	public function add_is_home_to_context( $context ) {
		$context['is_home'] = is_home();

		return $context;
	}

	/**
	 * Register nav menus
	 *
	 * @return void
	 */
	public function register_menus() {
		register_nav_menus(
			array(
				'nav_topics_menu' => 'Navigation Topics Menu',
				'nav_pages_menu'  => 'Navigation Pages Menu',
			)
		);
	}


	/**
	 * Registers and adds menus to context
	 *
	 * @param array $context Timber context.
	 *
	 * @return array
	 */
	public function add_menus_to_context( $context ) {
		$context['nav_topics_menu'] = new Menu( 'nav_topics_menu' );
		$context['nav_pages_menu']  = new Menu( 'nav_pages_menu' );
		return $context;
	}

	/**
	 * Adds a widget to the dashboard with a link to editor docs
	 *
	 * @return void
	 */
	public function add_documentation_widget() {
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
	public function add_documentation_menu_item() {
		add_menu_page(
			'Editor Docs',
			'Editor Docs',
			'manage_options',
			'link-to-docs',
			array( $this, 'redirect_to_docs' ),
			'dashicons-admin-links',
			100
		);
	}


	/**
	 * To have an external link to the docs we need this weird function
	 *
	 * @return void
	 */
	public function redirect_to_docs() {
		$menu_redirect = isset( $_GET['page'] ) ? $_GET['page'] : false;
		if ( 'link-to-docs' === $menu_redirect ) {
			header( 'Location: https://' . $_SERVER['HTTP_HOST'] . '/wp-content/themes/skela/documentation' );
			exit();
		}
	}

	/**
	 * Add ACF options page to WordPress
	 *
	 * @return void
	 */
	public function register_options() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => 'Site Settings',
					'menu_title' => 'Site Settings',
					'menu_slug'  => 'site-settings',
				)
			);
		}
	}

	/**
	 * Adds ability to access array of ACF options fields in a twig field
	 *
	 * @param array $context Timber context.
	 *
	 * @return array
	 */
	public function add_acf_options_to_context( $context ) {
		if ( class_exists( 'acf' ) ) {
			$context['options'] = get_fields( 'option' );
		}
		return $context;
	}

	/**
	 * Modify ACF relationship field to show most recent posts instead of alpha
	 *
	 * @param array  $args    Args.
	 * @param string $field   Field.
	 * @param int    $post_id Post ID.
	 *
	 * @return array
	 */
	public function post_relationship_query( $args, $field, $post_id ) {
		// Order returned query collection by date, starting with most recent.
		$args['order']   = 'DESC';
		$args['orderby'] = 'post_date';

		return $args;
	}
}

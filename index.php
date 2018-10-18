<?php
/**
 * Based on WP's template hierarchy, we are setting the home page to use the front-page.php file.
 * index.php is the last fallback file that is used in the hierarchy and as such we should issue
 * a 404 response if WP ever gets here.
 *
 */
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

\Skela\Services\WordPressService::abort_request(404);

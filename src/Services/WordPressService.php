<?php
/**
 * Wordpress specific related functions
 */
namespace Skela\Services;

class WordPressService
{
    /**
     * Force an abort of a HTTP request
     *
     * @param integer $statusCode HTTP status code number
     *
     * @return void
     */
    public static function abort_request($statusCode = 0)
    {

        // Always reset the content type.
        add_filter(
            'wp_headers',
            function ($headers) {
                $headers['Content-Type'] = 'text/html';
                return $headers;
            },
            99,
            1
        );

        // ...reset in nocache_headers as well.
        add_filter(
            'nocache_headers',
            function ($headers) {
                $headers['Content-Type'] = 'text/html';
                return $headers;
            },
            99,
            1
        );

        switch ($statusCode) {
        case 403:
            wp_die('Error: Access forbidden.', 'Access Forbidden - ' . SKELA_SITE_NAME, ['response' => 403]);

            // no break
        case 404:
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            include get_query_template('404');
            exit;
        }
    }
}

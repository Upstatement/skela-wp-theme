<?php
/**
 * Page.
 *
 * @package Skela
 */

$context         = Timber::context();
$_page           = Timber::get_post();
$context['page'] = $_page;

Timber::render( 'pages/page.twig', $context );

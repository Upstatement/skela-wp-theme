<?php
/**
 * Page.
 *
 * @package Skela
 */

$context = Timber::context();

$page = Timber::get_post();

$context['page'] = $page;

Timber::render( 'pages/page.twig', $context );

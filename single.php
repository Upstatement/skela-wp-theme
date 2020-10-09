<?php
/**
 * Single post / article.
 *
 * @package Skela
 */

$context            = Timber::context();
$_post              = Timber::get_post();
$context['article'] = $_post;

Timber::render( 'pages/article.twig', $context );

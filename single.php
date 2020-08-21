<?php
/**
 * Single post / article.
 *
 * @package Skela
 */

$context = Timber::context();

$post = Timber::get_post();

$context['article'] = $post;

Timber::render( 'pages/article.twig', $context );

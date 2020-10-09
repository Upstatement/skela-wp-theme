<?php
/**
 * Front page
 *
 * @package Skela
 */

use Skela\Repositories\PostTypeRepository;

$context = Timber::context();

$post_repo        = new PostTypeRepository();
$latest_posts     = $post_repo->latest_posts( 10, null, array(), null )->get();
$context['posts'] = $latest_posts;

// Render view.
Timber::render( 'pages/front-page.twig', $context );

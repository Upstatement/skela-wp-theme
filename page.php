<?php
/**
 * Front page
 */

use Skela\Models\SkelaPost;

$context = Timber::context();

$post = new SkelaPost();
$context['post'] = $post;

// Render view.
Timber::render('pages/page.twig', $context);

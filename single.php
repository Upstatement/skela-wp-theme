<?php
/**
 * Single post / article
 */

$context = Timber::get_context();

// Get post
$post = Timber::get_post();
$context['article'] = $post;

Timber::render('pages/article.twig', $context);

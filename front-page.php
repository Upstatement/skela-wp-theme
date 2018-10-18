<?php
/**
 * Front page
*/

use Skela\Repositories\PostTypeRepository;

$context = Timber::get_context();

$postTypeRepo = new PostTypeRepository();
$latestPosts = $postTypeRepo->latestPosts(10, null, [], null)->get();
$context['posts'] = $latestPosts;

// Render view.
Timber::render('pages/front-page.twig', $context);

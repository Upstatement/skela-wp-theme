<?php
/**
 * Page
 */

$context = Timber::context();

$page = Timber::get_post();
$context['page'] = $page;

Timber::render('pages/page.twig', $context);

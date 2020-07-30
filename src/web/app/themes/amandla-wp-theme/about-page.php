<?php
/**
 * Template Name: About page
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...

$context = Timber::context();
$context['team_title'] =  get_field('team_title');
$context['team'] = get_field('team');

$context['board_title'] =  get_field('board_title');
$context['board'] = get_field('board');

$context['funded_detail'] =  get_field('funded_detail');
$context['logo_funded'] = get_field('logo');

$timber_post = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'about-page.twig', 'page.twig' ), $context );

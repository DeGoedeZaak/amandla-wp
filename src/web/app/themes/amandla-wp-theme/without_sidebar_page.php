<?php
/**
 * Template Name: without sidebar page
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...

$context = Timber::context();
$context['page_sub_title'] =  get_field('page_sub_title');

$timber_post = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'without_sidebar_page.twig', 'page.twig' ), $context );

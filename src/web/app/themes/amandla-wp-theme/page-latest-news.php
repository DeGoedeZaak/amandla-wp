<?php
/**
 * Template Name: Latest News
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...



$context = Timber::context();


$query = array(
    'post_type'        => 'post',
    'post_status'      => 'publish',
	'orderby'          => 'date',
	'order'            => 'DESC',
	//'showposts'		   => 1 
);

 $context['blog_post']  = Timber::get_posts( $query );

//echo "<pre>";
//print_r($context['blog_post']);

$timber_post = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array('page-latest-news.twig', 'page.twig' ), $context );


<?php
/**
 * Template Name: Campaigns page
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...

$context = Timber::context();

// campaign 
$query_campaign = array(
    'post_type'        => 'campaign',
    'post_status'      => 'publish',
	'orderby'          => 'date',
	'order'            => 'DESC',
	//'showposts'		   => 2 
);
 $context['campaign_post']  = Timber::get_posts( $query_campaign ); 


$timber_post = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'page-campaigns.twig', 'page.twig' ), $context );

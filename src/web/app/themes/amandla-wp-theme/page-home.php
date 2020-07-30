<?php
/**
 * Template Name: Home Page
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...



$context = Timber::context();

 $people_count =  629012;	
 $home_top_title_c = get_field('home_top_title');	
 $people_count_add = $string = str_replace('{{}}',$people_count, $home_top_title_c);
	
$context['home_top_title'] =  $people_count_add;
$context['home_top_image'] =  get_field('home_top_image');
$context['home_top_description'] =  get_field('home_top_description');

$home_top_description_mobile_r = get_field('home_top_description_mobile');	
$home_top_description_mobile_t = $string = str_replace('{{}}',$people_count, $home_top_description_mobile_r);

$context['home_top_description_mobile'] =  $home_top_description_mobile_t;
$context['latest_campaigns_title'] =  get_field('latest_campaigns_title');
$context['campaign_text_title'] =  get_field('campaign_text_title');
$context['campaign_btn_text'] =  get_field('campaign_btn_text');
$context['campaign_btn_link'] =  get_field('campaign_btn_link');
$context['campaign_btn_text_mobile'] =  get_field('campaign_btn_text_mobile');
$context['campaign_btn_link_mobile'] =  get_field('campaign_btn_link_mobile');
$context['home_donation_text'] =  get_field('home_donation_text');
$context['home_donation_btn_title'] =  get_field('home_donation_btn_title');
$context['home_donation_link'] =  get_field('home_donation_link');
$context['latest_news_title'] =  get_field('latest_news_title');

$query = array(
    'post_type'        => 'post',
    'post_status'      => 'publish',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'showposts'		   => 3 
);

 $context['latest_post']  = Timber::get_posts( $query );
 
 // campaign 
$query_campaign = array(
    'post_type'        => 'campaign',
    'post_status'      => 'publish',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'showposts'		   => 2 
);
 $context['campaign_post']  = Timber::get_posts( $query_campaign );

$timber_post = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array('page-home.twig', 'page.twig' ), $context );




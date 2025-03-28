<?php

// Create Theme Options Page
if (function_exists('acf_add_options_page')) {
	// Main options page
	acf_add_options_page(array(
		'page_title'  => 'Giovanni Settings',
		'menu_title'  => 'Giovanni Settings',
		'menu_slug'   => 'giovanni-general-settings',
		'position'    => 2,
		'capability'  => 'edit_posts',
		'icon_url'    => 'dashicons-admin-generic',
		'redirect'    => false
	));

	// Subpage under Giovanni Settings
	acf_add_options_sub_page(array(
		'page_title'  => 'Registration Page',
		'menu_title'  => 'Registration Page',
		'parent_slug' => 'giovanni-general-settings', // Parent slug to nest under main options page
		'menu_slug'   => 'giovanni-registration-page',
		'capability'  => 'edit_posts'
	));

	// Subpage under Giovanni Settings
	acf_add_options_sub_page(array(
		'page_title'  => 'Subscription Popup',
		'menu_title'  => 'Subscription Popup',
		'parent_slug' => 'giovanni-general-settings', // Parent slug to nest under main options page
		'menu_slug'   => 'giovanni-subscription-popup',
		'capability'  => 'edit_posts'
	));

	// Subpage under Giovanni Settings
	acf_add_options_sub_page(array(
		'page_title'  => '404 Page',
		'menu_title'  => '404 Page',
		'parent_slug' => 'giovanni-general-settings', // Parent slug to nest under main options page
		'menu_slug'   => 'giovanni-error-page',
		'capability'  => 'edit_posts'
	));

	// Subpage under Giovanni Settings
	acf_add_options_sub_page(array(
		'page_title'  => 'Thank You Popup',
		'menu_title'  => 'Thank You Popup',
		'parent_slug' => 'giovanni-general-settings', // Parent slug to nest under main options page
		'menu_slug'   => 'giovanni-thankyou-modal',
		'capability'  => 'edit_posts'
	));
}


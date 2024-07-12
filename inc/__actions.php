<?php

/**
 * Allow SVG
 */
add_action('upload_mimes', function ($mimes) {
    $mimes['svg-xml']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    $mimes['svg']  = 'image/svg+xml';
    return $mimes;
});

/**
 * Add WooCommerce support
 *
 * @return void
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')) ) ) {
  function add_woocommerce_support()
  {
    // Add Woocommerce Support to site
    add_theme_support('woocommerce');
    // add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
  }

  add_action('after_setup_theme', 'add_woocommerce_support');
}
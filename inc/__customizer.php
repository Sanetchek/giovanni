<?php

// Add custom fields to Site Identity Tab
function custom_theme_customizer( $wp_customize ) {
  // Modify the Site Identity section
  $wp_customize->get_section( 'title_tagline' )->title = __( 'Site Identity', 'giovanni' );

  // Add an image field for Logo
  $wp_customize->add_setting( 'logo_setting', array(
      'default' => '',
  ) );

  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_logo_control', array(
      'label' => __( 'Logo', 'giovanni' ),
      'section' => 'title_tagline',
      'settings' => 'logo_setting',
  ) ) );
}
add_action( 'customize_register', 'custom_theme_customizer' );

// Show Logo on Front
function theme_logo() {
  // Get the logo setting value
  $header_logo = get_theme_mod( 'logo_setting', '' );
  $template = '';

  // Display the logo if it's set
  if ( $header_logo ) {
    $template .= '<a href="/" class="site-logo-link" rel="home" aria-current="page" tabindex="0">';
    $template .= '<img src="' . esc_url( $header_logo ) . '" width="300" class="site-logo" alt="logo" decoding="async">';
    $template .= '</a>';
  }

  return $template;
}

// Add custom fields to Social Media Links Tab
function social_theme_customizer( $wp_customize ) {
  $wp_customize->add_section( 'social_media_section', array(
    'title' => __( 'Social Media Links', 'giovanni' ),
    'priority' => 30,
  ) );

  // Whatsup Field
  $wp_customize->add_setting( 'whatsup_url_setting', array(
      'default' => '',
  ) );

  $wp_customize->add_control( 'whatsup_url_control', array(
      'label' => __( 'Whatsup URL', 'giovanni' ),
      'section' => 'social_media_section',
      'settings' => 'whatsup_url_setting',
      'type' => 'url',
  ) );

  // Telegram Field
  $wp_customize->add_setting( 'telegram_url_setting', array(
      'default' => '',
  ) );

  $wp_customize->add_control( 'telegram_url_control', array(
      'label' => __( 'Telegram URL', 'giovanni' ),
      'section' => 'social_media_section',
      'settings' => 'telegram_url_setting',
      'type' => 'url',
  ) );

  // LinkedIn Field
  $wp_customize->add_setting( 'linkedin_url_setting', array(
      'default' => '',
  ) );

  $wp_customize->add_control( 'linkedin_url_control', array(
      'label' => __( 'LinkedIn URL', 'giovanni' ),
      'section' => 'social_media_section',
      'settings' => 'linkedin_url_setting',
      'type' => 'url',
  ) );
}
add_action( 'customize_register', 'social_theme_customizer' );

// Add custom fields to Copyrights Tab
function copyrights_theme_customizer( $wp_customize ) {
  $wp_customize->add_section( 'copyrights_section', array(
    'title' => __( 'Copyrights', 'giovanni' ),
    'priority' => 100,
  ) );

  // Copyrights Field
  $wp_customize->add_setting( 'copyrights_text_setting', array(
      'default' => '',
  ) );

  $wp_customize->add_control( 'copyrights_text_control', array(
      'label' => __( 'Copyrights Text', 'giovanni' ),
      'section' => 'copyrights_section',
      'settings' => 'copyrights_text_setting',
      'type' => 'url',
  ) );
}
add_action( 'customize_register', 'copyrights_theme_customizer' );

<?php

/**
 * Adds rel=preload tags for fonts in the <head> for optimization
 *
 * @since 1.0.0
 */
function custom_enqueue_fonts() {
  echo '<link rel="preload" href="https://fonts.gstatic.com/s/librecaslontext/v5/DdT878IGsGw1aF1JU10PUbTvNNaDMfq41-JJHRO0.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
  echo '<link rel="preload" href="https://fonts.gstatic.com/s/jost/v18/92zPtBhPNqw79Ij1E865zBUv7myRJTVBNI4un_HKOEo.woff" as="font" type="font/woff" crossorigin="anonymous">' . "\n";
}
add_action('wp_head', 'custom_enqueue_fonts');

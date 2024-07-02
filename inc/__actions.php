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

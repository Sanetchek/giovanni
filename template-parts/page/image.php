<?php
$block = $args['block'];
$bg = $block['image'];
$bg_mob = $block['image_mob'];

$data = [
  'thumb' => [1920, 865],
  'max' => [
    '1024' => [800, 0],
    '768' => [768, 0],
    '576' => [576, 0],
    '412' => [412, 0],
  ],
];
echo liteimage( $bg, $data, $bg_mob );
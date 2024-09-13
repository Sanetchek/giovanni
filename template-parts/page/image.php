<?php
$block = $args['block'];
$bg = $block['image'];
$bg_mob = $block['image_mob'];

show_image($bg, '1920-865', ['class' => 'page-desk']);
$bg_mob = $bg_mob ? $bg_mob : $bg;
show_image($bg_mob, '800-full', ['class' => 'page-mob']);
<?php
$bg = $args['hero_image'];
$bg_mob = $args['hero_image_mob'];

show_image($bg, '1920-865', ['class' => 'page-desk']);
$bg_mob = $bg_mob ? $bg_mob : $bg;
show_image($bg_mob, '880-880', ['class' => 'page-mob']);
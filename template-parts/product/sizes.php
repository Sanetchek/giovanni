<?php

global $product;

// Get the product gallery attachment ids.
//$attachment_ids = $product->get_gallery_image_ids();
?>

<div id="left-popup" class="modal product-size-modal modal-size-guide">
    <button class="modal-close">&times;</button>

    <?php
    $radio_value = get_field('use_size_table_popup');
    if ($radio_value === 'ml') {
        $size_table_ml = get_field('size_table_content_for_lm', 'option');
        echo $size_table_ml;
    } elseif ($radio_value === 'numbers') {
        $size_table_numbers = get_field('size_table_content_for_numbers', 'option');
        echo $size_table_numbers;
    } else {
    ?>
        <div class="block-default">
            <h4>Fill in the field for the size chart</h4>            
        </div>
    <?php
    }
    ?>
 

</div>

<div id="modal-overlay" class="modal-overlay"></div>
<div id="modal_subscription" class="modal-subscription">
    <span class="modal-close"></span>
    <div class="modal-content">
        <div class="modal-side modal-side-image">
            <?php
            $image = get_field('subscribe_image', 'option');
            $data = [
                'thumb' => [800, 0],
                'max' => [
                    '576' => [576, 0],
                    '412' => [412, 0],
                ],
                'args' => [
                    'class' => 'modal-subscribe-image',
                ],
            ];
            echo liteimage($image, $data);
            ?>
        </div>

        <div class="modal-side modal-side-content">
            <h2 class="modal-subscribe-title"><?= get_field('subscribe_title', 'option') ?></h2>
            <p class="modal-subscribe-text"><?= get_field('subscribe_description', 'option') ?></p>

            <div class="modal-subscribe-form">
                <?php echo do_shortcode(get_field('subscibe_form_short_code', 'option')) ?>
            </div>
        </div>
    </div>
</div>
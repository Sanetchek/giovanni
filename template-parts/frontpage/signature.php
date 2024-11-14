<section id="signature_pieces" class="signature_pieces">
  <div class="container">
    <?php
    $title = get_field('signature_title');
    $desc = get_field('signature_subtitle');
    $button_title = get_field('signature_button_title');
    $button_link = get_field('signature_button_link');
    $image = get_field('sig_image');
    $image_mob = get_field('sig_image_mob');
    $image_link = get_field('sig_image_link');
    ?>
    <div class="article-paragraph">
        <h2 class="paragraph-title"><?= $title ?></h2>
        <?php if ($desc) : ?>
          <div class="paragraph-text"><p><?= $desc ?></p></div>
        <?php endif ?>
        <?php if ($button_link) : ?>
          <div class="button-animation-effect">
            <a href="<?= $button_link ?>" class="btn btn-hover">
              <div class="animation-label-container" style="--char-count: 18;">
                <p class="cta-text"><?= $button_title ?></p>
                <p aria-hidden="true" class="cta-text animation-label-text"><?= $button_title ?></p>
              </div>
            </a>
          </div>
        <?php endif ?>
    </div>
  </div>

  <?php if ($image) : ?>
    <div class="signature_pieces-side signature_pieces-image-wrap">
      <a href="<?= $image_link ?>">
      <?php     
      show_image($image, '1920-920', ['class' => 'collections-bg desktop-only page-desk']);
      $image_mob = $image_mob ? $image_mob : $image;
      show_image($image_mob, '800-800', ['class' => 'mobile-only page-mob']);
      ?>
      </a>
    </div>
  <?php endif ?>
</section>



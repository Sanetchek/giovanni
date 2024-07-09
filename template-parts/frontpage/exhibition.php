<section id="exhibition" class="exhibition">
  <?php
  $image = get_field('exh_image');
  $title = get_field('exh_title');
  $desc = get_field('exh_description');
  $label = get_field('exh_button_label');
  $link = get_field('exh_link');
  ?>
  <div class="main-wrap">
    <div class="exhibition-wrap">
      <div class="exhibition-side exhibition-content-wrap">
        <div class="exhibition-content">
          <h2><?= $title ?></h2>
          <p><?= $desc ?></p>

          <?php if ($link) : ?>
            <a href="<?= $link ?>" class="btn btn-hover"><?= $label ?></a>
          <?php endif ?>
        </div>
      </div>

      <div class="exhibition-side exhibition-image-wrap">
        <?php show_image($image, '424-424', ['class'=> 'exhibition-image']) ?>
      </div>
    </div>
  </div>
</section>

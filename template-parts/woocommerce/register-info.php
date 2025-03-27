<section class="reg-info">
  <?php $group = get_field('reg_info', 'option'); ?>
  <?php if ($group['title']) : ?>
    <h2 class="reg-title"><?= $group['title'] ?></h2>
  <?php endif ?>

  <?php if ($group['sub_title']) : ?>
    <p class="reg-info-message"><?= $group['sub_title'] ?></p>
  <?php endif ?>

  <?php if ($group['list']) : ?>
    <ul class="reg-info-list">
      <?php foreach ($group['list'] as $item) : ?>
        <li class="reg-info-item">
          <?php if ($item['icon']) : ?>
            <img src="<?= $item['icon'] ?>" alt="icon" class="reg-info-image" width="24" height="24" loading="lazy">
          <?php endif ?>

          <?php if ($item['text']) : ?>
            <span class="reg-info-text"><?= $item['text'] ?></span>
          <?php endif ?>
        </li>
      <?php endforeach ?>
    </ul>
  <?php endif ?>
</section>
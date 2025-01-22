<div id="login_modal" class="login-modal">
  <div class="login-modal-close">
    <svg class='icon-close' width='24' height='24'>
      <use href='<?= assets('img/sprite.svg#icon-close') ?>'></use>
    </svg>
  </div>

  <?php get_template_part('template-parts/woocommerce/login'); ?>

  <div class="register-section">
    <div class="register-difider">
      <div class="circle"></div>
    </div>

    <div class="register-link">
      <?= __('חדש ב-Giovanni Raspini? <a href="/my-account">הירשם</a>', 'giovanni') ?>
    </div>
  </div>

</div>

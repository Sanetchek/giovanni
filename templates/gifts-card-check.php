<?php
/**
 * Template Name: Gifts Card Check
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

get_header();
?>

<main id="primary" class="site-main checkgiftcardpage page-container">

  <div class="container">
    <div class="gift-certificate-balance__form-container">
      <div class="gift-certificate-balance__title">
        <?= __('בדוק את יתרת כרטיס המתנה שלך', 'giovanni') ?>
      </div>
      <p class="gift-certificate-balance__text"><?= __('כדי לבדוק את יתרת כרטיס המתנה של Giovanni Raspini שלך, הזן את המספר בן 19 הספרות המשויך לכרטיס שלך במקום למטה.', 'giovanni') ?></p>
    </div>

    <div class="button-confirm">

      <div class="wrapper-form">
        <div><?php //the_content() ?></div>

        <div class="wps_gift_card_balance_wrapper">
          <div class="gift_card_balance_email">
              <label><?= __('הזן אימייל של נמען', 'giovanni') ?></label>
              <input type="email" id="gift_card_balance_email" class="wps_gift_balance"  required="required">
          </div>
          <div class="gift_card_code">
              <label><?= __('הזן קוד כרטיס מתנה', 'giovanni') ?></label>
              <input type="text" id="gift_card_code" class="wps_gift_balance" required="required">
          </div>
          <p class="wps_check_balance">
            <button type="button" id="wps_check_balance" class="wps_check_balance button button-black button-hover white"><?= __('בדוק יתרה', 'giovanni') ?></button>
            <span id="wps_notification"></span>
          </p>
        </div>
        <div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
        </div>
      </div>

      <div class="gift-certificate__card-wrap">
        <div class="gift-certificate__card-clone">
          <div class="icon-logo-white"></div>
          <p><span><?= __('כרטיס מתנה', 'giovanni') ?></span></p>
        </div>
      </div>

    </div>
  </div>

  <div class="giftpage-best-sellers"><?php get_template_part('template-parts/best', 'seller') ?></div>

</main><!-- #main -->

<?php get_footer(); ?>
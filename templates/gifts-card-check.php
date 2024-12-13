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
        Check your gift card balance
      </div>
      <p class="gift-certificate-balance__text">To check the balance of your Giovanni Raspini Gift Card, enter the 19-digit number associated with your card in the space below.</p>
    </div>

    <div class="button-confirm">

      <div class="wrapper-form">
        <div><?php //the_content() ?></div>

        <div class="wps_gift_card_balance_wrapper">
          <div class="gift_card_balance_email">
              <label>Enter Recipient Email</label>
              <input type="email" id="gift_card_balance_email" class="wps_gift_balance" placeholder="Enter Recipient Email/Name or Sender Email." required="required">
          </div>
          <div class="gift_card_code">
              <label>Enter Gift Card Code</label>
              <input type="text" id="gift_card_code" class="wps_gift_balance" placeholder="Enter Gift Card Code" required="required">
          </div>
          <p class="wps_check_balance">
            <button type="button" id="wps_check_balance" class="wps_check_balance button button-black button-hover white"><?= __('Check Balance', 'giovanni') ?></button>
              <!-- <input class="button wps_check_balance" type="button" id="wps_check_balance" value="Check Balance"> -->
              <span id="wps_notification"></span>
          </p>
        </div>
        <div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
            <img src="path-to-your-assets/assets/images/loading.gif">
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
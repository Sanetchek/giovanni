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

<main id="primary" class="site-main page-container">

  <div class="container">
    <div class="row justify-center gift-certificate-balance__form-container">
      <div class="col-12 col-md-8 gift-certificate-balance__title text-center">
        Check your gift card balance
      </div>
      <p class="col-12 col-md-8 gift-certificate-balance__text text-center">To check the balance of your Giovanni Raspini Gift Card, enter the 19-digit number associated with your card in the space below.</p>
    </div>
    <div class="row justify-center align-top gift-certificate-balance__form-container">
      <div class="col-12 col-md-6 col-lg-4">
        <form class="giftcertificateform" action="/on/demandware.store/Sites-raspini-eu-Site/en_LU/GiftCertificateBalance-Submit" method="POST" data-component="GiftCertificateFormComponent" name="dwfrm_giftCertificateBalanceForm" id="dwfrm_giftCertificateBalanceForm" novalidate="true">

            <div class="form-group">
              <label for="cardNumber" class="form-control-label">Card number</label>
              <input type="string" class="form-control gift-certificate-balance__form-cardNumber" id="cardNumber" name="dwfrm_giftCertificateBalanceForm_cardNumber" required="" aria-required="true" value="" maxlength="50">
              <div class="invalid-feedback" id="cardNumberFeedback"></div>
            </div>
            <div class="form-group">
              <div class="invalid-feedback" id="cardEmailFeedback"></div>
            </div>
            <button type="submit" class="gift-certificate__button button button-black button-hover white">Check the balance</button>
        </form>
        <div class="gift-certificate-balance__message-balance"></div>
      </div>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="d-none d-md-block">
          <div class="gift-certificate-balance__card-clone">
            <div class="icon-logo-white"></div>
            <p>gift card</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</main><!-- #main -->

<?php get_footer(); ?>
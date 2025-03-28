jQuery(document).ready(function ($) {
  $(document).on('wpcf7mailsent', function (event) {
    if (event.detail.contactFormId == '26' || event.detail.contactFormId == '8225' || event.detail.contactFormId == '7965') { // Replace with actual form IDs
      $('#thankyou_modal, #thankyou_overlay').addClass('is-visible');
      $('body').addClass('no-scroll');
      $('#modal_subscription').hide();
      $('#modal-subscribe-overlay').removeClass('is-visible');
    }
  });

  $('.thankyou-modal-close, #thankyou_overlay').on('click', function () {
    $('#thankyou_modal, #thankyou_overlay').removeClass('is-visible');
    $('body').removeClass('no-scroll');
  });
});
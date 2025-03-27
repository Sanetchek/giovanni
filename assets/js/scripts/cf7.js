jQuery(document).ready(function ($) {
  $(document).on('wpcf7mailsent', function (event) {
    if (event.detail.contactFormId == 'c7cfe8b' || event.detail.contactFormId == '3b7a46a') { // Replace with actual form IDs
      setTimeout(function () {
        window.location.href = "https://giovanniraspini-shop.co.il/thankyou-newslatter/";
      }, 500); // Delay to ensure form submission completes
    }
  });
});
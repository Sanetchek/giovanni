jQuery(document).ready(function ($) {
  var $scrollToTop = $("#scrollToTop");

  // Show or hide the button based on scroll position
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $scrollToTop.fadeIn();
    } else {
      $scrollToTop.fadeOut();
    }
  });

  // Scroll to top when button is clicked
  $scrollToTop.click(function () {
    $("html, body").animate({
      scrollTop: 0
    }, 600);
  });
});
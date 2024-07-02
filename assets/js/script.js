'use strict';

(function ($) {
  // Home page slider
  const $slider = $('.popular-product');

  $slider.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
    const currentIndex = (currentSlide ? currentSlide : 0) + 1;
    const totalSlides = slick.slideCount;
    $('.slide-indicator').text(currentIndex);
    $('.slide-total').text(totalSlides);
  });

  $slider.slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    rtl: true,
    prevArrow: $('.slick-prev'),
    nextArrow: $('.slick-next'),
    responsive: [{
        breakpoint: 1279,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
        }
      },
    ]
  });


}(jQuery));
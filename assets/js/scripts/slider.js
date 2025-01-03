'use strict';

(function ($) {
  const screenWidth = $(window).width();

  /**
   * Initialize sliders on the homepage
   */
  $('.popular-product').each(function () {
    const $slider = $(this);
    const $slideIndicator = $slider.closest('.slider-container').find('.slide-indicator');
    const $slideTotal = $slider.closest('.slider-container').find('.slide-total');

    $slider.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
      const currentIndex = (currentSlide ? currentSlide : 0) + 1;
      const totalSlides = slick.slideCount;
      $slideIndicator.text(currentIndex);
      $slideTotal.text(totalSlides);
    });

    $slider.slick({
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      rtl: false,
      prevArrow: $slider.closest('.slider-container').find('.slick-prev'),
      nextArrow: $slider.closest('.slider-container').find('.slick-next'),
      responsive: [
        {
          breakpoint: 1279,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 768,
          settings: {
            centerMode: true,
            centerPadding: '100px',
            slidesToShow: 1
          }
        },
        {
          breakpoint: 400,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
          }
        },
      ]
    });
  });

  /**
   * Initialize sliders on the page
   */
  $('.article-slider-list').each(function () {
    const $slider = $(this);
    const $slideIndicator = $slider.closest('.article-slider').find('.slide-indicator');
    const $slideTotal = $slider.closest('.article-slider').find('.slide-total');

    $slider.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
      const currentIndex = (currentSlide ? currentSlide : 0) + 1;
      const totalSlides = slick.slideCount;
      $slideIndicator.text(currentIndex);
      $slideTotal.text(totalSlides);
    });

    $slider.slick({
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
      rtl: false,
      prevArrow: $slider.closest('.article-slider').find('.slick-prev'),
      nextArrow: $slider.closest('.article-slider').find('.slick-next'),
    });
  });

  if (screenWidth <= 1024) {
    $('.product-gallery__wrapper').slick({
      dots: true,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
      draggable: false,
      rtl: false,
    });
  }

  /**
   * Initialize modal slider on the product page
   */
  $('.slider-main').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    draggable: false,
    asNavFor: '.slider-nav'
  });

  $('.slider-nav').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: '.slider-main',
    infinite: false,
    dots: false,
    centerMode: false,
    focusOnSelect: true,
    arrows: false,
    vertical: true
  });

  $('.show-product-modal').on('click', function () {
    $('.product-modal').addClass('is-show');

    $('.product-modal-close').on('click', function () {
      $('.product-modal').removeClass('is-show');
      $(this).off('click');
    });
  })

  $(document).on('mousemove', '.zoomable', function (e) {
    const $zoomer = $(this);
    const offset = $zoomer.offset();
    const width = $zoomer.width();
    const height = $zoomer.height();

    // Disable transition while moving the mouse to avoid jerky behavior
    $zoomer.css('transition', 'none');

    // Calculate the mouse position relative to the element
    const xPercent = ((e.pageX - offset.left) / width) * 100;
    const yPercent = ((e.pageY - offset.top) / height) * 100;

    // Update background position to zoom
    $zoomer.css('background-position', `${xPercent}% ${yPercent}%`);
  });

  // Re-enable transition after the mouse stops moving for smooth zoom effect
  $(document).on('mouseleave', '.zoomable', function () {
    $(this).css('transition', 'background-position 0.1s ease');
  });

  /**
   * Initializes the customer menu slider on the customer page.
   * This function is called on page load and on window resize.
   * If the window width is less than or equal to 768px, the slider is initialized.
   * If the window width is greater than 768px, the slider is destroyed.
   * @function
   */
  function initCustomerMenuSlider() {
    if ($(window).width() <= 768) {
      if (!$('.customer-menu-links, .profile-navigation ul').hasClass('slick-initialized')) {
        $('.customer-menu-links, .profile-navigation ul').slick({
          dots: false,
          arrows: false,
          infinite: false,
          slidesToShow: 5,
          slidesToScroll: 1,
          rtl: true,
          responsive: [{
              breakpoint: 600,
              settings: {
                slidesToShow: 4,
              }
            },
            {
              breakpoint: 500,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 400,
              settings: {
                slidesToShow: 2,
              }
            },
          ]
        });
      }
    } else {
      if ($('.customer-menu-links').hasClass('slick-initialized')) {
        $('.customer-menu-links').slick('unslick');
      }
    }
  }

  // Initialize on page load
  initCustomerMenuSlider();

  // Reinitialize on window resize
  $(window).resize(function () {
    initCustomerMenuSlider();
  });

}(jQuery));
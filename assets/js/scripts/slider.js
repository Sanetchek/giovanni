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

  const $zoomableElement = $('.zoomable');
  let initialDistance = 0;
  let currentScale = 1;
  let startX = 0;
  let startY = 0;
  let currentX = 0;
  let currentY = 0;

  const getDistance = (touches) => {
    const dx = touches[1].clientX - touches[0].clientX;
    const dy = touches[1].clientY - touches[0].clientY;
    return Math.sqrt(dx * dx + dy * dy);
  };

  if ($(window).width() <= 992) {
    $zoomableElement.on('touchstart', function (e) {
      if (e.touches.length === 2) {
        // Pinch-to-zoom start
        initialDistance = getDistance(e.touches);
        $zoomableElement.css('transition', 'none'); // Disable smooth transition
      } else if (e.touches.length === 1 && currentScale > 1) {
        // Panning start
        startX = e.touches[0].clientX - currentX;
        startY = e.touches[0].clientY - currentY;
      }
    });

    $zoomableElement.on('touchmove', function (e) {
      e.preventDefault(); // Prevent default scrolling behavior

      if (e.touches.length === 2) {
        // Pinch-to-zoom
        const currentDistance = getDistance(e.touches);
        const scaleChange = currentDistance / initialDistance;

        // Calculate and clamp new scale
        currentScale = Math.min(Math.max(currentScale * scaleChange, 1), 3);
        $zoomableElement.css('background-size', `${currentScale * 100}%`);
      } else if (e.touches.length === 1 && currentScale > 1) {
        // Panning
        currentX = e.touches[0].clientX - startX;
        currentY = e.touches[0].clientY - startY;

        $zoomableElement.css('background-position', `${currentX}px ${currentY}px`);
      }
    });

    $zoomableElement.on('touchend', function () {
      // Reset or clamp background position on touch end
      if (currentScale === 1) {
        $zoomableElement.css({
          transition: 'background-position 0.2s ease-out',
          'background-position': 'center',
        });
      }
    });
  }

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
          arrows: true,
          infinite: false,
          slidesToShow: 5,
          slidesToScroll: 1,
          rtl: true,
          prevArrow: '<button class="slick-prev"></button>',
          nextArrow: '<button class="slick-next"></button>',
          responsive: [
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 4
              }
            },
            {
              breakpoint: 500,
              settings: {
                slidesToShow: 3
              }
            },
            {
              breakpoint: 400,
              settings: {
                slidesToShow: 2
              }
            }
          ]
        });
      }

      // Scroll to the `.current` slide after initializing Slick
      const $currentSlide = $('.customer-menu-links li.customer-menu-item.current, .profile-navigation ul li.customer-menu-item.current');
      if ($currentSlide.length > 0) {
        const index = $currentSlide.index(); // Get the correct index inside Slick
        $('.customer-menu-links, .profile-navigation ul').slick('slickGoTo', index);
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
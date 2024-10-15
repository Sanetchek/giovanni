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
      responsive: [{
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

  // Product page Modal Image Functionality
  let isDragging = false;
  let img = $('.slider-main img');
  let startX, startY, initialLeft, initialTop, initialWidth, initialHeight;
  let scale = 1;

  function startDragging(e) {
    isDragging = true;
    startX = e.pageX || e.originalEvent.touches[0].pageX;
    startY = e.pageY || e.originalEvent.touches[0].pageY;
    initialLeft = parseInt(img.css('left'));
    initialTop = parseInt(img.css('top'));
    $('.slider-main').css('cursor', 'grabbing'); // Change cursor during dragging
  }

  function stopDragging() {
    isDragging = false;
    $('.slider-main').css('cursor', 'grab'); // Change cursor back after dragging
  }

  function performDragging(e) {
    if (isDragging) {
      let moveX = (e.pageX || e.originalEvent.touches[0].pageX) - startX;
      let moveY = (e.pageY || e.originalEvent.touches[0].pageY) - startY;
      img.css({
        left: initialLeft + moveX + 'px',
        top: initialTop + moveY + 'px'
      });
    }
  }

  function scaleImage(factor) {
    scale *= factor;
    img.css('transform', `scale(${scale})`);
  }

  function handleWheel(e) {
    e.preventDefault();
    let factor = e.originalEvent.deltaY < 0 ? 1.1 : 0.9;
    scaleImage(factor);
  }

  function handlePinch(e) {
    if (e.touches.length == 2) {
      e.preventDefault();
      let touch1 = e.touches[0];
      let touch2 = e.touches[1];
      let currentDistance = Math.sqrt(Math.pow(touch2.pageX - touch1.pageX, 2) + Math.pow(touch2.pageY - touch1.pageY, 2));

      if (!initialWidth) {
        initialWidth = img.width();
        initialHeight = img.height();
      }

      if (!initialLeft) {
        initialLeft = currentDistance;
      }

      let factor = currentDistance / initialLeft;
      scaleImage(factor);
    }
  }

  $('.slider-main .product-modal__image').on('mousedown touchstart', startDragging);
  $(document).on('mousemove touchmove', performDragging);
  $(document).on('mouseup touchend', stopDragging);
  $('.slider-main .product-modal__image').on('wheel', handleWheel);
  $('.slider-main .product-modal__image').on('touchmove', handlePinch);

  $('.show-product-modal').on('click', function () {
    $('.product-modal').addClass('is-show');

    $('.product-modal-close').on('click', function () {
      $('.product-modal').removeClass('is-show');
      $(this).off('click'); // Remove the click event handler
    });
  })

}(jQuery));
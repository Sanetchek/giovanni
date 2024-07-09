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

  // #region Menu
  /**
   * Toggles the visibility of a menu.
   * @param {string} menuSelector - The selector for the menu to be toggled.
   * @param {boolean} show - If true, shows the menu; if false, hides the menu.
   */
  function toggleMenu(menuSelector, show) {
    const menu = $(menuSelector);
    if (show) {
      $('.mega-menu').removeClass('is-show');
      menu.addClass('is-show');
    } else {
      menu.removeClass('is-show');
    }
  }

  /**
   * Sets up event handlers to show and hide the menu on hover.
   * @param {string} triggerSelector - The selector for the element that triggers the menu.
   * @param {string} menuSelector - The selector for the menu to be shown/hidden.
   */
  function setupHoverHandler(triggerSelector, menuSelector) {
    $(triggerSelector).on('mouseenter', () => toggleMenu(menuSelector, true));
    $(triggerSelector).on('mouseleave', () => toggleMenu(menuSelector, false));

    $(menuSelector).on('mouseenter', () => toggleMenu(menuSelector, true));
    $(menuSelector).on('mouseleave', () => toggleMenu(menuSelector, false));
  }

  /**
   * Sets up event handlers to show and hide the menu on click.
   * @param {string} triggerSelector - The selector for the element that triggers the menu.
   * @param {string} menuSelector - The selector for the menu to be shown/hidden.
   */
  function setupClickHandler(triggerSelector, menuSelector) {
    $(triggerSelector).on('click', (e) => {
      e.preventDefault();
      const isShown = $(menuSelector).hasClass('is-show');
      toggleMenu(menuSelector, !isShown);
    });
  }

  /**
   * Adds a close button to the menu header if screen width is less than or equal to 1024px.
   * @param {string} menuSelector - The selector for the menu to be shown/hidden.
   */
  function addCloseButton(menuSelector) {
    $(menuSelector).find('.mega-menu-header').on('click', () => toggleMenu(menuSelector, false));
  }

  /**
   * Adjusts the menu event handlers based on the screen width.
   * If the screen width is greater than 1024px, sets up hover handlers.
   * If the screen width is 1024px or less, sets up click handlers.
   */
  function adjustMenuHandlers() {
    const screenWidth = $(window).width();
    if (screenWidth > 1024) {
      // Remove click handlers
      $('.show-jewellery a').off('click');
      $('.show-collection a').off('click');
      $('#jewellery_menu').off('click');
      $('#collection_menu').off('click');

      // Setup hover handlers
      setupHoverHandler('.show-jewellery a', '#jewellery_menu');
      setupHoverHandler('.show-collection a', '#collection_menu');
    } else {
      // Remove hover handlers
      $('.show-jewellery a').off('mouseenter mouseleave');
      $('.show-collection a').off('mouseenter mouseleave');
      $('#jewellery_menu').off('mouseenter mouseleave');
      $('#collection_menu').off('mouseenter mouseleave');

      // Setup click handlers
      setupClickHandler('.show-jewellery a', '#jewellery_menu');
      setupClickHandler('.show-collection a', '#collection_menu');

      // Add close buttons
      addCloseButton('#jewellery_menu');
      addCloseButton('#collection_menu');
    }
  }

  // Adjust menu handlers on document ready and window resize
  $(document).ready(function () {
    adjustMenuHandlers();
    $(window).resize(adjustMenuHandlers);
  });

  // Open/Close Mobile Menu
  $('.burger-menu').on('click', function (e) {
    e.preventDefault();
    $('body').toggleClass('is-block');
    $('.main-navigation').toggleClass('is-show');
  })
  // #endregion Menu

}(jQuery));
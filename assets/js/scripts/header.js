'use strict';

(function ($) {
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
      setupHoverHandler('.show-charms a', '#charms_menu');
      setupHoverHandler('.show-gifts a', '#gifts_menu');
      setupHoverHandler('.show-about a', '#about_menu');
    } else {
      // Remove hover handlers
      $('.show-jewellery a').off('mouseenter mouseleave');
      $('.show-collection a').off('mouseenter mouseleave');
      $('#jewellery_menu').off('mouseenter mouseleave');
      $('#collection_menu').off('mouseenter mouseleave');
      $('.show-charms a').off('mouseenter mouseleave');
      $('.show-about a').off('mouseenter mouseleave');
      $('.show-gifts a').off('mouseenter mouseleave');
      $('#charms_menu').off('mouseenter mouseleave');
      $('#gifts_menu').off('mouseenter mouseleave');
      $('#about_menu').off('mouseenter mouseleave');

      // Setup click handlers
      setupClickHandler('.show-jewellery a', '#jewellery_menu');
      setupClickHandler('.show-collection a', '#collection_menu');
      setupClickHandler('.show-charms a', '#charms_menu');
      setupClickHandler('.show-gifts a', '#gifts_menu');
      setupClickHandler('.show-about a', '#about_menu');

      // Add close buttons
      addCloseButton('#jewellery_menu');
      addCloseButton('#collection_menu');
      addCloseButton('#charms_menu');
      addCloseButton('#gifts_menu');
      addCloseButton('#about_menu');
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

  /**
   * Show/Hide Mini Cart
   */
  $('.header-cart').on('click', function (e) {
    e.preventDefault();

    if ($('.header-mini-cart').hasClass('is-show')) {
      $('.header-mini-cart').removeClass('is-show').addClass('is-hidden');
    } else {
      $('.header-mini-cart').removeClass('is-hidden').addClass('is-show');
    }
  });

  /**
   * Hide Mini Cart
   */
  $('.header-mini-cart-close').on('click', function () {
    $('.header-mini-cart').removeClass('is-show').addClass('is-hidden');
  })

  /**
   * Show Login form
   */
  $('.show-login-form').on('click', function (e) {
    e.preventDefault();

    if ($('#login_modal').hasClass('is-show')) {
      $('#login_modal').removeClass('is-show').addClass('is-hidden');
    } else {
      $('#login_modal').removeClass('is-hidden').addClass('is-show');
    }
  })

  /**
   * Hide Login form
   */
  $('.login-modal-close').on('click', function (e) {
    e.preventDefault();
    $('#login_modal').addClass('is-hidden').removeClass('is-show')
  })

  /**
   * Footer accordion menu
   */
    if ($(window).width() <= 768) {
      $('.js-accordion-trigger, .accordion-row h2').on('click', function() {
        var $accordionRow = $(this).closest('.accordion-row');
        var $menuContainer1 = $accordionRow.next('.menu-footer-widget-1-container');
        var $menuContainer2 = $accordionRow.next('.menu-footer-widget-2-container');
        var $menuContainer3 = $accordionRow.next('.menu-footer-widget-3-container');
        var $menuContainer4 = $accordionRow.next('.menu-footer-widget-4-container');

        var isExpanded = $accordionRow.find('.js-accordion-trigger').attr('aria-expanded') === 'true';
        $accordionRow.find('.js-accordion-trigger').attr('aria-expanded', !isExpanded);
        $menuContainer1.slideToggle(200);
        $menuContainer2.slideToggle(200);
        $menuContainer3.slideToggle(200);
        $menuContainer4.slideToggle(200);

        $accordionRow.find('.toggle-icon.plus').toggle(isExpanded);
        $accordionRow.find('.toggle-icon.minus').toggle(!isExpanded);

        $accordionRow.toggleClass('active');
    });

    }

}(jQuery));
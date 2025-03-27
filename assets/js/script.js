'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;
  const screenWidth = $(window).width();

  /**
   * Initialize sliders on the homepage
   */
  $('.popular-product').each(function () {
    const $slider = $(this);
    const $slideIndicator = $slider.closest('.slider-container').find('.slide-indicator');
    const $slideTotal = $slider.closest('.slider-container').find('.slide-total');

    $slider.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
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
            slidesToShow: 1,
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

    $slider.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
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

  $('.show-product-modal').on('click', function() {
    $('.product-modal').addClass('is-show');

    $('.product-modal-close').on('click', function () {
      $('.product-modal').removeClass('is-show');
      $(this).off('click'); // Remove the click event handler
    });
  })

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

  // Like button
  $('.post-review__like').on('click', function () {
    const security = $(this).attr('data-nonce'),
      likeCount = $(this).find('.post-review__like-count'),
      likeIcon = $(this).find('.post-review__like-icon'),
      postID = $(this).attr('data-post-id');

    // AJAX call goes to our endpoint url
    $.ajax({
      url: ajax_url,
      type: 'post',
      data: {
        post: postID,
        nonce: security,
        num: $(likeCount).text(),
        action: 'process_simple_like', //callback function
      },
      success: function (response) {
        if (response.count) {
          let count = response.count
          let updated_likes = parseInt(count);
          $(likeCount).html(updated_likes);
        }

        let icon = response.icon

        $(likeIcon).html(icon);

        // Update the .header-like .header-count element
        $('.header-like .header-count').html(response.total_likes);
      },
      error: function (response) {
        console.log('error', response);
      },
    });
  });

  // Infinite Scroll for Products Loop
  if ($('#product-list').length) {
    var canBeLoaded = true, // this param allows to initiate the AJAX request only if necessary
      bottomOffset = 1500; // the distance (in px) from the page bottom when you want to load more posts

    $(window).scroll(function () {
      if ($(document).scrollTop() > ($(document).height() - bottomOffset) && canBeLoaded == true && giovanni.current_page < giovanni.max_page) {
        canBeLoaded = false;
        var data = {
          'action': 'load_more_products',
          'page': giovanni.current_page
        };

        $.ajax({
          url: ajax_url,
          data: data,
          type: 'POST',
          beforeSend: function (xhr) {
            canBeLoaded = false;
            $('#page-loader').removeClass('hidden');
          },
          success: function (data) {
            if (data) {
              $('#product-list').append(data); // insert new posts
              giovanni.current_page++;
              canBeLoaded = true; // the ajax is completed, now we can run it again
              $('#page-loader').addClass('hidden'); // hide loader

              // Change the URL without reloading the page
              const newUrl = giovanni.siteurl + '/page/' + giovanni.current_page;
              history.pushState(null, null, newUrl);
            }
          }
        });
      }
    });
  }

  /**
   *  Show Archive Text
   */
  $('#show-archive-text').on('click', function () {
    $('.archive-text-container').css({
      'height': 'auto',
      'overflow': 'visible'
    });
    $(this).hide();
  });

  /**
   * Open Filter event on shop/archive page
   */
  $('.open-filter').on('click', function () {
    $('.products-filter-container').toggleClass('is-show');
  });

  /**
   * Sticky Block Settings
   */
  if (screenWidth > 1024) {
    const $container = $('.product-summary'); // change it your container
    const $stickyBlock = $('.product-summary-content'); // change it to block is sticky

    if ($stickyBlock.length && $container.length) {
      $(window).on('scroll', () => {
        handleScroll($stickyBlock, $container);
      });
    }
  }

  const handleScroll = ($stickyBlock, $container) => {
    const scrollPosition = $(window).scrollTop();
    const containerOffsetTop = $container.offset().top;
    const containerHeight = $container.height();
    const containerBottom = containerOffsetTop + containerHeight;
    const stickyBlockHeight = $stickyBlock.outerHeight(true);
    const stickyBlockBottom = scrollPosition + stickyBlockHeight;

    if (scrollPosition > containerOffsetTop) {
      if (stickyBlockBottom < containerBottom) {
        setSticky($stickyBlock);
      } else {
        setBottomPosition($stickyBlock, containerHeight, stickyBlockHeight);
      }
    } else {
      resetPosition($stickyBlock);
    }
  };

  const setSticky = ($stickyBlock) => {
    $stickyBlock.css({
      position: 'fixed',
      top: '50px',
      'z-index': '9',
    });
  };

  const setBottomPosition = ($stickyBlock, containerHeight, stickyBlockHeight) => {
    $stickyBlock.css({
      position: 'absolute',
      top: `${containerHeight - stickyBlockHeight}px`,
      'z-index': '9',
    });
  };

  const resetPosition = ($stickyBlock) => {
    $stickyBlock.css({
      position: '',
      top: '',
    });
  };

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

  $('.header-mini-cart-close').on('click', function () {
    $('.header-mini-cart').removeClass('is-show').addClass('is-hidden');
  })

  /**
   * Show/Hide Share Btns
   */
  $('.product-share-btn').on('click', function () {
    $('.share-dropdown').toggle();
  })

  /**
   * Ajax update Single Product add to cart
   */
  $('.single_add_to_cart_button').on('click', function (e) {
    e.preventDefault();

    var $thisbutton = $(this),
      $form = $thisbutton.closest('form.cart'),
      id = $thisbutton.val(),
      product_qty = $form.find('input[name=quantity]').val() || 1,
      product_id = $form.find('input[name=product_id]').val() || id,
      variation_id = $form.find('input[name=variation_id]').val() || 0;

    var data = {
      action: 'woocommerce_ajax_add_to_cart',
      product_id: product_id,
      product_sku: '',
      quantity: product_qty,
      variation_id: variation_id
    };

    $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

    $.ajax({
      type: 'post',
      url: ajax_url,
      data: data,
      beforeSend: function (response) {
        $thisbutton.removeClass('added').addClass('loading');
      },
      complete: function (response) {
        $thisbutton.addClass('added').removeClass('loading');
      },
      success: function (response) {
        if (response.error & response.product_url) {
          window.location = response.product_url;
          return;
        } else {
          $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
        }
      }
    });

    return false;
  });

  /**
   * Video PLay/Stop
   */
  $('.video-controls').click(function () {
    var $video = $(this).siblings('.video-element')[0];
    var $container = $(this).closest('.video-container');

    if ($video.paused) {
      $video.play();
      $container.removeClass('stop').addClass('play');
    } else {
      $video.pause();
      $container.removeClass('play').addClass('stop');
    }
  });

  /**
   * Open Modal Search
   */
  $('.header-search').on('click', function () {
    $('#search_modal').addClass('is-show');
  })

  /**
   * Close Modal Search
   */
  $('.search-modal-close').on('click', function () {
    $('#search_modal').removeClass('is-show');
  })

  /**
   * AJAX Modal Search
   */
  $('#search_form_modal').on('submit', function (e) {
    e.preventDefault();
    const searchValue = $(this).find('#search_modal').val();
    const submitButton = $(this).find('.search-form-submit');

    $('.search-modal-error').hide();

    $.ajax({
      url: ajax_url,
      method: 'GET',
      data: {
        action: 'giovanni_search',
        s_modal: searchValue,
        nonce: giovanni.search_nonce
      },
      beforeSend: function (response) {
        submitButton.addClass('is-loaded').prop('disabled', true);
      },
      complete: function (response) {
        submitButton.removeClass('is-loaded').prop('disabled', false);
      },
      success: function (data) {
        $('#search_modal_content').html(data);
      },
      error: function () {
        $('.search-modal-error').show();
      }
    });
  });

}(jQuery));
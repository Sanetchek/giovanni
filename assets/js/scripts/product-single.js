'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;
  const screenWidth = $(window).width();

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

}(jQuery));
'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  /**
   * Show/Hide Share Btns
   */
  $('.product-share-btn').on('click', function () {
    $('.share-dropdown').toggle();
  })

  /**
   * Show Mini Cart
   */
  $('.single_add_to_cart_button').on('click', function () {
    setTimeout(() => {
      $('.header-cart').trigger('click');
    }, 2000);
  })

  /**
   * Ajax update Single Product add to cart
   */
  if (!$('.product-type-wgm_gift_card').length) {
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
  }


}(jQuery));



document.addEventListener("DOMContentLoaded", function () {
  const radioGroups = document.querySelectorAll('ul[role="radiogroup"]');

  radioGroups.forEach(function (ul) {
    const listItems = ul.querySelectorAll('li');

    if (listItems.length < 2) {
      ul.style.display = "none";
    }
  });
});
 



(function($){
  window.vimeoPlayers = [];
  const iframes = Array.from(document.querySelectorAll('iframe.js-vimeo'));
  iframes.forEach(el => {
    const p = new Vimeo.Player(el);
    p.setVolume(0).catch(()=>{});
    window.vimeoPlayers.push(p);
  });

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        const el = entry.target;
        const player = window.vimeoPlayers.find(pp => pp.element === el);
        if (!player) return;
        if (entry.isIntersecting && entry.intersectionRatio >= 0.6) {
          player.play().catch(()=>{});
        } else {
          player.pause().catch(()=>{});
        }
      });
    }, { threshold: [0, 0.6] });
    iframes.forEach(el => io.observe(el));
  }

  const $slider = $('.product-gallery__wrapper');

  $slider
    .on('mousedown touchstart', '.product-gallery__video', function(){
      $(this).addClass('dragging');
    })
    .on('mouseup touchend touchcancel', '.product-gallery__video', function(){
      $(this).removeClass('dragging');
    });

  $slider.on('beforeChange', function(){
    if (window.vimeoPlayers) {
      window.vimeoPlayers.forEach(p => p.pause().catch(()=>{}));
    }
  });
})(jQuery);

document.querySelectorAll('.js-vimeo').forEach((iframe) => {
  const player = new Vimeo.Player(iframe);
  Promise.all([player.getVideoWidth(), player.getVideoHeight()]).then(([w, h]) => {
    if (w && h) {
      iframe.parentElement.style.aspectRatio = `${w} / ${h}`;
    }
  });
});
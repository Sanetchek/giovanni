'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  /**
   * Get already displayed product IDs from current DOM.
   */
  function getShownProductIds() {
    return $('.product-card[data-id]').map(function () {
      return String($(this).attr('data-id'));
    }).get();
  }

  /**
   * Remove duplicated products from the whole current product list.
   * This is final frontend protection after append.
   */
  function normalizeProductList() {
    const seenIds = new Set();

    $('#product-list .product-card[data-id]').each(function () {
      const productId = String($(this).attr('data-id'));

      if (seenIds.has(productId)) {
        $(this).remove();
        return;
      }

      seenIds.add(productId);
    });
  }

  /**
   * Remove duplicate product cards from AJAX response before append.
   *
   * It removes:
   * 1. Products already visible on the page.
   * 2. Products duplicated inside the same AJAX response.
   */
  function removeDuplicateProductsFromResponse(response) {
    const shownIds = new Set(getShownProductIds());
    const responseIds = new Set();
    const $response = $('<div>').html(response);

    $response.find('.product-card[data-id]').each(function () {
      const productId = String($(this).attr('data-id'));

      if (shownIds.has(productId) || responseIds.has(productId)) {
        $(this).remove();
        return;
      }

      responseIds.add(productId);
    });

    return $response.html();
  }

  /**
   * Infinite Scroll for Products Loop
   */
  if ($('#page-loader').length) {
    var canBeLoaded = true;
    var bottomOffset = 2500;

    $(window).on('scroll', function () {
      if (
        $(document).scrollTop() > $(document).height() - bottomOffset &&
        canBeLoaded &&
        window.giovanni.current_page < window.giovanni.max_page
      ) {
        canBeLoaded = false;

        var shownIds = getShownProductIds();

        var data = {
          action: 'load_more_products',
          page: window.giovanni.current_page,
          formData: $('#product-filters').serialize(),
          category_id: window.giovanni.current_category_id || '',
          shown_ids: shownIds,
          nonce: window.giovanni.product_filter_nonce
        };

        $.ajax({
          url: ajax_url,
          data: data,
          type: 'POST',

          beforeSend: function () {
            canBeLoaded = false;
            $('#page-loader').removeClass('hidden');
          },

          success: function (response) {
            var cleanResponse = removeDuplicateProductsFromResponse(response);

            if ($.trim(cleanResponse).length) {
              $('#product-list').append(cleanResponse);
              normalizeProductList();
            }

            window.giovanni.current_page++;
            canBeLoaded = true;
            $('#page-loader').addClass('hidden');
          },

          error: function () {
            canBeLoaded = true;
            $('#page-loader').addClass('hidden');
          }
        });
      }
    });
  }

  /**
   * Show Archive Text
   */
  $('#show-archive-text').on('click', function () {
    $('.archive-text-container').css({
      height: 'auto',
      overflow: 'visible'
    });

    $(this).hide();
  });

  /**
   * Slider Category Boxes
   */
  $('.slick-slider-boxes').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    infinite: false,
    rows: 0,
    centerMode: true,
    centerPadding: '0px',
    draggable: true,
    swipe: true,
    rtl: true,
    dots: false,
    arrows: false,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1
        }
      }
    ]
  });

}(jQuery));



function checkIds() {


  const idCounts = {};
  const allIds = [];
  const $cards = jQuery('.product-card[data-id]');

  $cards.each(function () {
    const id = String(jQuery(this).attr('data-id'));

    if (id) {
      allIds.push(id);
      idCounts[id] = (idCounts[id] || 0) + 1;
    }
  });

  const duplicateIds = Object.keys(idCounts).filter(function (id) {
    return idCounts[id] > 1;
  });

  console.log('Total product cards:', allIds.length);
  console.log('Unique product ids:', Object.keys(idCounts).length);
  console.log('All product ids:', allIds);
  console.log('test:');


  if (duplicateIds.length) {
    console.table(
      duplicateIds.map(function (id) {
        return {
          id: id,
          count: idCounts[id]
        };
      })
    );
  } else {
    console.log('Duplicate product ids: No duplicates found.');
  }
}

'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  // Infinite Scroll for Products Loop
  if ($('#product-list').length) {
    var canBeLoaded = true,
      bottomOffset = 2500;

    $(window).scroll(function () {
      console.log('max page', giovanni.max_page);
      if (
        $(document).scrollTop() > $(document).height() - bottomOffset &&
        canBeLoaded &&
        giovanni.current_page < giovanni.max_page
      ) {
        canBeLoaded = false;
        var data = {
          action: 'load_more_products',
          page: giovanni.current_page,
          formData: $('#product-filters').serialize(),
          category_id: giovanni.current_category_id || '',
          nonce: giovanni.product_filter_nonce
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
            $('#product-list').append(response);
            giovanni.current_page++;
            canBeLoaded = true;
            $('#page-loader').addClass('hidden');
          },
          error: function () {
            $('#page-loader').addClass('hidden'); // Hide loader on error as well
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
   *  Slider Category Boxes
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
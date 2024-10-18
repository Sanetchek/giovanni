'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  $('.btn-filter').on('click', function (e) {
    e.preventDefault();

    const parent = $(this).closest('.filter-wrap');
    const dropdown = $(parent).find('.filter-dropdown');

    $('.filter-dropdown').slideUp();

    if (!$(dropdown).is(':visible')) {
      $(dropdown).slideDown();
    } else {
      $(dropdown).slideUp();
    }
  })

  // Handle sort item click
  $('.filter-sort-item').on('click', function (e) {
    e.preventDefault();
    const filter = $(this).attr('data-sort');
    const parent = $(this).closest('.filter-wrap');
    const sortText = $(parent).find('.filter-sort-text');
    const dropdown = $(parent).find('.filter-dropdown');

    $('#sort').val(filter);
    $(sortText).html($(this).text());
    $(dropdown).slideUp();

    $('#product-filters').submit(); // Trigger form submission
  });

  // Handle checkbox change
  $('.filter-checkbox').on('change', function (e) {
    $('#product-filters').submit(); // Trigger form submission
  });

  // Handle form submission
  $('#product-filters').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Reset the current page to 1
    giovanni.current_page = 1;

    // Collect all filled fields
    const formData = $(this).serialize();

    // AJAX request to handle the filter and sorting
    $.ajax({
      url: ajax_url,
      method: 'POST',
      data: {
        action: 'filter_products',
        formData: formData,
        nonce: giovanni.product_filter_nonce
      },
      success: function (response) {
        $('#product-list').html(response);
      },
      error: function (error) {
        console.error('Error fetching products:', error);
      }
    });
  });

  $('.mobile-header .reset.js-reset').on('click', function() {
    location.reload(); 
  });

  $('.mobile-header .js-close-filters').on('click', function() {
    $('.open-filter').trigger('click'); 
  });  

}(jQuery));
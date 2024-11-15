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

  // Handle sort item click - mobile version
  $('.filter-sort-item').on('click', function (e) {
    e.preventDefault();
    const filter = $(this).attr('data-sort');
    const parent = $(this).closest('.filter-wrap');
    const sortText = $(parent).find('.filter-sort-text');
    const dropdown = $(parent).find('.filter-dropdown');

    $('#sort').val(filter);
    $(sortText).html($(this).text());
    $(dropdown).slideUp();

    setTimeout(() => {
      $('#product-filters').submit(); // Trigger form submission
    }, 500)
  });

  // Handle checkbox change
  $('.filter-checkbox').on('change', function (e) {
    const parent = $(this).closest('.filter-dropdown');
    const label = $(this).closest('.dropdown-filter-item');
    const name = $(label).find('.dropdown-filter-value').text();
    const attr = $(this).attr('data-attr');

    if ($(this).is(':checked')) {
      // Add the button when the checkbox is checked
      $('#filter_attr_list').prepend(`<button type="button" class="filter-value" data-attr="${attr}">${name}</button>`);
    } else {
      // Remove the button when the checkbox is unchecked
      $(`#filter_attr_list .filter-value[data-attr="${attr}"]`).remove();
    }

    $(parent).slideUp();
    $('.filter-attr.is-show').removeClass('is-show');
    $('#product-filters').submit(); // Trigger form submission
  });

  $(document).on('click', '.filter-value', function () {
    const attr = $(this).attr('data-attr');

    // Find and uncheck the corresponding checkbox
    $(`.filter-checkbox[data-attr="${attr}"]`).prop('checked', false);

    // Remove the button
    $(this).remove();

    // Optionally trigger form submission
    $('#product-filters').submit();
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
        category_id: giovanni.current_category_id || '',
        nonce: giovanni.product_filter_nonce
      },
      beforeSend: function () {
        $('#product-list').addClass('blur');
      },
      success: function (response) {
        $('#product-list').html(response);
        $('#product-list').removeClass('blur');
      },
      error: function (error) {
        console.error('Error fetching products:', error);
      }
    });
  });

  /**
   * Open Filter event on shop/archive page
   */
  $('.open-filter').on('click', function () {
    $('.filter-attr').toggleClass('is-show');
  });

  $('.mobile-header .reset.js-reset').on('click', function() {
    location.reload();
  });

  $('.mobile-header .js-close-filters').on('click', function() {
    $('.open-filter').trigger('click');
  });

}(jQuery));
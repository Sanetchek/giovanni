'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  /**
   * Open Modal Search
   */
  $('.header-search').on('click', function () {
    $('#search_modal').addClass('is-show');
    $('body').css('overflow-y', 'hidden')
  })

  /**
   * Close Modal Search
   */
  $('.search-modal-close').on('click', function () {
    $('#search_modal').removeClass('is-show');
    $('body').css('overflow-y', 'initial')
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
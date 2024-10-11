'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  /**
   * Open Modal Search
   */
  $('.header-search .icon-search').on('click', function () {
    $('form.header-form-search').fadeToggle();
  });

  /**
   * Typing check
   */
  let typingTimer; // Timer identifier
  let typingDelay = 500; // Delay time in milliseconds

  $('#header_search').on('keyup', function () {
    clearTimeout(typingTimer); // Clear previous timer if user continues typing
    typingTimer = setTimeout(searchAjaxRequest, typingDelay); // Set new timer
  });

  $('#header_search').on('keydown', function () {
    clearTimeout(typingTimer); // Clear timer on key down event to reset timer
  });

  // When the input field is focused, check if it has value and show results with fadeIn
  $('#header_search').on('focus', function () {
    let searchTerm = $(this).val();
    if (searchTerm.length > 0) {
      $('#search_results').fadeIn();
    } else {
      $('#search_results').fadeOut();
    }
  });

  // Fade out results when input loses focus (blur)
  $('#header_search').on('blur', function () {
    setTimeout(function () {
      $('#search_results').fadeOut();
    }, 200);
  });

  /**
   * Search request
   */
  function searchAjaxRequest() {
    let searchTerm = $('#header_search').val(); // Get the input value

    if (searchTerm.length > 0) {
      $.ajax({
        url: ajax_url, // Your AJAX URL or endpoint
        type: 'POST',
        data: {
          action: 'giovanni_search_suggestions',
          search: searchTerm,
          nonce: giovanni.search_nonce
        },
        success: function (response) {
          // Handle the successful response
          console.log(response);
          $('#search_results').html(response); // Populate search results
          $('#search_results').fadeIn(); // Fade in search results after getting response
        },
        error: function (xhr, status, error) {
          // Handle error
          console.log('AJAX Error: ' + error);
        }
      });
    } else {
      $('#search_results').fadeOut(); // Fade out search results if there's no search term
    }
  }


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
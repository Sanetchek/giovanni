'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  /**
   * Open Modal Search
   */
  $('.header-search .icon-search').on('click', function () {
    $('form.header-form-search').fadeToggle();
    $('.search-overlay-container').toggleClass('active');
    $('body').toggleClass('no-scroll');
  });


  /**
   * Typing check
   */
  let typingTimer; // Timer identifier
  let typingDelay = 500; // Delay time in milliseconds

  $('#header_search').on('keyup', function () {
    clearTimeout(typingTimer); // Clear previous timer if user continues typing
    typingTimer = setTimeout(searchAjaxRequest, typingDelay); // Set new timer
    if ($(this).val()) {
      $('.header-form-btns').fadeIn();
    } else {
      $('.header-form-btns').fadeOut();
    }
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
   * Clear Search form
   */
  $('#header_form_clear').on('click', function (e) {
    e.preventDefault();
    $('#header_search').val('');
    $('#search_results').fadeOut().html('');
    $('.header-form-btns').fadeOut();
  })

  /**
   * Search request
   */
   let searchTimeout;

   function searchAjaxRequest() {
     let searchTerm = $('#header_search').val().trim();
   
     if (searchTerm.length > 0) {
       clearTimeout(searchTimeout);
   
       searchTimeout = setTimeout(function () {
         $.ajax({
           url: ajax_url,
           type: 'POST',
           data: {
             action: 'giovanni_search_suggestions',
             search: searchTerm,
             nonce: giovanni.search_nonce
           },
           success: function (response) {
             $('#search_results').html(response).fadeIn();
           },
           error: function (xhr, status, error) {
             console.log('AJAX Error: ' + error);
           }
         });
       }, 300); 
     } else {
       $('#search_results').fadeOut(); 
     }
   }

   $('#header_search').on('input', searchAjaxRequest);
   

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

    if (searchValue.length === 0) {
      $('.search-modal-error').text('אנא הזן את בקשתך').show();
      return;
    }

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
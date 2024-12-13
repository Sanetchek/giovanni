'use strict';

(function ($) {
  // Function to validate amount
  function isValidAmount(amount) {
    const numericAmount = parseFloat(amount);
    return numericAmount >= 50 && numericAmount <= 1000;
  }

  // Function to handle amount selection and validation
  function handleAmountSelection() {
    // Clear other selection when a radio button is selected
    $('.gift-radio').on('change', function () {
      $('.gift-certificate__custom-amount').val('');
      $('.gift-certificate__form-section').hide();
    });

    // Clear radio selection when custom amount is entered
    $('.gift-certificate__custom-amount').on('input', function () {
      $('.gift-radio').prop('checked', false);
      $('.gift-certificate__form-section').hide();
    });

    // Next step button click handler
    $('.gift-certificate__next-step').on('click', function (e) {
      e.preventDefault();

      // Get selected amount from radio or custom input
      const selectedAmount = $('input[name="giftAmount"]:checked').val() ||
        $('.gift-certificate__custom-amount').val();

      if (!isValidAmount(selectedAmount)) {
        $('.error-label').show();
        return;
      }

      // Hide error, show next section
      $('.error-label').hide();
      $('.gift-certificate__form-section').slideDown();
    });
  }

  // Initialize amount selection handling
  handleAmountSelection();

  // Step 2: Validate custom amount
  function isValidAmount(amount) {
    const numericAmount = parseFloat(amount);

    return !isNaN(numericAmount) && numericAmount >= 50 && numericAmount <= 1000;
  }

  // Step 3: Validate form fields
  $('#senderName, #reciverEmail, #message').on('blur', function () {
    const parent = $(this).closest('.form-group');

    if ($(this).val()) {
      $(this).removeClass('error');
      $(parent).find('.invalid-feedback').hide();
    } else {
      $(this).addClass('error');
      $(parent).find('.invalid-feedback').show();
    }
  });

  // Step 4: Form submission
  $('#gift_form').on('submit', function (e) {
    e.preventDefault();

    // Collect form data
    const senderName = $('#senderName').val();
    const reciverEmail = $('#reciverEmail').val();
    const message = $('#message').val();
    const selectedAmount = $('input[name="giftAmount"]:checked').val() || $('.gift-certificate__custom-amount').val();

    if (!senderName || !reciverEmail || !selectedAmount) {
      return;
    }

    $.ajax({
      url: giovanni.ajax_url, // Make sure this is defined in your wp_localize_script
      type: 'POST',
      data: {
        action: 'add_gift_card_to_cart',
        giftAmount: selectedAmount,
        senderName: senderName,
        reciverEmail: reciverEmail,
        message: message,
        _wpnonce: giovanni.gift_nonce,
      },
      success: function (response) {
        if (response.success) {
          window.location.href = '/cart';
        } else {
          console.log('Failed to add gift card to cart.');
        }
      },
      error: function () {
        console.log('Error:', 'An error occurred. Please try again.');
      },
    });
  });

})(jQuery);

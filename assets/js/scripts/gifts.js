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
      $('.gift-certificate__form-section').addClass('disabled');
      $('.gift-certificate__next-step').prop('disabled', false);
    });

    // Clear radio selection when custom amount is entered
    $('.gift-certificate__custom-amount').on('input', function () {
      $('.gift-radio').prop('checked', false);
      $('.gift-certificate__form-section').addClass('disabled');
      $('.gift-certificate__next-step').prop('disabled', false);
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
      $('.gift-certificate__form-section').removeClass('disabled');
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
  $('#senderName, #reciverName, #reciverEmail, #reciverConfirmEmail').on('blur', function () {
    const parent = $(this).closest('.form-group');

    if ($(this).val()) {
      $(this).removeClass('error');
      $(parent).find('.invalid-feedback').hide();
    } else {
      $(this).addClass('error');
      $(parent).find('.invalid-feedback').show();
    }
  });

  $('#reciverConfirmEmail').on('input', function () {
    const reciverEmail = $('#reciverEmail').val().trim();
    const reciverConfirmEmail = $(this).val().trim();

    if (reciverEmail !== reciverConfirmEmail) {
      $('.emailError').show();
    } else {
      $('.emailError').hide();
    }
  });

  // Step 4: Form submission
  $('#gift_form').on('submit', function (e) {
    e.preventDefault();

    console.log('giovanni:', giovanni);


    // Collect form data
    const senderName = $('#senderName').val();
    const reciverName = $('#reciverName').val();
    const reciverEmail = $('#reciverEmail').val();
    const message = $('#message').val();
    const selectedAmount = $('input[name="giftAmount"]:checked').val() || $('.gift-certificate__custom-amount').val();

    console.log('Form data:', {
      senderName,
      reciverName,
      reciverEmail,
      selectedAmount
    });

    if (!senderName || !reciverName || !reciverEmail || !selectedAmount) {
      return;
    }

    console.log('Ajax URL:', giovanni.ajax_url);
    console.log('Nonce:', giovanni.gift_nonce);



    $.ajax({
      url: giovanni.ajax_url, // Make sure this is defined in your wp_localize_script
      type: 'POST',
      data: {
        action: 'add_gift_card_to_cart',
        giftAmount: selectedAmount,
        senderName,
        reciverName,
        reciverEmail,
        message,
        _wpnonce: giovanni.gift_nonce,
      },
      success: function (response) {
        console.log('AJAX success:', response);
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

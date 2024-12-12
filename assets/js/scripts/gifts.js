'use strict';

(function ($) {
  // Step 1: Manage visibility of sections
  $('.gift-radio').on('change', function () {
    $('.gift-certificate__form-section').hide();

    $('.gift-certificate__next-step').on('click', function (e) {
      e.preventDefault();

      const selectedAmount = $('input[name="giftAmount"]:checked')
        .siblings('.gift-amount')
        .text()
        .replace('₪', '') || $('.gift-certificate__custom-amount').val();

      if (!validateAmount(selectedAmount)) {
        $('.error-label').show();
        return;
      } else {
        $('.error-label').hide();
        $('.gift-certificate__form-section').slideDown();
      }
    });
  });

  // Step 2: Validate custom amount
  function validateAmount(amount) {
    const numericAmount = parseFloat(amount);
    return numericAmount >= 50 && numericAmount <= 1000;
  }

  // Step 3: Form submission
  $('#gift_form').on('submit', function (e) {
    e.preventDefault();

    // Collect form data
    const senderName = $('#senderName').val();
    const recivererName = $('#recivererName').val();
    const reciverEmail = $('#reciverEmail').val();
    const confirmReciverEmail = $('#confirmReciverEmail').val();
    const message = $('#message').val();
    const selectedAmount = $('input[name="giftAmount"]:checked').val();

    if (reciverEmail !== confirmReciverEmail) {
      $('#confirmReciverEmailFeedback').text('Emails do not match').show();
      return;
    } else {
      $('#confirmReciverEmailFeedback').hide();
    }

    if (!senderName || !recivererName || !reciverEmail || !selectedAmount) {
      alert('Please fill out all required fields.');
      return;
    }

    // Include nonce (replace 'your_nonce_variable' with the actual variable from the plugin or your theme)
    const nonce = giovanni.gift_nonce;

    $.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        action: 'add_gift_card_to_cart',
        giftAmount: selectedAmount,
        senderName: senderName,
        reciverName: recivererName,
        reciverEmail: reciverEmail,
        message: message,
        _wpnonce: nonce, // Include nonce
      },
      success: function (response) {
        if (response.success) {
          alert('Gift card added to cart!');
          window.location.href = '/cart';
        } else {
          alert(response.data || 'Failed to add gift card to cart.');
        }
      },
      error: function () {
        alert('An error occurred. Please try again.');
      },
    });
  });

})(jQuery);

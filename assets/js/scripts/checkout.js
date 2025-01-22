jQuery(document).ready(function ($) {
  // Wait until the terms wrapper exists
  const checkForTerms = setInterval(function () {
    const termsWrapper = $('.wc-block-checkout__terms');
    const placeOrderButton = $('.wc-block-components-checkout-place-order-button');

    if (termsWrapper.length && placeOrderButton.length) {
      clearInterval(checkForTerms); // Stop checking

      // Create the wrapper div
      const checkboxWrapper = $('<div>', {
        class: 'wc-block-components-checkbox',
      });

      // Create a checkbox input
      const checkbox = $('<input>', {
        id: 'terms',
        type: 'checkbox',
        class: 'wc-block-components-checkbox__input',
        name: 'terms',
        required: true,
        'aria-invalid': 'false',
      });

      // Create the SVG element
      const svg = $(`
        <svg class="wc-block-components-checkbox__mark" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 20">
          <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"></path>
        </svg>
      `);

      // Create the label span
      const labelSpan = $('<span>', {
        class: 'wc-block-components-checkbox__label',
        html: `
          אני מסכים/מסכימה ל⁦
          <a href="/terms-conditions/" target="_blank">תנאים והתניות</a>
          ול⁦
          <a href="/privacy-policy/" target="_blank">מדיניות פרטיות</a>
        `,
      });

      // Create the label and append the elements
      const label = $('<label>', {
        for: 'terms',
      }).append(checkbox).append(svg).append(labelSpan);

      // Append the label to the wrapper
      checkboxWrapper.append(label);

      // Append the wrapper to the terms wrapper
      termsWrapper.empty().append(checkboxWrapper);

      // Intercept place order button click
      placeOrderButton.on('click', function (e) {
        // Check if the terms checkbox is checked
        if (!$('#terms').is(':checked')) {
          alert('עליך להסכים לתנאים ולהגבלות כדי לבצע את ההזמנה.');
          e.preventDefault();
          return false;
        }

        // If the checkbox is checked, trigger form submission
        $('form.woocommerce-checkout').submit();
      });
    }
  }, 100); // Check every 100ms
});

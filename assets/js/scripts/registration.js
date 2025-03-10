jQuery(document).ready(function ($) {
  $('#ajax-registration-form').on('submit', function (e) {
    e.preventDefault();
    console.log('ok');


    // Basic validation
    const birthDate = $('#birth-date').val();
    const email = $('#email').val();
    const confirmEmail = $('#confirm-email').val();
    const password = $('#password').val();
    const confirmPassword = $('#confirm-password').val();

    const currentDate = new Date();
    const enteredDate = new Date(birthDate);

    // Password validation regex: At least 8 characters, one uppercase, one special character
    const passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

    if (birthDate && (enteredDate > currentDate || enteredDate.getFullYear() < 1900)) {
      showError('birth-date', 'נא להזין תאריך לידה חוקי.');
      return;
    }

    if (email !== confirmEmail) {
      showError('confirm-email', 'המיילים אינם תואמים.');
      return;
    }

    if (!passwordRegex.test(password)) {
      showError('password', 'הסיסמה חייבת להיות לפחות 8 תווים, לכלול אות אחת גדולה ותו מיוחד אחד.');
      return;
    }

    if (password !== confirmPassword) {
      showError('confirm-password', 'הסיסמאות אינן תואמות.');
      return;
    }

    // AJAX request
    $.ajax({
      url: giovanni.ajax_url,
      type: 'POST',
      data: {
        action: 'giovanni_register_user',
        nonce: giovanni.registration_nonce,
        formData: $(this).serialize(),
      },
      success: function (response) {
        if (!response.success) {
          // Display the error message from PHP
          showError(null, response.data.message);
        } else {
          $('#ajax-registration-form')[0].reset(); // Reset the form
          $('.form-message').remove(); // Clear any previous messages
          $('#ajax-registration-form-submit').after('<p class="form-message success">' + response.data.message + '</p>');
        }
      },
      error: function () {
        showError(null, 'There was an error processing the registration.');
      },
    });
  });

  // Function to display error messages
  function showError(field, message) {
    $('.form-message').remove(); // Remove any previous messages
    $('.error').removeClass('error'); // Remove any previous error class
    if (field) {
      $('#' + field).after('<p class="form-message error">' + message + '</p>');
      $('#' + field).addClass('error');
    } else {
      $('#ajax-registration-form-submit').after('<p class="form-message error">' + message + '</p>');
    }
  }

  // Function to show an error message
  function showRequiredError(field, message) {
    const $field = $('#' + field);
    const $error = $field.next('.form-message.error');

    if (!$error.length) {
      $field.after('<p class="form-message error">' + message + '</p>');
    }
  }

  // Function to remove the error message
  function removeRequiredError(field) {
    const $field = $('#' + field);
    $field.next('.form-message.error').remove();
  }

  // Real-time validation for required fields
  $('#ajax-registration-form input[required]').on('focusout input', function () {
    const fieldId = $(this).attr('id');
    const value = $(this).val().trim();
    const $field = $('#' + fieldId);

    if (!value) {
      showRequiredError(fieldId, 'אנא מלא שדה זה.');
      $($field).addClass('error');
    } else {
      removeRequiredError(fieldId);
      $($field).removeClass('error');
    }
  });
});

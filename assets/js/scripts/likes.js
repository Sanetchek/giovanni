'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  $('.product-remove-like').on('click', function () {
    const parent = $(this).closest('.product-card');
    const likeBtn = $(parent).find('.post-review__like');

    $(likeBtn).click();
    $(parent).remove();

    if ($('.product-card').length === 0) {
      window.location.reload();
    }
  });

  // Like button
  let messageTimeout; // Global timeout reference
  $('.post-review__like').on('click', function (e) {
    e.preventDefault();

    const $this = $(this);

    // Prevent multiple clicks
    if ($this.hasClass('disabled')) return;
    clearTimeout(messageTimeout); // Clear any existing timeout

    const security = $this.attr('data-nonce'),
      likeCount = $this.find('.post-review__like-count'),
      likeIcon = $this.find('.post-review__like-icon'),
      postID = $this.attr('data-post-id');

    // Reference message box and spans
    const $messageBox = $('#simple-like-process-message'),
      $processing = $messageBox.find('.simple-like-process-message-text'),
      $success = $messageBox.find('.simple-like-success-message-text'),
      $removed = $messageBox.find('.simple-like-removed-message-text'),
      $error = $messageBox.find('.simple-like-error-message-text');

    $.ajax({
      url: ajax_url,
      type: 'post',
      data: {
        post: postID,
        nonce: security,
        num: $(likeCount).text(),
        action: 'process_simple_like',
      },
      beforeSend: function () {
        // Show processing message
        $this.addClass('disabled');
        $messageBox.show();
        $processing.show();
        $success.hide();
        $removed.hide();
        $error.hide();
      },
      success: function (response) {
        if (response.count) {
          let updated_likes = parseInt(response.count);
          likeCount.html(updated_likes);
        }

        if (response.icon) {
          likeIcon.html(response.icon);
        }

        $('.header-like .header-count').html(response.total_likes);

        // Show success message
        $processing.hide();

        if (response.status === 'removed') {
          $removed.show();
        } else {
          $success.show();
        }
        $this.removeClass('disabled');

        messageTimeout = setTimeout(() => {
          $messageBox.fadeOut(300);
          resetLikeMessages();
        }, 5000);
      },
      error: function () {
        // Show error message
        $processing.hide();
        $error.show();
        $this.removeClass('disabled');

        messageTimeout = setTimeout(() => {
          $messageBox.fadeOut(300);
          resetLikeMessages();
        }, 2000);
      },
    });

    function resetLikeMessages() {
      $processing.hide();
      $success.hide();
      $removed.hide();
      $error.hide();
    }
  });
}(jQuery));
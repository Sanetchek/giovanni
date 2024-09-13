'use strict';

(function ($) {
  const ajax_url = window.giovanni.ajax_url;

  // Like button
  $('.post-review__like').on('click', function () {
    const security = $(this).attr('data-nonce'),
      likeCount = $(this).find('.post-review__like-count'),
      likeIcon = $(this).find('.post-review__like-icon'),
      postID = $(this).attr('data-post-id');

    // AJAX call goes to our endpoint url
    $.ajax({
      url: ajax_url,
      type: 'post',
      data: {
        post: postID,
        nonce: security,
        num: $(likeCount).text(),
        action: 'process_simple_like', //callback function
      },
      success: function (response) {
        if (response.count) {
          let count = response.count
          let updated_likes = parseInt(count);
          $(likeCount).html(updated_likes);
        }

        let icon = response.icon

        $(likeIcon).html(icon);

        // Update the .header-like .header-count element
        $('.header-like .header-count').html(response.total_likes);
      },
      error: function (response) {
        console.log('error', response);
      },
    });
  });

}(jQuery));
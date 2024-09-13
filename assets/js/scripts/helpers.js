'use strict';

(function ($) {
  /**
   * Video PLay/Stop
   */
  $('.video-controls').click(function () {
    var $video = $(this).siblings('.video-element')[0];
    var $container = $(this).closest('.video-container');

    if ($video.paused) {
      $video.play();
      $container.removeClass('stop').addClass('play');
    } else {
      $video.pause();
      $container.removeClass('play').addClass('stop');
    }
  });

}(jQuery));
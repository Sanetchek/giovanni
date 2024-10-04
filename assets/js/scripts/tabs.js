(function ($) {
  const tabs = $('.category-tabs a');
  const contents = $('.tab-content');

  tabs.on('click', function (e) {
    e.preventDefault();

    tabs.removeClass('active');
    contents.removeClass('active');

    $(this).addClass('active');
    const contentId = $(this).attr('href');
    $(contentId).addClass('active');
  });

  // Activate the first tab and content by default
  if (tabs.length > 0) {
    tabs.first().addClass('active');
    contents.first().addClass('active');
  }

  // Tab toggle functionality
  $('.tab-btn').on('click', function () {
    const item = $(this).closest('.tab-item');
    const content = $(item).find('.tab-content');

    $('.tab-item').removeClass('active');
    $('.tab-content').slideUp();

    if (!content.is(':visible')) {
      content.slideDown();
      $(item).addClass('active');
    }
  });

  // Show the first tab and its content by default
  $('.tab-item').first().addClass('active');
  $('.tab-item').first().find('.tab-content').slideDown();
}(jQuery));
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
  $('body').on('click', '.tab-btn', function () {
    const item = $(this).closest('.tab-item'); // Current tab item
    const content = $(item).find('.tab-content'); // Current tab content

    // Close all other tabs
    $('.tab-item').not(item).removeClass('active'); // Remove 'active' from all other tabs
    $('.tab-content').not(content).slideUp(); // Slide up all other tab contents

    // Toggle the current tab
    if (content.is(':visible')) {
      content.slideUp(); // Close current tab if it's open
      $(item).removeClass('active'); // Remove active state from current tab
    } else {
      content.slideDown(); // Open current tab
      $(item).addClass('active'); // Add active state to current tab
    }
  });

  // Show the first tab and its content by default
  $('.tab-item').first().addClass('active');
  $('.tab-item').first().find('.tab-content').slideDown();
}(jQuery));
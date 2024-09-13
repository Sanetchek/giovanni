document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.category-tabs a');
  const contents = document.querySelectorAll('.tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', function (e) {
      e.preventDefault();

      tabs.forEach(t => t.classList.remove('active'));
      contents.forEach(content => content.classList.remove('active'));

      tab.classList.add('active');
      const contentId = tab.getAttribute('href');
      document.querySelector(contentId).classList.add('active');
    });
  });

  // Activate the first tab and content by default
  if (tabs.length > 0) {
    tabs[0].classList.add('active');
    contents[0].classList.add('active');
  }
});
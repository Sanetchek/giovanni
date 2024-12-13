jQuery(document).ready(function ($) {
  const $modal = $('#modal_subscription');
  const $modalOverlay = $('#modal-overlay');

  // Function to check if modal should be shown
  function shouldShowModal() {
    const lastShown = localStorage.getItem('modalLastShown');
    const currentDate = new Date().toDateString();

    return !lastShown || lastShown !== currentDate;
  }

  // Function to show modal
  function showModal() {
    if (shouldShowModal()) {
      setTimeout(() => {
        $modal.show();
        $modalOverlay.addClass('is-visible');

        // Store the current date to prevent showing again today
        localStorage.setItem('modalLastShown', new Date().toDateString());
      }, 10000); // 15 seconds delay
    }
  }

  // Function to close modal
  function closeModal() {
    $modal.hide();
    $modalOverlay.removeClass('is-visible');
  }

  // Attach close event to close button
  $modal.find('.modal-close').on('click', closeModal);

  // Attach close event to overlay
  $modalOverlay.on('click', closeModal);

  // Show modal
  showModal();
});
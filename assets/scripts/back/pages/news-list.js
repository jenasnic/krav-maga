const orderingNewsList = (form) => {
  const ranks = form.querySelectorAll('td.draggable input[data-rank]');
  ranks.forEach((rank, index) => {
    rank.value = index.toString();
  });
};

const initializePreviewButton = (button) => {
  button.addEventListener('click', (event) => {
    event.preventDefault();
    const modal = document.getElementById('modal-preview');
    const iframe = document.getElementById('iframe-preview');
    iframe.src = button.dataset.previewUrl;
    modal.classList.add('is-active');
  });
}

const initializeNewsListForm = (form) => {
  form.addEventListener('submit', () => {
    orderingNewsList(form);
  });

  form.querySelectorAll('button[data-preview-url]').forEach(initializePreviewButton)
};

const initializeModalPreview = () => {
  const modal = document.getElementById('modal-preview');
  modal.querySelectorAll('.modal-close, .modal-background').forEach((element) => {
    element.addEventListener('click', () => {
      modal.classList.remove('is-active');
    });
  })
};

const newsListForm = document.getElementById('news-list-form');

if (newsListForm) {
  initializeNewsListForm(newsListForm);
  initializeModalPreview();
}

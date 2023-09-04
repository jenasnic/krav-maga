const orderingNewsList = (form) => {
  const ranks = form.querySelectorAll('td.draggable input[data-rank]');
  ranks.forEach((rank, index) => {
    rank.value = index.toString();
  });
};

const initializeNewsListForm = (form) => {
  form.addEventListener('submit', (event) => {
    orderingNewsList(form);
  });
};

const newsListForm = document.getElementById('news-list-form');

if (newsListForm) {
  initializeNewsListForm(newsListForm);
}

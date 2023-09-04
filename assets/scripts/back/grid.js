import List from 'list.js';

export const bindGrid = (element) => {
  const searchInput = element.querySelector('#input-search-id');
  if (searchInput) {
    new List(element, {
      valueNames: JSON.parse(searchInput.dataset.search),
    });
  }
};

[...document.querySelectorAll('[data-grid]')].forEach((element) => {
  bindGrid(element);
});

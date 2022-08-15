import List from 'list.js';

export const bindGrid = (element) => {
  const searchInput = element.querySelector('#input-search-id');
  const options = {
    valueNames: JSON.parse(searchInput.dataset.search),
  };

  new List(element, options);
};

[...document.querySelectorAll('[data-grid]')].forEach((element) => {
  bindGrid(element);
});

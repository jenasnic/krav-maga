import List from 'list.js';

export const bindGrid = (element) => {
  const options = {
    valueNames: [ 'firstName', 'lastName', 'phone' ]
  };

  new List(element, options);
};

[...document.querySelectorAll('[data-grid]')].forEach((element) => {
  bindGrid(element);
});

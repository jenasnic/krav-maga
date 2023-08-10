const orderingPriceOptions = (form) => {
  const rankOptions = form.querySelectorAll('.collection-type-item input[data-rank]');
  rankOptions.forEach((rankOption, index) => {
    rankOption.value = index.toString();
  });
};

const initializeSeasonForm = (form) => {
  form.addEventListener('submit', (event) => {
    orderingPriceOptions(form);
  });
};

const seasonForm = document.getElementById('season-form');

if (seasonForm) {
  initializeSeasonForm(seasonForm);
}

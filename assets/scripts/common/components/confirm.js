export const bindConfirmForm = (form) => {
  const { confirmMessage: message, confirm: isBinded } = form.dataset;
  if (isBinded) {
    return;
  }

  form.dataset.confirm = 'binded';

  form.addEventListener('submit', (event) => {
    if (!confirm(message)) {
      event.preventDefault();
    }
  });
};

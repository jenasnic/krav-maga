export const bindBulmaFile = (element) => {
  const input = element.querySelector('input[type=file]');
  input.addEventListener('change', () => {
    if (input.files.length > 0) {
      const fileName = element.querySelector('.file-name');
      fileName.textContent = input.files[0].name;
    }
  });
};

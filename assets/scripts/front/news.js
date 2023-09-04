const bindNewsButton = (button) => {
  button.addEventListener('click', () => {
    button.parentNode.classList.toggle('show-text');
  });

}

[...document.querySelectorAll('.news-wrapper .news-button')].forEach(bindNewsButton);

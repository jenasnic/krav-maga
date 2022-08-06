const displayFlash = () => {
  const flash = document.querySelector('[data-flash]');
  if (!flash) {
    return;
  }

  flash.classList.add('show');
  setTimeout(closeFlash, 3500, flash);
}

const closeFlash = (flash) => {
  flash.classList.add('remove');
  setTimeout(removeFlash, 500, flash);
}

const removeFlash = (flash) => {
  flash.remove();
  displayFlash();
}

displayFlash();

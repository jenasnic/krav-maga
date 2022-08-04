export const displayNotification = () => {
  const notification = document.querySelector('.notification');
  if (!notification) {
    return;
  }

  notification.classList.add('show');
  setTimeout(removeNotification, 4000, notification);
}

const removeNotification = (element) => {
  element.remove();
  displayNotification();
}

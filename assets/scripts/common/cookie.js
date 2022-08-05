import 'tarteaucitronjs/css/tarteaucitron.css';

document.addEventListener('DOMContentLoaded', () => {

  const cookiePreferenceSelectors = [...document.querySelectorAll('.manage-cookies')];
  cookiePreferenceSelectors.forEach(selector => {
    selector.addEventListener('click', event => {
      event.preventDefault();
      window.tarteaucitron.userInterface.openPanel();
    });
  });

  window.tarteaucitron.init({
    orientation: 'bottom', /* Banner position (top - bottom) */
    closePopup: true, /* Show a close X on the banner */
    showIcon: false, /* Show cookie icon to manage cookies */
    iconPosition: 'BottomRight', /* BottomRight, BottomLeft, TopRight and TopLeft */
    moreInfoLink: false, /* Show more info link */
    useExternalCss: true, /* If false, the tarteaucitron css file will be loaded */
    useExternalJs: true, /* If false, the tarteaucitron js file will be loaded */
    readmoreLink: '', /* Change the default readmore link */
  });

  (window.tarteaucitron.job = window.tarteaucitron.job || []).push('recaptcha');
});

export const fallback = (classes, service) => {
  window.tarteaucitron.fallback(classes, window.tarteaucitron.engage(service));

  [...document.getElementsByClassName('tarteaucitronAllow')].forEach(element => {
    window.tarteaucitron.addClickEventToElement(element, () => {
      window.tarteaucitron.userInterface.respond(element, true);
    });
  });

  [...document.getElementsByClassName('tarteaucitronDeny')].forEach(element => {
    window.tarteaucitron.addClickEventToElement(element, () => {
      window.tarteaucitron.userInterface.respond(element, false);
    });
  });
};

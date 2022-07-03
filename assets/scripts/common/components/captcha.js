import { fallback } from '../cookie';

/**
 * Bind google captcha on element parameter.
 *
 * @param element
 */
export const bindCaptcha = element => {
  if (window.tarteaucitron.launch['recaptcha'] !== true) {
    fallback(['g-recaptcha'], 'recaptcha');
  }

  window.grecaptcha && window.grecaptcha.render && window.grecaptcha.render(element, {
    'sitekey': element.dataset.sitekey,
  });
};

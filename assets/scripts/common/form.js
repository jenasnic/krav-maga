import { bindInputMask } from './components/input-mask';
import { bindCaptcha } from './components/captcha';

const mapping = {
  '[data-mask-input]': bindInputMask,
  '.g-recaptcha': bindCaptcha,
};

export const bindForm = (element) => {
  Object.entries(mapping).forEach(([selector, binder]) => {
    [...(element || document).querySelectorAll(selector)].forEach((element) => {
      binder(element);
    }
  )});
};

bindForm();
